# Quick Start - For Collaborators

**TL;DR - Just want to start coding? Follow this.**

---

## ⚡ 5-Minute Setup

```bash
# 1. Clone project
git clone <repo-url>
cd dcms-saas

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Configure database in .env, then:
php artisan migrate

# 5. Run server
php artisan serve --host=0.0.0.0 --port=8000
```

---

## 🖥️ Windows Only: Setup Hosts File

Run **PowerShell as Administrator** (one time):

```powershell
Start-Process notepad -Verb RunAs C:\Windows\System32\drivers\etc\hosts

# Add these lines:
127.0.0.1       dcmsapp.local
127.0.0.1       admin.dcmsapp.local

# Save and run:
ipconfig /flushdns
```

---

## 🌐 Access Application

```
Landing Page: http://dcmsapp.local:8000/
Register:     http://dcmsapp.local:8000/register
Admin:        http://dcmsapp.local:8000/admin
Tenant Login: http://[name].dcmsapp.local:8000/
```

---

## 📝 Daily Commands

```bash
# Start server
php artisan serve --host=0.0.0.0 --port=8000

# Watch CSS/JS changes (in another terminal)
npm run dev

# Run migrations
php artisan migrate

# Clear cache if things act weird
php artisan cache:clear
php artisan config:clear
```

---

## 🆘 If Something Breaks

```bash
# Fresh database (WARNING: Deletes data!)
php artisan migrate:refresh --seed

# Check all routes
php artisan route:list

# Run tests
php artisan test
```

---

## 📖 Full Setup Guide

See `DEVELOPER_SETUP.md` for complete instructions.

---

**Done!** Start coding 🚀
