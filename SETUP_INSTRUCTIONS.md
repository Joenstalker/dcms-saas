# Setup Instructions - DCMS SaaS

## Quick Start Guide

Follow these steps to get your development environment up and running:

### Step 1: Complete Composer Installation
```bash
composer install --no-interaction
```

If you encounter any issues, try:
```bash
composer install --no-interaction --prefer-dist --no-dev
```

### Step 2: Complete NPM Installation
```bash
npm install
```

### Step 3: Environment Configuration
```bash
# Copy .env.example to .env if not exists
if (!(Test-Path .env)) { Copy-Item .env.example .env }

# Generate application key
php artisan key:generate
```

### Step 4: Database Setup
1. Create a MySQL database named `dcms_saas`
2. Update `.env` with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=dcms_saas
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

3. Run migrations:
   ```bash
   php artisan migrate
   ```

### Step 5: Storage Setup
```bash
# Create storage link
php artisan storage:link

# Ensure storage directories exist
php artisan storage:link
```

### Step 6: Build Assets

**For Development:**
```bash
npm run dev
```

**For Production:**
```bash
npm run build
```

### Step 7: Start Servers

**Terminal 1 - Laravel Server:**
```bash
php artisan serve
```

**Terminal 2 - Vite Dev Server (if using npm run dev):**
```bash
npm run dev
```

### Step 8: Access Application
Open your browser and navigate to:
- **Main Application**: http://localhost:8000
- **Vite HMR**: http://localhost:5173 (when running `npm run dev`)

## Troubleshooting

### Issue: Vendor directory missing
**Solution:** Run `composer install` again

### Issue: Node modules missing
**Solution:** Run `npm install` again

### Issue: Storage directories not writable
**Solution:** 
```bash
# Windows PowerShell
icacls storage /grant Users:F /T
icacls bootstrap/cache /grant Users:F /T
```

### Issue: APP_KEY not set
**Solution:** Run `php artisan key:generate`

### Issue: Database connection failed
**Solution:** 
1. Check database credentials in `.env`
2. Ensure MySQL/PostgreSQL is running
3. Verify database exists

### Issue: Vite assets not loading
**Solution:**
1. Ensure `npm run dev` is running
2. Check `vite.config.js` configuration
3. Clear browser cache

## Next Steps

After completing setup:
1. Review `PROJECT_PLAN.md` for project architecture
2. Check `README.md` for general information
3. Start building features according to the development phases

## Development Commands

```bash
# Run tests
php artisan test

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Code formatting (if Laravel Pint is configured)
./vendor/bin/pint
```

## Environment Variables

Key environment variables to configure:
- `APP_NAME` - Application name
- `APP_ENV` - Environment (local, staging, production)
- `APP_DEBUG` - Debug mode (true/false)
- `APP_URL` - Application URL
- `DB_*` - Database configuration
- `MAIL_*` - Email configuration

---

**Status**: Environment setup in progress
**Last Updated**: January 23, 2026
