# Separated Provider & Tenant Authentication

**Date**: January 24, 2026  
**Implementation**: Complete separation of provider and tenant login flows

---

## 🎯 Architecture

### Provider (Admin/SaaS Owner)
```
Route: http://dcmsapp.local:8000/admin/login
Controller: App\Http\Controllers\Admin\AuthController
View: admin/auth/login.blade.php
Middleware: auth, admin
Redirect: /admin/dashboard
```

### Tenant (Clinic)
```
Route: http://[clinic].dcmsapp.local:8000/login/{tenant}
Controller: App\Http\Controllers\Tenant\TenantLoginController
View: tenant/auth/login.blade.php
Middleware: tenant
Redirect: /tenant/{tenant}/dashboard
```

---

## 📝 Changes Made

### 1. **New Files Created**

#### `app/Http/Controllers/Admin/AuthController.php`
- **Purpose**: Handle provider authentication
- **Methods**:
  - `showLoginForm()` - Display admin login page
  - `login(Request $request)` - Authenticate provider
  - `logout(Request $request)` - Logout provider
- **Validation**: 
  - User must be `is_system_admin = true`
  - Email + password required
  - Redirects to `/admin/dashboard` on success

#### `resources/views/admin/auth/login.blade.php`
- **Purpose**: Provider-specific login UI
- **Features**:
  - Dark blue gradient background (professional SaaS look)
  - DCMS SaaS branding
  - "Provider Dashboard" messaging
  - Email + Password fields
  - Remember me checkbox
  - Link back to landing page
  - Messaging for clinics to register

#### `app/Http/Middleware/AdminMiddleware.php`
- **Purpose**: Protect admin routes
- **Logic**:
  - Checks if user is authenticated
  - Checks if user has `is_system_admin = true`
  - Aborts with 403 if not authorized
  - Redirects to `/admin/login` if not authenticated

### 2. **Modified Files**

#### `routes/web.php`
**Removed**:
- Old generic `/login` and `/login` POST routes

**Added**:
```php
// Admin Authentication routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
});
```

**Updated**:
- Admin routes group now uses `['auth', 'admin']` middleware
- Tenant routes unchanged (use TenantMiddleware)

#### `app/Http/Controllers/Auth/LoginController.php`
**Updated**:
- Removed admin login logic
- `showLoginForm()` now just displays deprecated warning
- `login()` method redirects to home with deprecation message
- Kept for backward compatibility

#### `bootstrap/app.php`
**Added**:
```php
$middleware->alias([
    'tenant' => \App\Http\Middleware\TenantMiddleware::class,
    'admin' => \App\Http\Middleware\AdminMiddleware::class,  // NEW
]);
```

---

## 🔐 Authentication Flow

### Provider Login
```
1. Visit: http://dcmsapp.local:8000/admin/login
2. See: Admin Login page (dark blue, SaaS branding)
3. Enter: Email + Password
4. System checks:
   ✓ User exists
   ✓ User is system_admin = true
   ✓ Password matches
5. Redirect: /admin/dashboard
6. User can now:
   ├─ Manage tenants (create, edit, delete, verify)
   ├─ Manage pricing plans
   └─ View all clinic activity
```

### Tenant Login
```
1. Visit: http://dental.dcmsapp.local:8000/
2. TenantMiddleware identifies: slug = "dental"
3. Not authenticated → Redirect to tenant.login
4. See: Clinic login page (clinic branding, "Welcome Back")
5. Enter: Email + Password
6. System checks:
   ✓ User belongs to clinic
   ✓ Email is verified
   ✓ Clinic has active subscription
7. Redirect: /tenant/{tenant}/dashboard
8. User can now:
   ├─ View clinic dashboard
   ├─ Manage patients
   ├─ Schedule appointments
   └─ Configure clinic settings
```

---

## 🔑 Key Differences

| Aspect | Provider | Tenant |
|--------|----------|--------|
| **URL** | dcmsapp.local:8000/admin/login | [clinic].dcmsapp.local:8000/ |
| **Branding** | DCMS SaaS (dark blue) | Clinic name (sky/emerald) |
| **User Type** | System admin | Clinic owner/staff |
| **Access Level** | All tenants, all data | Only own clinic data |
| **Middleware** | `auth`, `admin` | `tenant` (built-in) |
| **Verification** | None required | Email must be verified |
| **Subscription** | N/A | Must be active |

---

## 🧪 Testing

### Test Provider Login
```bash
1. Create system admin user (if not exists):
   php artisan tinker
   >>> User::create([
       'name' => 'Admin',
       'email' => 'admin@dcmsapp.com',
       'password' => Hash::make('password123'),
       'is_system_admin' => true
   ]);

2. Visit: http://dcmsapp.local:8000/admin/login
3. Enter: admin@dcmsapp.com / password123
4. Should see: Admin Dashboard
```

### Test Tenant Login
```bash
1. Register new clinic: http://dcmsapp.local:8000/register
   - Subdomain: testclinic
   - Verify email

2. Visit: http://testclinic.dcmsapp.local:8000/
3. Should see: Clinic login page (Welcome Back + Clinic Name)
4. Login with clinic credentials
5. Should see: Clinic Dashboard
```

### Test Access Control
```bash
1. Try accessing: http://dcmsapp.local:8000/admin/dashboard (not logged in)
   → Should redirect to /admin/login

2. Login as tenant user, try: http://dcmsapp.local:8000/admin/dashboard
   → Should get 403 Forbidden (not admin)

3. Logout from both, should redirect properly:
   - Admin logout → /admin/login
   - Tenant logout → /tenant/login or home
```

---

## 🚀 URLs Reference

### Provider Routes
| URL | Purpose |
|-----|---------|
| `/admin/login` | Provider login page |
| `/admin/logout` | Logout provider |
| `/admin/dashboard` | Provider dashboard |
| `/admin/tenants` | Manage tenants |
| `/admin/pricing-plans` | Manage pricing |

### Tenant Routes (on clinic subdomain)
| URL | Purpose |
|-----|---------|
| `/login/{tenant}` | Clinic login page |
| `/logout` | Logout tenant user |
| `/tenant/{tenant}/dashboard` | Clinic dashboard |
| `/tenant/{tenant}/patients` | Clinic patients |
| `/tenant/{tenant}/appointments` | Clinic appointments |
| `/register` | New clinic registration (main domain) |

### Public Routes
| URL | Purpose |
|-----|---------|
| `/` | Landing page |
| `/register` | Register new clinic |
| `/verify/*` | Email verification |

---

## ✅ Verification Checklist

After implementation:

- [x] Admin AuthController created with proper validation
- [x] Admin login view created with SaaS branding
- [x] AdminMiddleware created and registered
- [x] Routes updated with separate admin/tenant flows
- [x] Middleware aliases configured in bootstrap/app.php
- [x] Old LoginController logic removed
- [x] Admin routes protected with auth + admin middleware
- [x] Tenant routes use existing TenantMiddleware
- [x] Proper redirects on logout
- [x] User can't access admin dashboard without is_system_admin flag

---

## 🔄 Session Management

```php
// For Admin
Session::put('admin_id', auth()->user()->id);
// Can access: /admin/dashboard, /admin/tenants, /admin/pricing-plans

// For Tenant User
Session::put('tenant_id', $tenant->id);  // From TenantMiddleware
// Can access: /tenant/{tenant}/dashboard, /tenant/{tenant}/patients, etc.
```

---

## 📋 Next Steps

1. **Test the complete flow** (see Testing section above)
2. **Update landing page** to link to admin login for providers
3. **Add password reset** for both provider and tenant
4. **Implement 2FA** for admin accounts (recommended for SaaS)
5. **Add audit logging** for admin actions

---

**Status**: ✅ Complete and Ready for Testing
