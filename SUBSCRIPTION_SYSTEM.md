# Subscription Expiration & Suspension System

## Overview

This document describes the subscription management system that handles expired subscriptions, suspended tenants, and renewal workflows.

## Features

### 1. Subscription Status Tracking

**Database Fields** (`tenants` table):
- `subscription_status` (enum): 'active', 'trial', 'expired', 'suspended', 'cancelled'
- `subscription_ends_at` (timestamp): When the subscription expires
- `trial_ends_at` (timestamp): When the trial period ends
- `last_payment_date` (timestamp): Last successful payment date
- `suspended_at` (timestamp): When the tenant was suspended

### 2. Automatic Suspension

**How It Works:**
1. When a tenant's subscription expires (past `subscription_ends_at` date)
2. The `TenantMiddleware` checks subscription status on every request
3. If expired, the tenant is automatically marked as 'suspended'
4. All access to tenant dashboard is blocked
5. Users are redirected to the suspension page

**Middleware Logic:**
```php
// Located in: app/Http/Middleware/TenantMiddleware.php
- Checks if subscription is active using $tenant->hasActiveSubscription()
- If expired, updates status to 'suspended'
- Redirects to suspension page (except for suspension page itself)
```

### 3. Suspension Page

**What Tenants See:**
- Clear message that their subscription has expired
- Reassurance that their data is safe and preserved
- Contact information to reach administrator:
  - Email: Configurable via `MAIL_FROM_ADDRESS`
  - Phone: Configurable via `APP_SUPPORT_PHONE`
- Optional quick renewal button to select a new plan

**Page Location:** `resources/views/tenant/subscription/suspended.blade.php`

### 4. Subscription Status Methods

**Tenant Model Methods:**

```php
// Check if tenant has an active subscription
$tenant->hasActiveSubscription() : bool

// Check if subscription is expired
$tenant->isSubscriptionExpired() : bool

// Check if tenant is suspended
$tenant->isSuspended() : bool

// Get HTML badge for subscription status
$tenant->getSubscriptionStatusBadge() : string

// Get days until subscription expires (negative if overdue)
$tenant->getDaysUntilExpiration() : ?int
```

### 5. Admin Panel Features

**Subscription Column:**
- Shows subscription status badge (Active, Trial, Expired, Suspended, Cancelled)
- Displays subscription end date
- Shows warning badges:
  - "X days left" if expiring within 7 days
  - "X days overdue" if already expired

**View Details Modal:**
- Shows subscription status
- Displays payment information
- Shows expiration dates

### 6. Automated Cron Job

**Command:** `php artisan subscriptions:check-expired`

**What It Does:**
- Scans all tenants with 'active' or 'trial' status
- Checks if `subscription_ends_at` or `trial_ends_at` has passed
- Automatically suspends expired tenants
- Updates `subscription_status` to 'suspended'
- Sets `suspended_at` timestamp

**Setup:**
Add to your server's cron tab (runs daily at midnight):
```bash
0 0 * * * cd /path/to/dcms-saas && php artisan subscriptions:check-expired
```

Or use Laravel's scheduler in `routes/console.php`:
```php
Schedule::command('subscriptions:check-expired')->daily();
```

## Configuration

### Environment Variables

Add to your `.env` file:

```env
APP_SUPPORT_PHONE="+63 123 456 7890"
APP_SUPPORT_EMAIL="support@dcms.com"
```

### Payment Processing

When a tenant successfully pays for a subscription:

```php
$tenant->update([
    'subscription_status' => 'active',
    'subscription_ends_at' => now()->addMonth(), // or addMonths(3), addYear()
    'last_payment_date' => now(),
    'suspended_at' => null,
]);
```

## Subscription Statuses

| Status | Description | Tenant Access |
|--------|-------------|---------------|
| **active** | Subscription is paid and active | Full access |
| **trial** | In trial period | Full access |
| **expired** | Subscription ended, grace period | Limited/No access |
| **suspended** | Automatically suspended due to non-payment | Suspended page only |
| **cancelled** | Manually cancelled by admin or tenant | No access |

## Workflows

### When Subscription Expires:

1. **Automated Check** (via cron job):
   - Command runs: `subscriptions:check-expired`
   - Finds expired subscriptions
   - Marks as 'suspended'

2. **On Next Login Attempt**:
   - `TenantMiddleware` checks subscription
   - Detects expired status
   - Redirects to suspension page

3. **Suspension Page**:
   - Shows expiration message
   - Displays contact information
   - Offers renewal option

### Renewal Process:

1. **Tenant contacts admin** (via displayed contact info)
2. **Admin processes payment** (offline or through payment gateway)
3. **Admin updates subscription**:
   - Through admin panel or payment callback
   - Sets new `subscription_ends_at` date
   - Changes status to 'active'
4. **Tenant regains access** immediately

### Alternative: Self-Service Renewal

Tenant clicks "Renew Subscription" button → Redirected to plan selection → Payment processing → Automatic reactivation

## Security & Data Protection

✅ **Data is Never Deleted:**
- When suspended, tenant data remains in database
- All patient records, appointments, and settings are preserved
- Reactivation grants immediate access to all historical data

✅ **Access Control:**
- Suspended tenants cannot access dashboard
- Only suspension page is accessible
- Middleware enforces access restrictions

✅ **Grace Periods:**
- Configure grace periods by adjusting suspension logic
- Can allow X days after expiration before suspension

## Admin Actions

### Manual Suspension:
```php
$tenant->update([
    'subscription_status' => 'suspended',
    'suspended_at' => now(),
]);
```

### Reactivate Tenant:
```php
$tenant->update([
    'subscription_status' => 'active',
    'subscription_ends_at' => now()->addMonth(),
    'last_payment_date' => now(),
    'suspended_at' => null,
]);
```

### Extend Subscription:
```php
$tenant->subscription_ends_at = $tenant->subscription_ends_at->addMonth();
$tenant->save();
```

## Testing

### Test Expired Subscription:

1. **Manually expire a tenant:**
   ```php
   php artisan tinker
   
   $tenant = Tenant::find(1);
   $tenant->update([
       'subscription_ends_at' => now()->subDay(),
       'subscription_status' => 'active'
   ]);
   ```

2. **Run the check command:**
   ```bash
   php artisan subscriptions:check-expired
   ```

3. **Try to access tenant dashboard** - should redirect to suspension page

### Test Renewal:

1. Access suspension page
2. Click "Renew Subscription"
3. Complete payment
4. Verify access is restored

## Future Enhancements

### Suggested Improvements:

1. **Email Notifications:**
   - Warning emails before expiration (7 days, 3 days, 1 day)
   - Suspension notification email
   - Renewal confirmation email

2. **Grace Period:**
   - Allow X days after expiration before suspension
   - Configure per plan or globally

3. **Payment Integration:**
   - Stripe/PayPal integration for automated renewal
   - Recurring billing
   - Payment reminders

4. **Admin Dashboard:**
   - Graph of subscriptions expiring this month
   - Revenue forecasting
   - Churn rate tracking

5. **Self-Service Portal:**
   - Billing history
   - Invoice downloads
   - Payment method management

## Routes

| Route | Purpose |
|-------|---------|
| `/subscription/suspended/{tenant}` | Suspension page (no auth required) |
| `/subscription/select-plan/{tenant}` | Plan selection for renewal (auth required) |
| `/subscription/payment/{tenant}/{plan}` | Payment processing (auth required) |

## Files Modified

### Models:
- `app/Models/Tenant.php` - Added subscription methods

### Middleware:
- `app/Http/Middleware/TenantMiddleware.php` - Added subscription checks

### Controllers:
- `app/Http/Controllers/Tenant/SubscriptionController.php` - Added `suspended()` method

### Views:
- `resources/views/tenant/subscription/suspended.blade.php` - Suspension page
- `resources/views/admin/tenants/index.blade.php` - Added subscription column

### Migrations:
- `database/migrations/2026_01_24_004014_add_subscription_status_to_tenants_table.php`

### Commands:
- `app/Console/Commands/CheckExpiredSubscriptions.php` - Automated suspension

## Support

For questions or issues with the subscription system, contact the development team or refer to this documentation.
