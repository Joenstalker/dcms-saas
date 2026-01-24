# Dynamic Pricing Plans Management

## Overview

The admin panel now includes a complete CRUD system for managing pricing plans. All plans can be created, edited, and customized dynamically without touching code.

## Features

### 1. **Fully Dynamic Plans**
- Create unlimited pricing plans
- Modify existing plans anytime
- No code changes required
- Changes reflect immediately

### 2. **Free Trial System**
- Each plan can have its own trial period (0-365 days)
- Configurable trial days per plan
- Automatic trial activation on subscription
- Trial status tracking

### 3. **Flexible Pricing**
- Set any price (₱0 for free plans)
- Three billing cycles:
  - Monthly
  - Quarterly (3 months)
  - Yearly
- Change prices anytime

### 4. **Usage Limits**
- Max users per plan (or unlimited)
- Max patients per plan (or unlimited)
- Enforced at application level

### 5. **Feature Management**
- Add/remove features dynamically
- Unlimited features per plan
- Displayed as bullet points
- Easy to customize

### 6. **Visual Customization**
- Mark plans as "Popular"
- Add custom badges (e.g., "Best Value", "Most Popular")
- Choose badge colors
- Sort order control

### 7. **Plan Status Control**
- Activate/deactivate plans instantly
- Inactive plans hidden from selection
- Cannot delete plans with active tenants

## Admin Interface

### **Pricing Plans Index** (`/admin/pricing-plans`)

Shows all plans in a responsive grid:
- **Card-based layout** showing:
  - Plan name and slug
  - Price and billing cycle
  - Trial days (if applicable)
  - User and patient limits
  - Top 5 features
  - Active tenant count
  - Status (Active/Inactive)
  
- **Quick actions:**
  - Edit plan
  - Activate/Deactivate
  - Delete (if no tenants using it)

### **Create/Edit Plan** (`/admin/pricing-plans/create`, `/admin/pricing-plans/{id}/edit`)

Comprehensive form with sections:

#### **Basic Information**
- Plan name (e.g., "Basic", "Pro", "Ultimate")
- Slug (auto-generated from name)
- Description
- Sort order

#### **Pricing & Billing**
- Price (₱) - use 0 for free plans
- Billing cycle (monthly/quarterly/yearly)
- **Free trial days** (0 = no trial)

#### **Usage Limits**
- Max users (leave empty for unlimited)
- Max patients (leave empty for unlimited)

#### **Features**
- Dynamic feature list
- Add/remove features with buttons
- Displays as checkmarks on plan cards

#### **Badge Settings**
- Badge text (e.g., "Most Popular")
- Badge color (Primary, Secondary, Success, Warning, Error)
- Shows only if plan marked as popular

#### **Status**
- **Active** toggle - make plan available
- **Popular** toggle - highlight plan

## Database Structure

### New Fields Added to `pricing_plans` Table:

```php
'trial_days' => 'integer',      // Number of free trial days (default: 0)
'is_popular' => 'boolean',      // Highlight as popular plan
'badge_text' => 'string',       // Custom badge text
'badge_color' => 'string',      // Badge color class
```

## How Trials Work

### **When Tenant Selects Plan:**

1. System checks `trial_days` on selected plan
2. If `trial_days > 0`:
   - Sets `trial_ends_at` = now() + trial_days
   - Sets `subscription_status` = 'trial'
   - Tenant gets full access during trial
3. If `trial_days = 0`:
   - No trial period
   - Sets `subscription_status` = 'active'
   - Subscription starts immediately

### **Trial Expiration:**

1. System checks `trial_ends_at` date
2. If trial expired:
   - Marks tenant as 'suspended'
   - Shows suspension page
   - Tenant must pay to continue

### **Example:**

**Scenario:** 2 tenants bypassed by admin to Basic plan

**Current Status:**
- They have `pricing_plan_id` = Basic
- But `trial_ends_at` is NULL (no trial set)
- Status is likely 'active'

**To Give Them Trial:**

Option 1: Admin manually updates their record:
```php
php artisan tinker

$tenant = Tenant::find(1);
$tenant->update([
    'subscription_status' => 'trial',
    'trial_ends_at' => now()->addDays(7),
    'subscription_ends_at' => now()->addMonth(),
]);
```

Option 2: They select a plan through normal flow (gets trial automatically)

## Answering Your Questions

### Q1: "2 tenants bypassed by admin to Basic - Is that trial only?"

**Answer:** Not necessarily. If the admin manually assigned them the plan, they likely didn't get the automatic trial setup. They would be marked as 'active' without a `trial_ends_at` date.

**To make them trial:**
- Update their `subscription_status` to 'trial'
- Set `trial_ends_at` to now() + 7 days
- Or have them go through the normal subscription flow

### Q2: "Do we need to add 7 days free trial?"

**Answer:** YES! The system now supports this. Each plan has a `trial_days` field:
- Free Trial plan: 7 days
- Basic: 7 days  
- Pro: 14 days
- Ultimate: 14 days

You can set any number of trial days per plan in the admin panel.

### Q3: "Admin can modify pricing plans dynamically?"

**Answer:** YES! Absolutely. The admin can:
- ✅ Create new plans (Free Trial, Basic, Pro, Ultimate, Custom, etc.)
- ✅ Edit existing plans
- ✅ Change prices anytime
- ✅ Add/remove features
- ✅ Modify trial periods
- ✅ Change billing cycles
- ✅ Update limits (users, patients)
- ✅ Activate/deactivate plans
- ✅ Delete unused plans

All changes are immediate - no code deployment needed!

## Usage Examples

### Create a Custom Plan

1. Go to `/admin/pricing-plans`
2. Click "Add New Plan"
3. Fill in details:
   - Name: "Startup Special"
   - Price: ₱1,499
   - Billing: Monthly
   - Trial Days: 30
   - Max Users: 5
   - Max Patients: 1000
   - Features: List all included features
   - Mark as Popular: Yes
   - Badge: "Limited Offer" (Warning color)
4. Click "Create Plan"

### Modify Existing Plan

1. Go to `/admin/pricing-plans`
2. Click "Edit" on any plan
3. Change any field (e.g., increase price, add trial days)
4. Click "Update Plan"
5. Changes apply immediately

### Seasonal Pricing

Example: Holiday discount

1. Edit "Pro" plan
2. Change price from ₱2,499 to ₱1,999
3. Change badge to "Holiday Special - 20% Off"
4. Save
5. Revert after holiday season ends

## API/Routes

```php
// Admin Pricing Plans
GET    /admin/pricing-plans              // List all plans
GET    /admin/pricing-plans/create       // Create form
POST   /admin/pricing-plans              // Store new plan
GET    /admin/pricing-plans/{id}         // View plan details
GET    /admin/pricing-plans/{id}/edit    // Edit form
PUT    /admin/pricing-plans/{id}         // Update plan
DELETE /admin/pricing-plans/{id}         // Delete plan
POST   /admin/pricing-plans/{id}/toggle-active // Activate/Deactivate
```

## Validation Rules

- **Name:** Required, max 255 characters
- **Slug:** Unique, auto-generated if empty
- **Price:** Required, numeric, min 0
- **Billing Cycle:** Required, must be monthly/quarterly/yearly
- **Trial Days:** Required, integer, min 0
- **Features:** Array of strings
- **Max Users:** Optional, integer, min 1 (null = unlimited)
- **Max Patients:** Optional, integer, min 1 (null = unlimited)

## Best Practices

### 1. **Trial Periods:**
- Offer 7-14 days for paid plans
- Longer trials for expensive plans
- Consider 30 days for annual billing

### 2. **Pricing Strategy:**
- Keep 3-4 plans maximum
- Clear differentiation between tiers
- Mark most profitable plan as "Popular"

### 3. **Features:**
- List 5-8 key features
- Most important features first
- Use clear, benefit-focused language

### 4. **Plan Updates:**
- Don't reduce features on existing subscribers
- Grandfather old prices for current users
- Create new plan versions instead

### 5. **Free Plans:**
- Set price to 0
- Limit users and patients significantly
- Use as lead magnet

## Testing

### Test Creating a Plan:

1. Access `/admin/pricing-plans`
2. Click "Add New Plan"
3. Fill all required fields
4. Add 3-5 features
5. Enable trial (7 days)
6. Mark as active
7. Save and verify

### Test Trial Functionality:

```bash
# Create test tenant
php artisan tinker

$tenant = Tenant::first();
$plan = PricingPlan::where('slug', 'basic')->first();

# Simulate subscription with trial
$tenant->update([
    'pricing_plan_id' => $plan->id,
    'subscription_status' => 'trial',
    'trial_ends_at' => now()->addDays($plan->trial_days),
    'subscription_ends_at' => now()->addMonth(),
]);

# Check status
echo $tenant->hasActiveSubscription() ? 'Active' : 'Expired';
```

## Future Enhancements

Suggested features for v2:

1. **Plan Comparison Table** - Side-by-side feature comparison
2. **Usage Analytics** - Track which plans are most popular
3. **Revenue Forecasting** - Predict MRR based on active plans
4. **Coupon Codes** - Discount codes per plan
5. **Plan Addons** - Extra features that can be purchased
6. **Annual Discounts** - Auto-apply discount for yearly billing
7. **Plan Recommendations** - Suggest upgrade based on usage

## Support

The pricing plan system is fully self-service. Admins can:
- Create unlimited plans
- Modify everything dynamically
- See real-time tenant counts
- Control trial periods

No developer intervention needed for normal pricing changes!
