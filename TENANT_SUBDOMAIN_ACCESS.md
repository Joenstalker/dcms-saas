# Tenant Subdomain Access Control

**Updated**: January 24, 2026

---

## 🎯 How It Works Now

### Landing Page (For Prospects)
```
http://dcmsapp.local:8000/
    ↓
Shows: Welcome page with "GET STARTED" button
Link: Register button
Accessible: Everyone (no login required)
```

### Tenant Login (For Existing Clinics)
```
http://dental.dcmsapp.local:8000/
    ↓
NOT logged in? → Redirect to login
Logged in? → Redirect to dashboard
```

---

## 🔄 Access Flow

### Scenario 1: Access Main Domain
```
User visits: http://dcmsapp.local:8000/
    ↓
Middleware skips (no tenant subdomain)
    ↓
Shows: Landing page
Status: Public - anyone can see
```

### Scenario 2: Access Tenant Subdomain Without Login
```
User visits: http://dental.dcmsapp.local:8000/
    ↓
Middleware identifies tenant: "dental"
    ↓
Is user authenticated? NO
    ↓
Redirect to: http://dcmsapp.local:8000/login
    ↓
User logs in
```

### Scenario 3: Access Tenant Subdomain With Login
```
User visits: http://dental.dcmsapp.local:8000/tenant/1/dashboard
    ↓
Middleware identifies tenant: "dental"
    ↓
Is user authenticated? YES
    ↓
Check subscription: Active?
    ├─ YES → Show dashboard
    └─ NO → Show suspension page
```

---

## ✅ Public Routes (Accessible on Tenant Subdomains)

These routes can be accessed without login, even on tenant subdomains:

```
/login              → Login form
/register           → Registration
/verify/email/*     → Email verification links
/subscription/suspended/*  → Suspension page
```

---

## 🚀 Testing

### Test 1: Landing Page
```
1. Go to: http://dcmsapp.local:8000/
2. See: Welcome page with "GET STARTED"
3. Click "GET STARTED" → Register
```

### Test 2: Tenant Subdomain (Not Logged In)
```
1. Go to: http://dental.dcmsapp.local:8000/
2. Expected: Redirect to http://dcmsapp.local:8000/login
3. See: Login form
```

### Test 3: Tenant Subdomain (Logged In)
```
1. Login at: http://dcmsapp.local:8000/login
2. Go to: http://dental.dcmsapp.local:8000/tenant/1/dashboard
3. See: Tenant dashboard (if subscription active)
```

### Test 4: New Tenant Registration
```
1. Go to: http://dcmsapp.local:8000/register
2. Register with subdomain: "newclinic"
3. Verify email
4. Login
5. Access: http://newclinic.dcmsapp.local:8000/
6. See: Dashboard (after login from login page)
```

---

## 📋 Changes Made

**File**: `app/Http/Middleware/TenantMiddleware.php`

**What Changed**:
1. Added check for unauthenticated access to tenant subdomains
2. Allow public routes (login, register, verify) on tenant subdomains
3. Redirect to login if accessing tenant subdomain without auth

**Key Logic**:
```php
// Allow public routes on tenant subdomains
if ($request->routeIs('login') || $request->routeIs('tenant.registration.*') 
    || $request->routeIs('tenant.verification.*')
    || $request->routeIs('tenant.subscription.suspended')) {
    return $next($request);
}

// If not authenticated and on tenant subdomain, redirect to login
if (!auth()->check()) {
    return redirect()->route('login');
}
```

---

## 📊 User Experience

### For Prospects
```
1. Visit dcmsapp.local
2. See landing page
3. Click "GET STARTED"
4. Register with subdomain
5. Verify email
6. Done - can now login
```

### For Existing Clinic Users
```
1. Visit: dental.dcmsapp.local
2. Redirected to: dcmsapp.local/login
3. Login with email + password
4. Redirected to: dental.dcmsapp.local/dashboard
5. Access clinic portal
```

---

## ✨ Benefits

✅ **Clear Separation**: Landing page vs tenant access  
✅ **Security**: Forces login on tenant subdomains  
✅ **User-Friendly**: Automatic redirects  
✅ **Public Routes Work**: Register/verify still accessible on subdomains  
✅ **Multi-Tenant Ready**: Each tenant has own login flow  

---

## ⚠️ Important Notes

### Login Redirect
- Accessing tenant subdomain without login redirects to **main domain login**
- After login, user can access their tenant subdomain
- This prevents empty landing page on subdomains

### Public Routes Still Work
- Can still access `/register` on any subdomain
- Can still access `/verify/email/...` on any subdomain  
- Can still access `/login` on any subdomain
- This allows email verification links to work from subdomains

### Subscription Status
- After login, subscription is checked
- If expired → redirect to suspension page
- If active → access dashboard

---

**Status**: ✅ Ready to test
