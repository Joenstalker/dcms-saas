# DCMS SaaS - CRUD & Database Migration Analysis
**Date**: January 24, 2026

---

## 📊 Complete Database Schema Analysis

### Migration Timeline & Order

**Foundation Layer**:
1. `2026_01_23_135820` - Create `users` table
2. `2026_01_23_135830` - Create `pricing_plans` table
3. `2026_01_23_135831` - Create `tenants` table
4. `2026_01_23_135922` - Add `tenant_id` FK to `users` table

**Authorization & Sessions**:
5. `2026_01_23_140313` - Create permission tables (Spatie RBAC)
6. `2026_01_23_141610` - Create `sessions` table
7. `2026_01_23_141617` - Create `cache` table

**Tenant Extensions**:
8. `2026_01_23_142711` - Add email verification fields to `tenants`
9. `2026_01_23_143339` - Add branding & configuration to `tenants`
10. `2026_01_23_143901` - Create tenant master file tables
11. `2026_01_23_144455` - Add `tenant_id` to `roles` table
12. `2026_01_23_153214` - Make `pricing_plan_id` nullable in `tenants`
13. `2026_01_23_163551` - Add unique constraint to `tenants.email`
14. `2026_01_23_164116` - Add unique constraint to `tenants.phone`

**Subscription Features**:
15. `2026_01_24_004014` - Add subscription status tracking to `tenants`
16. `2026_01_24_005226` - Add trial days to `pricing_plans`

---

## 🗄️ Detailed Table Schemas

### 1. **Users Table**

**Purpose**: System-wide user accounts (system admins + tenant users)

```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255),
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Migration Code**:
```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->rememberToken();
    $table->timestamps();
});
```

**Extended by**: 
- `2026_01_23_135922` adds `tenant_id` foreign key

**Final Schema**:
```sql
id, name, email, email_verified_at, password, remember_token, 
tenant_id (FK), is_system_admin, created_at, updated_at
```

---

### 2. **Pricing Plans Table**

**Purpose**: Store all subscription tiers with features & pricing

**Original Schema** (`2026_01_23_135830`):
```sql
CREATE TABLE pricing_plans (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),              -- "Basic", "Pro", "Ultimate"
    slug VARCHAR(255) UNIQUE,       -- "basic", "pro", "ultimate"
    description TEXT NULL,
    price DECIMAL(10, 2) DEFAULT 0,
    billing_cycle VARCHAR(255) DEFAULT 'monthly', -- monthly, quarterly, yearly
    features JSON NULL,             -- Array of feature strings
    max_users INT NULL,             -- NULL = unlimited
    max_patients INT NULL,          -- NULL = unlimited
    is_active BOOLEAN DEFAULT 1,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Extended by** (`2026_01_24_005226`):
```sql
ALTER TABLE pricing_plans ADD COLUMN trial_days INT;
```

**Final Schema**:
```sql
id, name, slug, description, price, billing_cycle, 
features (JSON), max_users, max_patients, trial_days,
is_active, is_popular, badge_text, badge_color, 
sort_order, created_at, updated_at
```

**Key Features**:
- Dynamic feature list (stored as JSON array)
- Per-plan trial days (0-365)
- Billing cycles: Monthly (30d), Quarterly (90d), Yearly (365d)
- Usage limits (nullable = unlimited)
- Sort order for UI display
- Popular badge for featured plans

---

### 3. **Tenants Table**

**Purpose**: Clinic/tenant accounts with all configuration

**Original Schema** (`2026_01_23_135831`):
```sql
CREATE TABLE tenants (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),              -- Clinic name
    slug VARCHAR(255) UNIQUE,       -- Subdomain identifier
    domain VARCHAR(255) NULL,       -- Custom domain
    pricing_plan_id BIGINT FK,      -- Link to pricing plan
    email VARCHAR(255),
    phone VARCHAR(255) NULL,
    address TEXT NULL,
    city VARCHAR(255) NULL,
    state VARCHAR(255) NULL,
    zip_code VARCHAR(255) NULL,
    country VARCHAR(255) DEFAULT 'Philippines',
    logo VARCHAR(255) NULL,
    settings JSON NULL,
    is_active BOOLEAN DEFAULT 1,
    trial_ends_at TIMESTAMP NULL,
    subscription_ends_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL       -- Soft deletes
);
```

**Extended by** (`2026_01_23_142711` - Email Verification):
```sql
ALTER TABLE tenants ADD COLUMN 
    email_verification_token VARCHAR(255) NULL,
    email_verified_at TIMESTAMP NULL;
```

**Extended by** (`2026_01_23_143339` - Branding & Configuration):
```sql
ALTER TABLE tenants ADD COLUMN
    setup_completed BOOLEAN DEFAULT 0,
    primary_color VARCHAR(255) NULL,
    secondary_color VARCHAR(255) NULL,
    invoice_header TEXT NULL,
    receipt_header TEXT NULL,
    business_hours JSON NULL,
    consent_forms JSON NULL,
    certificate_templates JSON NULL,
    default_hmo_providers JSON NULL,
    default_dental_services JSON NULL;
```

**Updated by** (`2026_01_23_153214`):
```sql
ALTER TABLE tenants MODIFY pricing_plan_id BIGINT NULL;
-- pricing_plan_id became nullable
```

**Added Constraints** (`2026_01_23_163551`, `2026_01_23_164116`):
```sql
ALTER TABLE tenants ADD UNIQUE(email);
ALTER TABLE tenants ADD UNIQUE(phone);
```

**Extended by** (`2026_01_24_004014` - Subscription Status):
```sql
ALTER TABLE tenants ADD COLUMN
    subscription_status ENUM('active', 'trial', 'expired', 'suspended', 'cancelled') DEFAULT 'trial',
    last_payment_date TIMESTAMP NULL,
    suspended_at TIMESTAMP NULL;
```

**Final Complete Schema**:
```sql
id, name, slug, domain,
pricing_plan_id (FK, nullable),
email (UNIQUE), phone (UNIQUE),
address, city, state, zip_code, country,
logo,
settings (JSON),
is_active, setup_completed,
primary_color, secondary_color,
invoice_header, receipt_header,
business_hours (JSON), 
consent_forms (JSON), 
certificate_templates (JSON),
default_hmo_providers (JSON),
default_dental_services (JSON),
trial_ends_at, subscription_ends_at,
subscription_status (ENUM), last_payment_date, suspended_at,
email_verification_token,
email_verified_at,
created_at, updated_at, deleted_at (soft delete)
```

**Data Types Summary**:
- **JSON Columns**: settings, business_hours, consent_forms, certificate_templates, default_hmo_providers, default_dental_services
- **ENUM**: subscription_status (5 states)
- **TIMESTAMPS**: trial_ends_at, subscription_ends_at, last_payment_date, suspended_at, email_verified_at, created_at, updated_at, deleted_at

---

### 4. **Spatie RBAC Tables** (`2026_01_23_140313`)

```sql
CREATE TABLE roles (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    guard_name VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

CREATE TABLE permissions (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    guard_name VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

CREATE TABLE model_has_roles (
    role_id BIGINT FK,
    model_id BIGINT,
    model_type VARCHAR(255),
    PRIMARY KEY (role_id, model_id, model_type)
);

CREATE TABLE model_has_permissions (
    permission_id BIGINT FK,
    model_id BIGINT,
    model_type VARCHAR(255),
    PRIMARY KEY (permission_id, model_id, model_type)
);

CREATE TABLE role_has_permissions (
    permission_id BIGINT FK,
    role_id BIGINT FK,
    PRIMARY KEY (permission_id, role_id)
);
```

**Extended by** (`2026_01_23_144455`):
```sql
ALTER TABLE roles ADD COLUMN tenant_id BIGINT NULL FK;
-- Allows tenant-scoped roles
```

---

### 5. **Tenant Master File Tables** (`2026_01_23_143901`)

#### **tenant_services**
```sql
CREATE TABLE tenant_services (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    tenant_id BIGINT FK (onDelete CASCADE),
    name VARCHAR(255),
    description TEXT NULL,
    price DECIMAL(10, 2) DEFAULT 0,
    category VARCHAR(255) NULL,      -- e.g., "Cleaning", "Filling", "Root Canal"
    is_active BOOLEAN DEFAULT 1,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    INDEX (tenant_id, is_active)
);
```

#### **tenant_medicines**
```sql
CREATE TABLE tenant_medicines (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    tenant_id BIGINT FK (onDelete CASCADE),
    name VARCHAR(255),
    description TEXT NULL,
    unit VARCHAR(255) DEFAULT 'piece', -- tablet, capsule, bottle, ml, etc.
    stock INT DEFAULT 0,
    price DECIMAL(10, 2) NULL,
    expiry_date DATE NULL,
    is_active BOOLEAN DEFAULT 1,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    INDEX (tenant_id, is_active)
);
```

#### **tenant_templates**
```sql
CREATE TABLE tenant_templates (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    tenant_id BIGINT FK (onDelete CASCADE),
    name VARCHAR(255),
    type VARCHAR(255),               -- consent, certificate, invoice, receipt, etc.
    content TEXT NULL,
    fields JSON NULL,                -- Dynamic fields: name, date, clinic, etc.
    is_active BOOLEAN DEFAULT 1,
    is_default BOOLEAN DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    INDEX (tenant_id, type, is_active)
);
```

---

### 6. **Sessions & Cache Tables**

#### **sessions** (`2026_01_23_141610`)
```sql
CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT,
    last_activity INT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    INDEX (user_id, last_activity)
);
```

#### **cache** (`2026_01_23_141617`)
```sql
CREATE TABLE cache (
    key VARCHAR(255) PRIMARY KEY,
    value LONGTEXT,
    expiration INT,
    INDEX (expiration)
);
```

---

## 🎯 CRUD Operations Analysis

### Admin Controllers

#### **1. PricingPlanController** (Full CRUD + Custom Actions)

**Location**: [app/Http/Controllers/Admin/PricingPlanController.php](app/Http/Controllers/Admin/PricingPlanController.php)

**Methods**:

| Method | Route | Purpose |
|--------|-------|---------|
| `index()` | GET `/admin/pricing-plans` | List all pricing plans |
| `create()` | GET `/admin/pricing-plans/create` | Show create form |
| `store()` | POST `/admin/pricing-plans` | Save new plan |
| `show()` | GET `/admin/pricing-plans/{id}` | View plan details + tenants using it |
| `edit()` | GET `/admin/pricing-plans/{id}/edit` | Show edit form |
| `update()` | PUT `/admin/pricing-plans/{id}` | Update plan |
| `destroy()` | DELETE `/admin/pricing-plans/{id}` | Delete plan (if no tenants) |
| `toggleActive()` | POST `/admin/pricing-plans/{id}/toggle` | Activate/deactivate |

**Validation Rules** (store/update):
```php
[
    'name' => 'required|string|max:255',
    'slug' => 'nullable|string|max:255|unique:pricing_plans,slug',
    'description' => 'nullable|string',
    'price' => 'required|numeric|min:0',
    'billing_cycle' => 'required|in:monthly,quarterly,yearly',
    'trial_days' => 'required|integer|min:0',
    'features' => 'nullable|array',
    'features.*' => 'string',
    'max_users' => 'nullable|integer|min:1',
    'max_patients' => 'nullable|integer|min:1',
    'is_active' => 'boolean',
    'is_popular' => 'boolean',
    'badge_text' => 'nullable|string|max:50',
    'badge_color' => 'nullable|string|max:50',
    'sort_order' => 'required|integer|min:0',
]
```

**Key Logic**:

1. **Auto-generate slug**: If slug is empty, generate from name
   ```php
   if (empty($validated['slug'])) {
       $validated['slug'] = Str::slug($validated['name']);
   }
   ```

2. **Filter empty features**: Remove empty strings from features array
   ```php
   if (isset($validated['features'])) {
       $validated['features'] = array_filter($validated['features'], 
           fn ($feature) => !empty($feature)
       );
   }
   ```

3. **Handle checkboxes**: Convert checkbox presence to boolean
   ```php
   $validated['is_active'] = $request->has('is_active');
   $validated['is_popular'] = $request->has('is_popular');
   ```

4. **Protect deletion**: Check if tenants use the plan
   ```php
   if ($pricingPlan->tenants()->count() > 0) {
       return redirect()
           ->with('error', 'Cannot delete. Used by ' 
               . $pricingPlan->tenants()->count() . ' tenant(s).');
   }
   ```

5. **Toggle active status**:
   ```php
   public function toggleActive(PricingPlan $pricingPlan): RedirectResponse
   {
       $pricingPlan->update(['is_active' => !$pricingPlan->is_active]);
       $status = $pricingPlan->is_active ? 'activated' : 'deactivated';
       return redirect()->with('success', "Plan {$status}!");
   }
   ```

---

#### **2. TenantController** (Full CRUD + Custom Admin Actions)

**Location**: [app/Http/Controllers/Admin/TenantController.php](app/Http/Controllers/Admin/TenantController.php)

**Methods**:

| Method | Route | Purpose |
|--------|-------|---------|
| `index()` | GET `/admin/tenants` | List tenants (searchable, paginated) |
| `create()` | GET `/admin/tenants/create` | Show create form |
| `store()` | POST `/admin/tenants` | Create new tenant |
| `show()` | GET `/admin/tenants/{id}` | View tenant details + users |
| `edit()` | GET `/admin/tenants/{id}/edit` | Show edit form |
| `update()` | PUT `/admin/tenants/{id}` | Update tenant |
| `destroy()` | DELETE `/admin/tenants/{id}` | Force delete tenant |
| `toggleActive()` | POST `/admin/tenants/{id}/toggle` | Activate/deactivate |
| `markEmailVerified()` | POST `/admin/tenants/{id}/mark-verified` | Verify email (admin) |
| `resendVerificationEmail()` | POST `/admin/tenants/{id}/resend-email` | Resend verification |

**Validation Rules** (store/update):
```php
[
    'name' => 'required|string|max:255',
    'slug' => 'required|string|max:255|unique:tenants,slug',
    'email' => 'required|email|max:255',
    'phone' => 'nullable|string|max:20',
    'pricing_plan_id' => 'required|exists:pricing_plans,id',
    'address' => 'nullable|string',
    'city' => 'nullable|string|max:255',
    'state' => 'nullable|string|max:255',
    'zip_code' => 'nullable|string|max:10',
    'country' => 'nullable|string|max:255',
    'is_active' => 'boolean', // update only
]
```

**Key Features**:

1. **Search Functionality** (in index):
   ```php
   if ($request->filled('search')) {
       $search = $request->search;
       $query->where(function ($q) use ($search) {
           $q->where('name', 'like', "%{$search}%")
               ->orWhere('slug', 'like', "%{$search}%")
               ->orWhere('email', 'like', "%{$search}%");
       });
   }
   ```

2. **Pagination**: 15 tenants per page with query string preservation
   ```php
   $tenants = $query->latest()->paginate(15)->withQueryString();
   ```

3. **Eager Loading**:
   ```php
   $query = Tenant::with('pricingPlan');
   $tenant->load('pricingPlan', 'users');
   ```

4. **Email Verification (Admin)**:
   ```php
   public function markEmailVerified(Tenant $tenant): RedirectResponse
   {
       DB::beginTransaction();
       try {
           $tenant->update([
               'email_verified_at' => now(),
               'email_verification_token' => null,
           ]);
           // Also mark owner user's email as verified
           $owner = User::where('tenant_id', $tenant->id)
               ->where('email', $tenant->email)
               ->first();
           if ($owner) {
               $owner->update(['email_verified_at' => now()]);
           }
           DB::commit();
           return redirect()->with('success', 'Email verified!');
       } catch (\Exception $e) {
           DB::rollBack();
           return redirect()->with('error', 'Failed: ' . $e->getMessage());
       }
   }
   ```

5. **Resend Verification Email**:
   ```php
   public function resendVerificationEmail(Tenant $tenant): RedirectResponse
   {
       $verificationToken = Str::random(64);
       $tenant->update(['email_verification_token' => $verificationToken]);
       
       $owner = User::where('tenant_id', $tenant->id)
           ->where('email', $tenant->email)
           ->first();
       
       if ($owner) {
           $owner->notify(new TenantVerificationNotification($tenant, $verificationToken));
       }
       
       return redirect()->with('success', 'Email sent!');
   }
   ```

6. **Force Delete** (Cascade):
   ```php
   public function destroy(Tenant $tenant): RedirectResponse
   {
       // Permanently delete tenant and all related data
       // Cascade delete via foreign keys
       $tenant->forceDelete();
       return redirect()->with('success', 'Tenant deleted.');
   }
   ```

---

### Tenant Controllers

#### **3. UserController** (Tenant-level user management)

**Location**: [app/Http/Controllers/Tenant/UserController.php](app/Http/Controllers/Tenant/UserController.php)

**Routes**:
```php
Route::resource('users', UserController::class);
// GET  /tenant/{tenant}/users
// POST /tenant/{tenant}/users
// GET  /tenant/{tenant}/users/{user}
// PUT  /tenant/{tenant}/users/{user}
// DELETE /tenant/{tenant}/users/{user}
```

**Purpose**: Tenant admins manage users within their clinic

---

## 🔄 Data Flow Diagrams

### Pricing Plan CRUD Flow

```
Admin Dashboard
    ↓
/admin/pricing-plans (index)
    ├─ List all plans
    ├─ Show active status
    └─ Show tenant count
    
    ↓ [Create New]
    
/admin/pricing-plans/create
    ↓
[Form Submit with Validation]
    ├─ Validate all fields
    ├─ Auto-generate slug if empty
    ├─ Filter empty features
    └─ Convert checkbox values
    
    ↓ [Success]
    
Database:
    INSERT INTO pricing_plans (
        name, slug, description, price, 
        billing_cycle, trial_days, features,
        max_users, max_patients, is_active, 
        is_popular, badge_text, badge_color, 
        sort_order
    ) VALUES (...)
    
    ↓
/admin/pricing-plans (redirect with success message)
```

### Tenant CRUD Flow

```
Admin Dashboard
    ↓
/admin/tenants (index)
    ├─ Search by name/slug/email
    ├─ Pagination (15 per page)
    └─ Show subscription status
    
    ↓ [Edit Tenant]
    
/admin/tenants/{id}/edit
    ↓
[Form Submit with Validation]
    ├─ Validate tenant details
    ├─ Check slug uniqueness
    └─ Validate pricing plan exists
    
    ↓ [Success]
    
Database:
    UPDATE tenants SET (
        name, slug, email, phone,
        pricing_plan_id, address, city, etc.
    ) WHERE id = ?
    
    ↓
/admin/tenants/{id} (show updated details)

    ↓ [Custom Actions]
    
├─ toggleActive()
│   └─ Toggle is_active (soft access control)
│
├─ markEmailVerified()
│   └─ Set email_verified_at (transactional)
│       └─ Also update User::email_verified_at
│
└─ resendVerificationEmail()
    └─ Generate new token
    └─ Send email notification
```

---

## 📊 Key Implementation Details

### 1. Unique Constraints

**Pricing Plans**:
- `slug` - Unique (ensures single definition per plan)

**Tenants**:
- `slug` - Unique (subdomain identifier)
- `email` - Unique (clinic email)
- `phone` - Unique (clinic phone)

**Users**:
- `email` - Unique (login identifier)

### 2. Foreign Key Relationships

```
users
  └─ tenant_id FK → tenants (nullable for system admins)

tenants
  └─ pricing_plan_id FK → pricing_plans (nullable)

roles
  └─ tenant_id FK → tenants (nullable, for tenant-scoped roles)

tenant_services
  └─ tenant_id FK → tenants (CASCADE DELETE)

tenant_medicines
  └─ tenant_id FK → tenants (CASCADE DELETE)

tenant_templates
  └─ tenant_id FK → tenants (CASCADE DELETE)
```

### 3. Soft Deletes

Only **tenants** table uses soft deletes:
```php
// In Tenant model
use SoftDeletes;
```

This allows:
- Archive tenant data without permanent loss
- Restore if needed
- Includes `deleted_at` timestamp

**Database**: `deleted_at TIMESTAMP NULL`

### 4. JSON Columns

Used for flexible, schema-less data storage:

**tenants table**:
- `settings` - General settings
- `business_hours` - Operating hours
- `consent_forms` - Consent templates
- `certificate_templates` - Certificate configs
- `default_hmo_providers` - Default HMO list
- `default_dental_services` - Default services

**pricing_plans table**:
- `features` - Array of feature strings

**tenant_templates table**:
- `fields` - Dynamic field definitions

**Benefit**: Flexibility without schema changes

### 5. Indexing Strategy

```sql
-- tenant_services
INDEX (tenant_id, is_active)

-- tenant_medicines
INDEX (tenant_id, is_active)

-- tenant_templates
INDEX (tenant_id, type, is_active)

-- sessions
INDEX (user_id, last_activity)

-- cache
INDEX (expiration)

-- roles
PRIMARY KEY (id)  -- Spatie default
```

**Purpose**: Optimize queries for multi-tenant isolation

---

## 🧪 Form Handling & Validation

### Create/Edit Forms Pattern

**Validation Flow**:
```php
public function store(Request $request): RedirectResponse
{
    // 1. Validate input
    $validated = $request->validate([...]);
    
    // 2. Transform data
    if (empty($validated['slug'])) {
        $validated['slug'] = Str::slug($validated['name']);
    }
    
    // 3. Handle special fields
    if (isset($validated['features'])) {
        $validated['features'] = array_filter($validated['features']);
    }
    
    // 4. Handle checkboxes
    $validated['is_active'] = $request->has('is_active');
    
    // 5. Persist
    Model::create($validated);
    
    // 6. Redirect with message
    return redirect()->route('...')
        ->with('success', 'Created successfully!');
}
```

### Slug Auto-Generation

```php
// If slug provided, use it
// If slug empty, generate from name

if (empty($validated['slug'])) {
    $validated['slug'] = Str::slug($validated['name']);
    // "My Plan Name" → "my-plan-name"
}
```

### Feature Array Handling

```php
// Filter out empty feature strings
$validated['features'] = array_filter(
    $validated['features'], 
    fn ($feature) => !empty($feature)
);

// Result: Only non-empty features stored in JSON
// SELECT * WHERE features CONTAINS "Feature 1"
```

---

## 🔒 Safety & Integrity

### 1. Unique Slug Enforcement

```php
'slug' => 'required|string|max:255|unique:tenants,slug,'.$tenant->id
// Allows same slug during update, but unique across all records
```

### 2. Pricing Plan Protection

```php
public function destroy(PricingPlan $pricingPlan): RedirectResponse
{
    if ($pricingPlan->tenants()->count() > 0) {
        return redirect()
            ->with('error', 'Cannot delete. Used by tenants.');
    }
    $pricingPlan->delete();
}
```

**Rule**: Cannot delete pricing plans with active tenants

### 3. Transactional Email Verification

```php
DB::beginTransaction();
try {
    // Update tenant
    $tenant->update(['email_verified_at' => now(), ...]);
    
    // Update related user
    if ($owner) {
        $owner->update(['email_verified_at' => now()]);
    }
    
    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
    throw $e;
}
```

**Ensures**: Consistency between tenant and user email verification

### 4. Cascade Deletes

```sql
tenant_id BIGINT FK (onDelete CASCADE)
-- When tenant deleted, all related records deleted
```

**Affected tables**:
- tenant_services
- tenant_medicines
- tenant_templates

---

## 📈 Query Performance

### Index Strategy for Multi-Tenancy

**Master data queries**:
```php
// Fast: Uses slug index
$tenant = Tenant::where('slug', $subdomain)->first();

// Fast: Uses email index  
$tenant = Tenant::where('email', $email)->first();
```

**Tenant-scoped data**:
```php
// Fast: Uses (tenant_id, is_active) index
$services = TenantService::where('tenant_id', $tenantId)
    ->where('is_active', true)
    ->get();
```

**Template lookups**:
```php
// Fast: Uses (tenant_id, type, is_active) index
$consents = TenantTemplate::where('tenant_id', $tenantId)
    ->where('type', 'consent')
    ->where('is_active', true)
    ->get();
```

---

## 🚀 Scalability Considerations

### Database Design for Scale

1. **Vertical Partitioning**: Could partition by tenant_id
2. **Connection Pooling**: Essential for multi-tenant
3. **Query Optimization**: Indexes on foreign keys + filters
4. **Caching**: Database cache tables (configurable to Redis)

### Current Limitations

- Single database instance
- All tenants share tables (design feature, not limitation)
- Soft deletes increase dataset size
- JSON columns aren't fully indexed

### Growth Path

- Add Redis cache (replace database cache)
- Implement query result caching
- Add read replicas for reporting
- Partition by tenant_id if needed (future)

---

## ✅ CRUD Operations Summary

| Operation | Table | Method | Status |
|-----------|-------|--------|--------|
| Create Pricing Plan | pricing_plans | store() | ✅ Complete |
| Read Pricing Plans | pricing_plans | index(), show() | ✅ Complete |
| Update Pricing Plan | pricing_plans | update() | ✅ Complete |
| Delete Pricing Plan | pricing_plans | destroy() (guarded) | ✅ Complete |
| Toggle Plan Status | pricing_plans | toggleActive() | ✅ Complete |
| Create Tenant | tenants | store() | ✅ Complete |
| Read Tenants | tenants | index(), show() | ✅ Complete |
| Update Tenant | tenants | update() | ✅ Complete |
| Delete Tenant | tenants | destroy() (force) | ✅ Complete |
| Toggle Tenant Status | tenants | toggleActive() | ✅ Complete |
| Verify Tenant Email | tenants | markEmailVerified() | ✅ Complete |
| Resend Verification | tenants | resendVerificationEmail() | ✅ Complete |
| Create User (Tenant) | users | store() | ✅ In Controller |
| Read Users | users | index(), show() | ✅ In Controller |
| Update User | users | update() | ✅ In Controller |
| Delete User | users | destroy() | ✅ In Controller |

---

**Analysis Completed**: January 24, 2026
**Depth**: Complete schema analysis with CRUD implementation details
