# Quick Reference: New Registration Flow

## 🎯 The Flow in Simple Terms

**Old Way**:
```
Register → Email Verify → Auto-login → Dashboard
(Still need to manually add domain to hosts file)
```

**New Way**:
```
Register (domain auto-generated)
  → Auto-add to hosts file ✅
  → Email Verify 
  → Redirect to LOGIN (must login manually)
  → Dashboard access
```

---

## 🔧 Setup Requirements

```bash
# 1. Run Laravel as Administrator
Start-Process powershell -Verb RunAs
cd d:\dentistmng\dcms-saas
php artisan serve --host=0.0.0.0 --port=8000

# 2. Make sure you have these hosts file entries
# (Can be auto-added if running as Admin)
127.0.0.1   dcmsapp.local
127.0.0.1   admin.dcmsapp.local

# 3. Configure .env (already done)
LOCAL_BASE_DOMAIN=dcmsapp.local
```

---

## 📝 Test Registration

**Step 1**: Go to registration
```
http://dcmsapp.local:8000/register
```

**Step 2**: Fill form
```
Clinic Name: "Test Clinic"
Subdomain: "testclinic"  (no spaces, lowercase)
Owner Name: "John Doe"
Email: "john@test.com"
Password: "secure123"
```

**Step 3**: Submit
- System creates tenant with:
  - slug = "testclinic"
  - domain = "testclinic.dcmsapp.local"
- Auto-adds to hosts file (if running as Admin)
- Sends verification email

**Step 4**: Verify email
- Click link in email: `/verify/email/{token}/{email}`
- Redirected to login page
- See message: "Email verified! Please login"

**Step 5**: Login
```
http://dcmsapp.local:8000/login

Email: john@test.com
Password: secure123

Redirects to: http://testclinic.dcmsapp.local:8000/tenant/1/dashboard
```

---

## 💾 Database Check

```sql
-- Check tenant was created with domain
SELECT id, name, slug, domain, is_active, email_verified_at 
FROM tenants 
WHERE slug = 'testclinic';

-- Expected:
-- id: 1
-- name: Test Clinic
-- slug: testclinic
-- domain: testclinic.dcmsapp.local
-- is_active: 0 (before verification), 1 (after)
-- email_verified_at: NULL (before), timestamp (after)
```

---

## 🖥️ Hosts File Check

**If auto-add worked**:
```powershell
# Check if entry exists
Select-String "testclinic" C:\Windows\System32\drivers\etc\hosts

# Should show:
# 127.0.0.1       testclinic.dcmsapp.local
```

**If auto-add failed**:
```powershell
# Manual add:
"127.0.0.1       testclinic.dcmsapp.local" | 
  Add-Content C:\Windows\System32\drivers\etc\hosts -Force

# Flush DNS:
ipconfig /flushdns
```

---

## 📧 Email Verification

**If using real email**:
- Email sent automatically
- User clicks link → Activates tenant

**If email not configured**:
- Manual verification via admin:
  ```
  /admin/tenants/{tenant_id}/mark-verified
  ```

---

## 🚨 Troubleshooting

### Problem: Hosts file not auto-added

**Cause**: Not running as Administrator

**Solution**: 
```powershell
# Run PowerShell as Admin
Start-Process powershell -Verb RunAs

# Then run Laravel server
php artisan serve --host=0.0.0.0 --port=8000
```

### Problem: Domain not resolving

**Cause**: Hosts file entry missing

**Solution**:
```powershell
# Add manually to hosts file
# Run Notepad as Admin
Start-Process notepad -Verb RunAs C:\Windows\System32\drivers\etc\hosts

# Add line at bottom:
# 127.0.0.1       myclinic.dcmsapp.local

# Save and flush DNS:
ipconfig /flushdns
```

### Problem: Cannot login after verification

**Check**:
1. Is email verified? `SELECT email_verified_at FROM tenants WHERE slug='myclinic'`
2. Is tenant active? `SELECT is_active FROM tenants WHERE slug='myclinic'`
3. Is user email verified? `SELECT email_verified_at FROM users WHERE email='user@email.com'`

**Solution**: Use admin verification
```
GET /admin/tenants/{tenant_id}/mark-verified
```

### Problem: Email not sending

**Configure in .env**:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@dcmsapp.com
```

---

## 📊 Key Changes at a Glance

| Aspect | Before | After |
|--------|--------|-------|
| Domain field | Manual entry | Auto-generated |
| Hosts file | Manual addition | Auto-added (Windows) |
| After verification | Auto-login to dashboard | Redirect to login |
| Tenant access | Immediate after verify | Must login first |
| Subdomain format | User defined | `{subdomain}.dcmsapp.local` |

---

## ✅ Verification Checklist

After registration:
- [ ] Tenant created in database
- [ ] Tenant slug is lowercase subdomain
- [ ] Tenant domain is `{slug}.dcmsapp.local`
- [ ] Tenant is_active = false initially
- [ ] Verification email sent (if configured)
- [ ] Hosts file entry added (if Admin)

After email verification:
- [ ] Tenant is_active = true
- [ ] Tenant email_verified_at = timestamp
- [ ] User email_verified_at = timestamp
- [ ] User redirected to login page
- [ ] Can login with email + password

After login:
- [ ] Session created
- [ ] Redirected to tenant dashboard
- [ ] URL shows tenant subdomain: `{subdomain}.dcmsapp.local:8000`
- [ ] Dashboard accessible

---

**Quick Start**: Run as Admin, register, verify email, login, access dashboard! ✅
