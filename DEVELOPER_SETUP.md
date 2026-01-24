# Developer Setup Guide - For Team Members

**Date**: January 24, 2026  
**Project**: DCMS - Dental Clinic Management System (SaaS)

---

## 📋 Prerequisites

Before you start, make sure you have:

- **PHP** 8.2+ installed
- **Composer** installed
- **Node.js** 18+ and npm installed
- **MySQL** or **PostgreSQL** running
- **Git** installed
- **Administrator access** on Windows (for hosts file editing)

---

## 🚀 Quick Setup (5 Steps)

### Step 1: Clone the Repository

```bash
git clone <repository-url>
cd dcms-saas
```

### Step 2: Install Dependencies

```bash
# Install PHP packages
composer install

# Install JavaScript packages
npm install
```

### Step 3: Setup Environment

```bash
# Copy environment template
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Configure Database

Edit `.env` and set your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dcms_saas
DB_USERNAME=root
DB_PASSWORD=your_password
```

Then run migrations:

```bash
php artisan migrate
```

### Step 5: Configure Local Domain (Windows)

**Run PowerShell as Administrator** and do this once:

```powershell
# Edit hosts file
Start-Process notepad -Verb RunAs C:\Windows\System32\drivers\etc\hosts

# Add these lines at the bottom:
127.0.0.1       dcmsapp.local
127.0.0.1       admin.dcmsapp.local

# Save and close

# Flush DNS
ipconfig /flushdns
```

---

## ▶️ Running the Application

### Terminal 1: Start Laravel Server

```bash
cd d:\dentistmng\dcms-saas
php artisan serve --host=0.0.0.0 --port=8000
```

You should see:
```
Laravel development server started: http://0.0.0.0:8000
```

### Terminal 2: Start Asset Watcher (Optional)

```bash
cd d:\dentistmng\dcms-saas
npm run dev
```

This watches for CSS/JS changes and auto-compiles them.

---

## 🌐 Access the Application

Once servers are running:

| URL | Purpose |
|-----|---------|
| `http://dcmsapp.local:8000/` | Landing page |
| `http://dcmsapp.local:8000/register` | Register new clinic |
| `http://dcmsapp.local:8000/login` | Main login |
| `http://dcmsapp.local:8000/admin` | Admin panel |
| `http://[tenant].dcmsapp.local:8000/` | Tenant login (e.g., `dental.dcmsapp.local:8000`) |

---

## 📊 Database Setup (First Time)

### Option A: Fresh Database

```bash
# Create database in MySQL
CREATE DATABASE dcms_saas;

# Run migrations (creates tables)
php artisan migrate

# (Optional) Seed with test data
php artisan db:seed
```

### Option B: From Team Database Backup

```bash
# Import database from .sql file
mysql -u root -p dcms_saas < backup.sql

# Still run migrations to ensure schema is up-to-date
php artisan migrate
```

---

## 🔐 Default Admin Account

After first setup, ask a team lead for:
- Admin email
- Admin password

Or create one via command:

```bash
php artisan tinker

# Then run:
>>> App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@dcmsapp.com',
    'password' => Hash::make('password123'),
    'is_system_admin' => true,
]);
>>> exit
```

---

## 🧪 Test the Setup

### 1. Test Main Site
```
Go to: http://dcmsapp.local:8000/
Expected: Landing page with "GET STARTED" button
```

### 2. Test Registration
```
Go to: http://dcmsapp.local:8000/register
Expected: Registration form
```

### 3. Test Admin Panel
```
Go to: http://dcmsapp.local:8000/admin
Expected: Admin dashboard (if logged in as admin)
```

### 4. Test Tenant Login
```
1. Register new clinic with subdomain: "testclinic"
2. Verify email
3. Go to: http://testclinic.dcmsapp.local:8000/
4. Expected: Tenant login page with clinic name
5. Login with credentials
6. Expected: Clinic dashboard
```

---

## 📝 Key Project Files

```
dcms-saas/
├── .env                    ← Your configuration
├── app/                    ← Application code
│   ├── Http/Controllers/   ← Request handlers
│   ├── Models/             ← Database models
│   └── Services/           ← Business logic
├── database/
│   ├── migrations/         ← Database schema
│   └── seeders/            ← Test data
├── resources/
│   ├── views/              ← Blade templates
│   ├── css/                ← Stylesheets
│   └── js/                 ← JavaScript
├── routes/
│   └── web.php             ← URL routes
└── storage/                ← Logs, uploads
```

---

## 🆘 Common Issues

### Issue: "SQLSTATE[HY000]: General error..."

**Solution**: Database not connected

```bash
# Check .env database credentials
# Or create database first:
CREATE DATABASE dcms_saas;
```

### Issue: "RuntimeException: No application encryption key..."

**Solution**: Generate app key

```bash
php artisan key:generate
```

### Issue: Domain doesn't resolve (dddc.dcmsapp.local)

**Solution**: Add to hosts file

```powershell
# Run PowerShell as Admin
Start-Process notepad -Verb RunAs C:\Windows\System32\drivers\etc\hosts

# Add:
127.0.0.1    dddc.dcmsapp.local

# Save and flush DNS:
ipconfig /flushdns
```

### Issue: "The stream or file could not be opened in append mode..."

**Solution**: Create storage directories

```bash
mkdir storage/logs
mkdir storage/app/public
php artisan storage:link
```

### Issue: Permissions denied on Windows

**Solution**: Run PowerShell as Administrator

```powershell
Start-Process powershell -Verb RunAs
```

---

## 📦 Useful Commands

```bash
# View all routes
php artisan route:list

# Clear cache
php artisan cache:clear
php artisan config:clear

# Create new migration
php artisan make:migration create_table_name

# Create new controller
php artisan make:controller ControllerName

# Run tests
php artisan test

# Database refresh (WARNING: Deletes all data!)
php artisan migrate:refresh --seed
```

---

## 🔄 Git Workflow

### First time pulling code:
```bash
git clone <repo>
composer install
npm install
php artisan migrate
```

### Daily workflow:
```bash
# Pull latest changes
git pull origin main

# Install any new dependencies
composer install
npm install

# Run any new migrations
php artisan migrate

# Start development
php artisan serve --host=0.0.0.0 --port=8000
npm run dev
```

### Before committing:
```bash
# Check code quality
php artisan pint

# Run tests
php artisan test

# Then commit
git add .
git commit -m "Feature: description"
git push origin branch-name
```

---

## 📞 Need Help?

- Check `TROUBLESHOOTING.md` in the project root
- Check `README.md` for quick reference
- Ask team lead for database backup if schema issues
- Check Laravel docs: https://laravel.com

---

## ✅ Verification Checklist

After setup, confirm:

- [ ] All dependencies installed (`composer install`, `npm install`)
- [ ] `.env` configured with database credentials
- [ ] Database migrated (`php artisan migrate`)
- [ ] App key generated (`php artisan key:generate`)
- [ ] Hosts file updated (Windows)
- [ ] Laravel server running (`php artisan serve`)
- [ ] Can access `http://dcmsapp.local:8000/`
- [ ] Can register new clinic
- [ ] Can login to admin panel

---

**You're ready to develop!** 🚀
