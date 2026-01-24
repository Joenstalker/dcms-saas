# Automatic Tenant Domain Registration Flow

**Date**: January 24, 2026  
**Purpose**: Auto-generate domain during registration and auto-add to hosts file

---

## 🎯 New Registration Flow

### When a New Tenant Registers

```
User visits: http://dcmsapp.local:8000/register
    ↓
[Fills registration form]
├─ Clinic Name: "My Dental Clinic"
├─ Subdomain: "myclinic"
├─ Owner Name & Email
└─ Password & phone (optional)
    
[Submit POST /register]
    ↓
[Backend Processing]
├─ Validate all inputs
├─ Auto-generate domain: "myclinic.dcmsapp.local"
├─ Create Tenant record with slug="myclinic" & domain="myclinic.dcmsapp.local"
├─ Create User (clinic owner) for tenant
├─ Send verification email
└─ Auto-add to hosts file (Windows only, if running as Admin)
    
[Success]
    ↓
Redirect to: /register/success/{tenant}
    ↓
User sees message: "Check your email to verify your account"
    ↓
User clicks email verification link
    ↓
[Email Verification Process]
├─ Verify token & email
├─ Mark tenant as active (is_active = true)
├─ Mark user email as verified
└─ Redirect to: /verify/success/{tenant}
    
[Final Step]
    ↓
User redirected to LOGIN PAGE (NOT dashboard)
    ↓
Message: "Email verified! Please login to access your dashboard."
    ↓
User logs in with their credentials
    ↓
After login → Dashboard access
```

---

## 💾 Database Changes

### What Gets Stored During Registration

**Tenant Table**:
```sql
INSERT INTO tenants (
    name,           -- "My Dental Clinic"
    slug,           -- "myclinic"
    domain,         -- "myclinic.dcmsapp.local" (AUTO-GENERATED)
    email,          -- clinic email
    is_active,      -- false (until email verified)
    subscription_status,  -- "trial" (default)
    email_verification_token,  -- verification token
    email_verified_at,     -- NULL (until verified)
    created_at, updated_at
) VALUES (...)
```

**User Table**:
```sql
INSERT INTO users (
    name,           -- "Owner Name"
    email,          -- clinic email
    password,       -- hashed password
    tenant_id,      -- FK to newly created tenant
    is_system_admin,  -- false
    email_verified_at,  -- NULL (until verified)
    created_at, updated_at
) VALUES (...)
```

---

## 🖥️ Auto-Add to Hosts File (Windows)

### How It Works

1. **On Registration** (if running as Admin):
   ```php
   // In RegistrationController.php
   $this->addTenantToHostsFile($normalizedSubdomain);
   ```

2. **Method**:
   - Checks if running on Windows (`PHP_OS_FAMILY === 'Windows'`)
   - Reads hosts file from: `C:\Windows\System32\drivers\etc\hosts`
   - Checks if domain already exists
   - Appends new entry: `127.0.0.1    myclinic.dcmsapp.local`
   - Flushes DNS cache: `ipconfig /flushdns`
   - Logs success/failure

3. **Requirements**:
   - **Windows OS only** (skips on Linux/Mac)
   - **Laravel running as Administrator**
   - **Hosts file must be writable**

### Example Entry Added

```
127.0.0.1       myclinic.dcmsapp.local
```

### If Auto-Add Fails

- Not critical - doesn't fail registration
- User must manually add to hosts file
- Logged in application logs

---

## 🔧 Configuration

### Environment Variable

**File**: `.env`

```env
# Local Development Domain Configuration
LOCAL_BASE_DOMAIN=dcmsapp.local
```

**Used By**:
```php
$baseDomain = env('LOCAL_BASE_DOMAIN', 'dcmsapp.local');
$generatedDomain = "{$subdomain}.{$baseDomain}";
// Result: "myclinic.dcmsapp.local"
```

---

## 📧 Email Verification Flow

### What Happens After Registration

1. **Verification Email Sent**:
   ```
   Subject: "Verify Your Clinic Account"
   
   Body:
   "Click here to verify your email:
    http://dcmsapp.local:8000/verify/email/{token}/{email}
   ```

2. **User Clicks Link**:
   - Validates token matches tenant's email verification token
   - Updates `email_verified_at` timestamp
   - Clears `email_verification_token` (can't reuse)
   - Sets `is_active = true` (tenant is now active)
   - Updates user's `email_verified_at` as well

3. **After Verification**:
   - NOT auto-logged in (user must login)
   - Redirected to: `/verify/success/{tenant}`
   - Shows message: "Email verified! Please login"
   - User goes to login page
   - Logs in with email + password

---

## 🔐 Login Flow (After Verification)

```
User goes to: http://dcmsapp.local:8000/login
    ↓
[Login Form]
├─ Email: (their registered email)
└─ Password: (their password)
    
[Submit POST /login]
    ↓
[Authentication]
├─ Verify email exists in users table
├─ Verify password matches (bcrypt)
└─ Create session
    
[Success]
    ↓
Redirect to: /tenant/{tenant}/dashboard
    ↓
Display: Tenant Dashboard
```

---

## 🎯 Key Features

### ✅ Auto-Generated Domain
- No manual domain field entry needed
- Follows pattern: `{subdomain}.dcmsapp.local`
- Consistent and predictable

### ✅ Auto-Add to Hosts File
- On Windows, automatically adds entry
- Flushes DNS cache for immediate resolution
- Requires admin privileges (handled gracefully)

### ✅ Email Verification Required
- Ensures clinic email is valid
- Tenant cannot access dashboard until verified
- Owner email is also verified

### ✅ No Auto-Login
- User must explicitly login
- Prevents accidental access
- More secure
- User control over when they first access

### ✅ Non-Blocking Operations
- Hosts file auto-add doesn't block registration
- Email sending doesn't block registration
- If either fails, registration still succeeds
- Errors logged for admin review

---

## 📝 Code Changes Summary

### RegistrationController.php

**Added**:
1. Auto-generate domain from subdomain
2. Auto-add tenant domain to hosts file (Windows)
3. Call `addTenantToHostsFile()` after creation

**Method Added**:
```php
private function addTenantToHostsFile(string $subdomain): void
{
    // Handles Windows hosts file updates
    // Checks permissions, prevents duplicates
    // Flushes DNS cache
    // Non-blocking, comprehensive error handling
}
```

### VerificationController.php

**Modified**:
1. Changed: Don't auto-login user after verification
2. Changed: Redirect to verification success page (not dashboard)
3. Changed: Display login prompt instead of dashboard access

---

## 🚀 Testing the Flow

### Step 1: Run as Administrator

```powershell
# PowerShell as Admin
cd d:\dentistmng\dcms-saas
php artisan serve --host=0.0.0.0 --port=8000
```

### Step 2: Register New Tenant

```
1. Go to: http://dcmsapp.local:8000/register
2. Fill form:
   - Clinic Name: "Test Clinic"
   - Subdomain: "testclinic"
   - Owner Name: "John Doe"
   - Email: "john@test.com"
   - Password: (secure password)
3. Submit
```

### Step 3: Verify in Database

**Check Tenant was Created**:
```sql
SELECT id, name, slug, domain, is_active, email_verified_at 
FROM tenants 
WHERE slug = 'testclinic';
```

**Expected Result**:
```
id: 4
name: "Test Clinic"
slug: "testclinic"
domain: "testclinic.dcmsapp.local"
is_active: 0 (false)
email_verified_at: NULL
```

### Step 4: Check Hosts File Auto-Add

**If running as Admin**:
```powershell
Select-String "testclinic" C:\Windows\System32\drivers\etc\hosts
```

**Should show**:
```
127.0.0.1       testclinic.dcmsapp.local
```

### Step 5: Verify Email

1. Check email (or logs) for verification link
2. Click link
3. Verify email in database:
   ```sql
   SELECT is_active, email_verified_at FROM tenants WHERE id = 4;
   ```
   Should show: `is_active = 1, email_verified_at = (timestamp)`

### Step 6: Login and Access

1. Go to: `http://dcmsapp.local:8000/login`
2. Login with registered email + password
3. Redirected to: `http://testclinic.dcmsapp.local:8000/tenant/4/dashboard`
4. See tenant dashboard

---

## ⚠️ Important Notes

### Hosts File Permissions

- **Admin Access Required**: Writing to hosts file requires administrator privileges
- **Not Running as Admin**: Auto-add will fail silently (logged as warning)
- **Manual Addition**: Users can manually add if auto-add fails
  ```
  127.0.0.1    mytenantname.dcmsapp.local
  ```

### Windows Only

- Auto-add hosts file only works on Windows
- On Linux/Mac, skipped (logged as info)
- Middleware still works fine without hosts file entry if domain resolved differently

### Email Configuration

- Email sending must be configured in `.env`
- Without proper email config, verification emails won't send
- Fallback: Manual verification by admin via `/admin/tenants/{id}/mark-verified`

---

## 🔄 Complete Example

### User Registration Journey

```
1. Visit: http://dcmsapp.local:8000/register

2. Fill Form:
   - Clinic: "Smile Dental Care"
   - Subdomain: "smileclinic"
   - Owner: "Dr. Smith"
   - Email: "dr.smith@smile.com"
   - Password: "secure123"

3. System Creates:
   - Tenant: slug="smileclinic", domain="smileclinic.dcmsapp.local"
   - User: associated with tenant
   - Adds to hosts file: 127.0.0.1 smileclinic.dcmsapp.local
   - Sends verification email

4. User Receives Email:
   Subject: "Verify Your Clinic Account"
   Link: http://dcmsapp.local:8000/verify/email/TOKEN/dr.smith@smile.com

5. User Clicks Link:
   - Email verified
   - Tenant activated
   - Redirected to success page
   - Sees: "Please login to access your dashboard"

6. User Goes to Login:
   URL: http://dcmsapp.local:8000/login
   Email: dr.smith@smile.com
   Password: secure123

7. System Validates & Logs In:
   - Creates session
   - Redirects to tenant middleware
   - Tenant identified from domain: smileclinic.dcmsapp.local
   - User redirected to: http://smileclinic.dcmsapp.local:8000/tenant/1/dashboard

8. User Sees:
   - Welcome message with tenant name
   - Subscription status
   - Setup wizard prompt (if setup not completed)
   - Dashboard modules
```

---

## 📋 Checklist for Implementation

- [x] Modify `RegistrationController` to auto-generate domain
- [x] Add method to auto-add to hosts file
- [x] Call hosts file method after tenant creation
- [x] Modify `VerificationController` to redirect to login instead of dashboard
- [x] Add `LOCAL_BASE_DOMAIN` to `.env`
- [x] Test registration flow
- [x] Test email verification
- [x] Test login and dashboard access

---

**Implementation Complete** ✅
