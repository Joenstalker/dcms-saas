# DCMS SaaS - Complete System Analysis
**Date**: January 24, 2026  
**Project**: Dental Clinic Management System (Multi-Tenant SaaS)

---

## 📊 Executive Summary

**DCMS** is a sophisticated **Laravel 11-based multi-tenant SaaS application** designed to manage dental clinics. The system implements:
- ✅ **Multi-tenancy with shared database architecture** (tenant_id isolation)
- ✅ **Subdomain-based tenant identification** (clinic1.dcmsapp.local)
- ✅ **Comprehensive subscription management** with trial periods and automatic suspension
- ✅ **Dynamic pricing plans** with configurable features and usage limits
- ✅ **Role-based access control (RBAC)** via Spatie Laravel Permission
- ✅ **Modern frontend** using Blade, Tailwind CSS, DaisyUI, and Alpine.js
- ✅ **Auto-domain registration** with Windows hosts file integration
- ✅ **Email verification & setup wizard** for tenant onboarding

---

## 🏗️ Technology Stack

| Component | Technology | Version |
|-----------|-----------|---------|
| **Framework** | Laravel | 11.x |
| **PHP** | PHP | 8.2+ |
| **Database** | MySQL/PostgreSQL | Latest |
| **Frontend** | Blade, Tailwind CSS, DaisyUI | Latest |
| **Authentication** | Laravel Sanctum | 4.x |
| **Authorization** | Spatie Laravel Permission | 6.x |
| **JavaScript** | Alpine.js | Latest |
| **Build Tool** | Vite | Latest |

---

## 🎯 Core Features

### 1. Multi-Tenancy Architecture
```
Single Laravel Application Instance
         ↓
   Shared Database (MySQL)
         ↓
┌────────────────────────┐
│ Tenant Isolation       │
├────────────────────────┤
│ Tenant 1: clinic1      │
│ ├─ Users (5)           │
│ ├─ Patients            │
│ ├─ Appointments        │
│ └─ Settings            │
│                        │
│ Tenant 2: clinic2      │
│ ├─ Users (3)           │
│ ├─ Patients            │
│ ├─ Appointments        │
│ └─ Settings            │
└────────────────────────┘
```

**Tenant Identification Method**: Subdomain-based  
**Example**: `dental1.dcmsapp.local`, `dental2.dcmsapp.local`

**Data Isolation**: Every tenant-scoped table has `tenant_id` foreign key
```php
// Users belong to a tenant
$user->tenant_id = 1;

// Queries automatically filtered
User::where('tenant_id', $tenant->id)->get();
```

### 2. Subscription Management System
```
Registration → Email Verification → Setup Wizard → Trial/Subscription
     ↓                  ↓                  ↓             ↓
  Create Tenant    Activate Tenant    Configure     Active/Expired
  (Trial: 14 days)  (Email verified)   Clinic      Automatic suspension
```

**Subscription Status States**:
- `active` - Paid subscription active
- `trial` - Trial period (auto-expires after 14 days)
- `expired` - Subscription past due date
- `suspended` - Auto-suspended after expiration
- `cancelled` - User cancelled subscription

**Key Fields** (on `tenants` table):
```php
subscription_status     // enum: active, trial, expired, suspended, cancelled
subscription_ends_at    // timestamp: when paid subscription expires
trial_ends_at          // timestamp: when trial period ends
last_payment_date      // timestamp: last successful payment
suspended_at           // timestamp: when auto-suspended
```

**Automatic Suspension Flow**:
1. Tenant requests dashboard
2. `TenantMiddleware` checks subscription
3. If `subscription_ends_at` is in past:
   - Auto-update status to 'suspended'
   - Block access to dashboard
   - Redirect to suspension page
4. Admin page shows contact info for renewal

### 3. Pricing Plans & Features
**PricingPlan Model** contains:
- Plan name (e.g., "Basic", "Professional", "Enterprise")
- Price & billing period (monthly/yearly)
- Feature limits (users, patients, storage, etc.)
- Configurable features via JSON

**Tenants** can:
- Select plan during registration
- Change plan from dashboard
- Access features based on their assigned plan

### 4. Auto-Domain Registration (Recent Feature)
**Problem Solved**: 
- Manual domain entry was error-prone
- Windows users had to manually edit hosts file
- Inconsistent domain naming across tenants

**Solution Implemented**:
1. User enters subdomain only: `"myclinic"`
2. System auto-generates full domain: `"myclinic.dcmsapp.local"`
3. Auto-adds entry to Windows hosts file: `127.0.0.1 myclinic.dcmsapp.local`
4. Flushes DNS cache automatically
5. All operations logged (non-blocking - registration succeeds even if hosts file fails)

**Requirements**: Laravel runs as Administrator on Windows

### 5. Email Verification & Setup Wizard
**Registration Flow**:
1. User registers with email & password
2. Verification email sent
3. User clicks email link with token
4. Email verified → Tenant activated
5. User redirected to login (NOT auto-logged in)
6. User logs in
7. First-time users see Setup Wizard:
   - Step 1: Branding (logo, colors)
   - Step 2: Clinic Details (name, address, phone)
   - Step 3: Consent Forms
   - Step 4: Default Settings
   - Step 5: Pricing Plan Selection

---

## 📁 Project Structure

```
dcms-saas/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── AuthController.php          # Admin panel login
│   │   │   │   ├── DashboardController.php     # Admin dashboard
│   │   │   │   ├── TenantController.php        # Manage tenants
│   │   │   │   └── PricingPlanController.php   # Manage pricing plans
│   │   │   │
│   │   │   ├── Auth/
│   │   │   │   └── LoginController.php         # General login handler
│   │   │   │
│   │   │   └── Tenant/
│   │   │       ├── RegistrationController.php  # Register new clinic
│   │   │       ├── TenantLoginController.php   # Tenant-specific login
│   │   │       ├── VerificationController.php  # Email verification
│   │   │       ├── SubscriptionController.php  # Subscription management
│   │   │       ├── DashboardController.php     # Tenant dashboard
│   │   │       ├── SetupController.php         # Setup wizard
│   │   │       └── UserController.php          # User management
│   │   │
│   │   ├── Middleware/
│   │   │   ├── TenantMiddleware.php           # Tenant identification & validation
│   │   │   └── VerifyTenantSetup.php          # Redirect to setup if needed
│   │   │
│   │   └── Requests/
│   │       └── [Form validation classes]
│   │
│   ├── Models/
│   │   ├── User.php                # User model (belongs to Tenant)
│   │   ├── Tenant.php              # Tenant model (clinic)
│   │   └── PricingPlan.php         # Subscription plans
│   │
│   ├── Services/
│   │   └── TenantProvisioningService.php     # Tenant creation logic
│   │
│   ├── Notifications/
│   │   └── TenantVerificationNotification.php # Email verification
│   │
│   ├── Traits/
│   │   └── [Reusable trait classes]
│   │
│   └── Providers/
│       └── AppServiceProvider.php
│
├── resources/
│   ├── views/
│   │   ├── welcome.blade.php               # Landing page (main domain)
│   │   ├── tenant/
│   │   │   ├── registration/
│   │   │   │   ├── index.blade.php         # Registration form
│   │   │   │   ├── success.blade.php       # Registration success
│   │   │   │   └── modal-flow.blade.php    # Registration modal
│   │   │   │
│   │   │   ├── auth/
│   │   │   │   └── login.blade.php         # Clinic-specific login page
│   │   │   │
│   │   │   ├── dashboard.blade.php         # Tenant dashboard
│   │   │   ├── setup/                      # Setup wizard
│   │   │   └── subscription/               # Subscription pages
│   │   │
│   │   └── admin/                          # Admin panel views
│   │
│   ├── css/
│   │   └── [Tailwind CSS files]
│   │
│   └── js/
│       └── [Alpine.js & utility scripts]
│
├── routes/
│   ├── web.php                # All web routes
│   └── console.php            # Console commands
│
├── database/
│   ├── migrations/
│   │   ├── create_users_table
│   │   ├── create_tenants_table
│   │   ├── create_pricing_plans_table
│   │   ├── add_tenant_id_to_users_table
│   │   ├── add_email_verification_token_to_tenants_table
│   │   ├── add_branding_and_configuration_to_tenants_table
│   │   └── [More migrations...]
│   │
│   └── seeders/
│       └── DatabaseSeeder.php
│
├── config/
│   ├── permission.php         # Spatie permission config
│   └── [Other Laravel configs]
│
├── public/
│   ├── index.php
│   ├── storage -> ../storage/app/public
│   └── build/                 # Compiled assets (Vite output)
│
└── [Config, bootstrap, storage, vendor directories...]
```

---

## 🗄️ Database Schema Overview

### Core Tables

#### **Users Table**
```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY,
    tenant_id BIGINT NULLABLE FOREIGN KEY,  -- NULL = system admin
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    is_system_admin BOOLEAN DEFAULT false,
    remember_token VARCHAR(100) NULLABLE,
    email_verified_at TIMESTAMP NULLABLE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Key Points**:
- `tenant_id = NULL` → System admin
- `tenant_id > 0` → Tenant user (belongs to specific clinic)
- All tenant users must have verified email before accessing dashboard

#### **Tenants Table**
```sql
CREATE TABLE tenants (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255),                        -- Clinic name
    slug VARCHAR(255) UNIQUE,                 -- URL slug (e.g., "myclinic")
    domain VARCHAR(255) UNIQUE,               -- Full domain (e.g., "myclinic.dcmsapp.local")
    pricing_plan_id BIGINT FOREIGN KEY,       -- Selected plan
    
    -- Contact Info
    email VARCHAR(255),
    phone VARCHAR(20),
    address TEXT,
    city VARCHAR(100),
    state VARCHAR(100),
    zip_code VARCHAR(10),
    country VARCHAR(100),
    
    -- Subscription Management
    subscription_status ENUM('active','trial','expired','suspended','cancelled'),
    subscription_ends_at TIMESTAMP NULLABLE,
    trial_ends_at TIMESTAMP NULLABLE,
    last_payment_date TIMESTAMP NULLABLE,
    suspended_at TIMESTAMP NULLABLE,
    
    -- Branding
    logo VARCHAR(255) NULLABLE,               -- Logo file path
    primary_color VARCHAR(10) DEFAULT '#000000',
    secondary_color VARCHAR(10) DEFAULT '#666666',
    invoice_header TEXT NULLABLE,
    receipt_header TEXT NULLABLE,
    
    -- Configuration
    settings JSON,                            -- Flexible settings storage
    business_hours JSON,                      -- Operating hours
    consent_forms JSON,                       -- Stored consent form templates
    certificate_templates JSON,               -- Certificate configs
    default_hmo_providers JSON,               -- HMO provider list
    default_dental_services JSON,             -- Service catalog
    
    -- Status
    is_active BOOLEAN DEFAULT false,
    setup_completed BOOLEAN DEFAULT false,
    email_verified_at TIMESTAMP NULLABLE,
    email_verification_token VARCHAR(255) NULLABLE,
    
    -- Timestamps
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULLABLE            -- Soft deletes
};
```

#### **Pricing Plans Table**
```sql
CREATE TABLE pricing_plans (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255),                        -- "Basic", "Professional", "Enterprise"
    description TEXT,
    price DECIMAL(10, 2),
    billing_period VARCHAR(50),               -- "monthly" or "yearly"
    features JSON,                            -- Feature limits & configs
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Relationship Diagram
```
┌──────────────┐
│   Users      │
├──────────────┤
│ id (PK)      │
│ tenant_id (FK) ──┐
│ name         │   │
│ email        │   │
│ password     │   │
└──────────────┘   │
                   │
             ┌─────▼──────────┐
             │    Tenants     │
             ├────────────────┤
             │ id (PK)        │
             │ name           │
             │ slug           │
             │ domain         │
             │ pricing_plan_id├─────┐
             │ subscription.. │     │
             │ ...more fields │     │
             └────────────────┘     │
                                    │
                          ┌─────────▼─────────┐
                          │  Pricing Plans    │
                          ├───────────────────┤
                          │ id (PK)           │
                          │ name              │
                          │ price             │
                          │ features (JSON)   │
                          └───────────────────┘
```

**One-to-Many Relationships**:
- One Tenant has many Users
- One Pricing Plan is used by many Tenants

---

## 🔄 Request Flow & Routing

### Registration Flow (Public)
```
GET  /register                      → Show registration form
POST /register                      → Store new tenant
POST /register/verify-email         → Send verification email
GET  /register/check-subdomain      → Check subdomain availability (AJAX)
GET  /register/success/{tenant}     → Registration success page
```

### Email Verification Flow (Public)
```
GET  /verify/email/{token}/{email}  → Verify email & activate tenant
GET  /verify/success/{tenant}       → Verification success page
GET  /verify/failed                 → Verification failed page
```

### Tenant Authentication Flow
```
GET  /login                         → Show clinic-specific login
POST /login                         → Authenticate user
POST /logout                        → Logout user
```

**Key Feature**: Login page shows clinic name/logo (tenant-specific branding)

### Tenant Dashboard Flow (Protected - auth + subscription check)
```
GET  /tenant/{tenant}/dashboard     → Main dashboard
GET  /tenant/{tenant}/setup/{step}  → Setup wizard
POST /tenant/{tenant}/setup/*       → Save setup data
GET  /tenant/{tenant}/users         → User management
GET  /tenant/{tenant}/patients      → Patient management (placeholder)
GET  /tenant/{tenant}/appointments  → Appointments (placeholder)
```

### Admin Panel Flow (System Admin only)
```
GET  /admin/login                   → Admin login
POST /admin/login                   → Admin authentication
GET  /admin/dashboard               → Admin dashboard
GET  /admin/tenants                 → Manage all tenants
GET  /admin/pricing-plans           → Manage pricing plans
```

---

## 🔐 Authentication & Authorization

### Authentication Layers
1. **System Admin** - Can access `/admin` routes
2. **Tenant User** - Can access tenant dashboard with valid subscription
3. **Public User** - Can register and verify email

### Authorization Checks

**TenantMiddleware**:
```php
// Every request to tenant routes:
1. Extracts tenant from subdomain
2. Checks if tenant exists
3. Checks subscription status:
   - If expired → redirect to suspension page
   - If trial ended → redirect to renewal page
   - If suspended → show suspension page
4. Sets tenant context (session/cache)
```

**VerifyTenantSetup Middleware**:
```php
// After first login, redirects to setup wizard if:
- Setup not completed
- Email not verified
```

### Role-Based Access Control (Spatie)
Users can have roles:
- `admin` - Clinic administrator (all permissions)
- `dentist` - Dental professional (view patients, create appointments)
- `staff` - Reception/staff (schedule appointments, patient records)
- `patient` - Patient portal access (view appointments, records)

---

## 🎯 Key Business Logic

### 1. Subscription Lifecycle

**Trial Period** (Auto-expiring after 14 days):
```php
$tenant = Tenant::create([
    'subscription_status' => 'trial',
    'trial_ends_at' => now()->addDays(14),
    'subscription_ends_at' => null,  // No paid subscription yet
]);

// After 14 days, subscription considered 'expired' if not converted to paid
```

**Paid Subscription**:
```php
$tenant->update([
    'subscription_status' => 'active',
    'subscription_ends_at' => now()->addMonth(),  // Or custom renewal date
    'last_payment_date' => now(),
]);
```

**Auto-Suspension**:
```php
// In TenantMiddleware, on every request:
if ($tenant->subscription_ends_at->isPast()) {
    $tenant->update([
        'subscription_status' => 'suspended',
        'suspended_at' => now(),
    ]);
    return redirect('/subscription/suspended/' . $tenant->id);
}
```

### 2. Tenant Provisioning

**TenantProvisioningService handles**:
1. Creating tenant record
2. Auto-generating domain from subdomain
3. Sending verification email
4. Adding to Windows hosts file
5. Creating default roles & permissions

### 3. Email Verification

**Flow**:
1. User registers
2. Unique token generated: `email_verification_token`
3. Email sent with link: `/verify/email/{token}/{email}`
4. User clicks link
5. Token validated, email marked as verified
6. Tenant marked as active
7. User redirected to login page

### 4. Setup Wizard

**5-Step process** (all optional, but recommended):
1. **Branding**: Logo upload, color scheme
2. **Details**: Clinic name, address, phone, etc.
3. **Consent Forms**: Upload consent form templates
4. **Defaults**: Set default services, HMO providers
5. **Pricing**: Select subscription plan (if trial ending)

**Storage**: All config saved to `tenants` table:
```php
$tenant->update([
    'logo' => 'path/to/logo.png',
    'primary_color' => '#0066CC',
    'secondary_color' => '#FF6600',
    'business_hours' => ['mon' => '8:00-17:00', ...],
    'consent_forms' => [...],
    'default_dental_services' => [...],
    'setup_completed' => true,
]);
```

---

## 🌐 Domain Management

### Subdomain-Based Tenant Routing

**Configuration** (in `.env`):
```
LOCAL_BASE_DOMAIN=dcmsapp.local
```

**Tenant Identification**:
- `dcmsapp.local` → Main domain (landing page)
- `clinic1.dcmsapp.local` → Tenant 1
- `clinic2.dcmsapp.local` → Tenant 2

**TenantMiddleware Flow**:
```php
$subdomain = explode('.', $request->getHost())[0];  // Extract 'clinic1'
$tenant = Tenant::where('slug', $subdomain)->first();
if (!$tenant) abort(404);  // Tenant not found
session(['tenant' => $tenant]);  // Store in session
```

### Windows Hosts File Auto-Update

**Location**: `C:\Windows\System32\drivers\etc\hosts`

**Entry Format**:
```
127.0.0.1 clinic1.dcmsapp.local
127.0.0.1 clinic2.dcmsapp.local
```

**DNS Cache Flush**:
```
ipconfig /flushdns
```

---

## 📊 Data Isolation Strategy

**Every tenant-scoped table must include**:
```php
$table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
```

**All queries must filter by tenant**:
```php
// ❌ Wrong - could expose other clinic's data
User::where('role', 'dentist')->get();

// ✅ Correct - tenant-aware query
auth()->user()->tenant->users()->where('role', 'dentist')->get();
```

**Best Practice**: Use Laravel's global scopes to auto-filter:
```php
class User extends Authenticatable {
    protected static function booted() {
        static::addGlobalScope('tenant', function ($query) {
            $query->where('tenant_id', session('tenant_id'));
        });
    }
}
```

---

## 📦 Dependencies

### Backend Dependencies
- `laravel/framework:^11.0` - Core framework
- `laravel/sanctum:^4.0` - API authentication
- `spatie/laravel-permission:^6.0` - RBAC
- `laravel/tinker:^2.9` - REPL

### Frontend Dependencies
- `tailwindcss` - Styling framework
- `daisyui` - Component library
- `alpinejs` - Lightweight JavaScript
- `vite` - Build tool

### Development Dependencies
- `phpunit/phpunit:^11.0.1` - Testing
- `laravel/pint:^1.13` - Code formatting
- `mockery/mockery:^1.6` - Mocking

---

## 🚀 Current Development Status

### ✅ Completed Features
- [x] Multi-tenant architecture with shared database
- [x] Subdomain-based tenant identification
- [x] User authentication & authorization
- [x] Email verification system
- [x] Subscription management with auto-suspension
- [x] Pricing plan management
- [x] Setup wizard for clinic configuration
- [x] Admin panel for SaaS provider
- [x] Auto-domain generation & Windows hosts file integration
- [x] Tenant-specific login pages with branding
- [x] Role-based access control (RBAC)
- [x] Soft deletes for tenants & users

### 🔄 In Progress / Planned
- [ ] Patient management module (placeholder exists)
- [ ] Appointment scheduling system
- [ ] Dental service catalog & procedures
- [ ] Financial management (invoicing, payments)
- [ ] Inventory management
- [ ] Reports & analytics
- [ ] SMS/Email notifications
- [ ] Mobile app (future)
- [ ] API documentation
- [ ] Automated tests (unit & integration)

### 📝 Documentation Files
- `README.md` - Project overview
- `PROJECT_PLAN.md` - Detailed project plan
- `SYSTEM_ANALYSIS.md` - Comprehensive system analysis
- `IMPLEMENTATION_SUMMARY.md` - Recent auto-domain changes
- `DEVELOPER_SETUP.md` - Developer setup guide
- `QUICK_START.md` - Quick start guide
- `SUBSCRIPTION_SYSTEM.md` - Subscription management docs
- `TENANT_LOGIN_PAGE.md` - Tenant login page documentation
- `PROVIDER_TENANT_AUTH_SEPARATION.md` - Auth separation
- Plus many more...

---

## 🎓 Learning Path for New Developers

1. **Start Here**: Read [README.md](README.md) & [QUICK_START.md](QUICK_START.md)
2. **Setup**: Follow [DEVELOPER_SETUP.md](DEVELOPER_SETUP.md)
3. **Understanding**: Read [SYSTEM_ANALYSIS.md](SYSTEM_ANALYSIS.md) & [PROJECT_PLAN.md](PROJECT_PLAN.md)
4. **Deep Dive**: Explore specific documentation:
   - Subscription flow: [SUBSCRIPTION_SYSTEM.md](SUBSCRIPTION_SYSTEM.md)
   - Multi-tenancy: [PROVIDER_TENANT_AUTH_SEPARATION.md](PROVIDER_TENANT_AUTH_SEPARATION.md)
   - Recent features: [IMPLEMENTATION_SUMMARY.md](IMPLEMENTATION_SUMMARY.md)
5. **Code Exploration**:
   - Routes: `routes/web.php`
   - Controllers: `app/Http/Controllers/`
   - Models: `app/Models/`
   - Middleware: `app/Http/Middleware/`

---

## 🔧 Common Development Tasks

### Adding a New Tenant Feature
1. Create migration for new table
2. Add `tenant_id` foreign key
3. Create model with relationships
4. Create controller with authorization
5. Add routes in `routes/web.php`
6. Create views in `resources/views/tenant/`

### Debugging Tenant Context
```php
// In any controller or view:
$tenant = session('tenant');  // Get current tenant
$user = auth()->user();       // Get current user
dd($user->tenant);            // Debug tenant relationship
```

### Testing Multi-Tenancy Locally
```bash
# Run Laravel with hosts file configured:
# Add to C:\Windows\System32\drivers\etc\hosts:
#   127.0.0.1 dcmsapp.local
#   127.0.0.1 clinic1.dcmsapp.local
#   127.0.0.1 clinic2.dcmsapp.local

php artisan serve

# Visit in browser:
http://dcmsapp.local:8000          # Landing page
http://clinic1.dcmsapp.local:8000  # Clinic 1 login
http://clinic2.dcmsapp.local:8000  # Clinic 2 login
```

---

## 📚 Key Files Reference

| File | Purpose |
|------|---------|
| [routes/web.php](routes/web.php) | All route definitions |
| [app/Models/Tenant.php](app/Models/Tenant.php) | Tenant model & logic |
| [app/Models/User.php](app/Models/User.php) | User model |
| [app/Http/Controllers/Tenant/RegistrationController.php](app/Http/Controllers/Tenant/RegistrationController.php) | Tenant registration |
| [app/Http/Controllers/Tenant/TenantLoginController.php](app/Http/Controllers/Tenant/TenantLoginController.php) | Tenant login |
| [app/Http/Middleware/TenantMiddleware.php](app/Http/Middleware/TenantMiddleware.php) | Tenant identification & validation |
| [app/Services/TenantProvisioningService.php](app/Services/TenantProvisioningService.php) | Tenant creation logic |
| [database/migrations/](database/migrations/) | Database schema definitions |
| [resources/views/tenant/](resources/views/tenant/) | Tenant UI views |
| [resources/views/admin/](resources/views/admin/) | Admin panel views |

---

## 🎯 System Design Highlights

### Why Multi-Tenancy with Shared Database?
- **Cost-effective**: One database for all tenants
- **Scalable**: Easy to add new tenants
- **Maintainable**: Single codebase, easy updates
- **Security**: Tenant isolation via `tenant_id` column

### Why Subdomain-Based Routing?
- **User-friendly**: No complex URL patterns
- **Professional**: `clinic1.dcmsapp.com` looks better than `dcmsapp.com/clinic1`
- **SEO-friendly**: Each tenant can have custom branding
- **Easy debugging**: Can identify tenant from URL

### Why Laravel Sanctum + Role-Based Permissions?
- **Flexible**: Easy to add new roles & permissions
- **Secure**: Token-based API authentication ready
- **Auditable**: Can track who did what
- **Scalable**: Works with microservices architecture

---

## ⚙️ Configuration Files

### Key Config Files
- `.env` - Environment variables (database, mail, app settings)
- `config/app.php` - Application settings
- `config/database.php` - Database connections
- `config/permission.php` - Spatie permission config
- `tailwind.config.js` - Tailwind CSS customization
- `vite.config.js` - Vite build configuration

### Important Environment Variables
```
APP_NAME=DCMS
APP_ENV=local
APP_DEBUG=true
APP_URL=http://dcmsapp.local:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dcms_saas
DB_USERNAME=root
DB_PASSWORD=

MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_FROM_ADDRESS=support@dcmsapp.com

LOCAL_BASE_DOMAIN=dcmsapp.local
```

---

## 🎉 Next Steps for Development

1. **Review Code**: Explore [app/Http/Controllers/](app/Http/Controllers/) & [app/Models/](app/Models/)
2. **Understand Flow**: Trace through registration → verification → dashboard access
3. **Read Docs**: All documentation files are in the root directory
4. **Setup Locally**: Follow [DEVELOPER_SETUP.md](DEVELOPER_SETUP.md)
5. **Test Features**: Test registration, email verification, login, subscription
6. **Contribute**: Pick a placeholder module and start implementing (e.g., Patient Management)

---

**Last Updated**: January 24, 2026  
**Status**: Active Development
