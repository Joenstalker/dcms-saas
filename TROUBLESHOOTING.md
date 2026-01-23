# Troubleshooting Guide

## HTTP 500 Error - Fixed ✅

### Problem
The application was throwing HTTP 500 errors because:
1. `SESSION_DRIVER` was set to `database` but migrations hadn't been run
2. `CACHE_STORE` was set to `database` but cache table didn't exist
3. `QUEUE_CONNECTION` was set to `database` but queue tables didn't exist

### Solution Applied
Changed the following in `.env`:
- `SESSION_DRIVER=database` → `SESSION_DRIVER=file`
- `CACHE_STORE=database` → `CACHE_STORE=file`
- `QUEUE_CONNECTION=database` → `QUEUE_CONNECTION=sync`

### Why This Works
- File-based sessions don't require database tables
- File-based cache doesn't require database tables
- Sync queue driver processes jobs immediately without database

### To Use Database Later
Once you run migrations, you can switch back:
```bash
# After running: php artisan migrate
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

## Common Issues and Solutions

### Issue: "Table doesn't exist" errors
**Solution**: Run migrations first:
```bash
php artisan migrate
```

### Issue: Config cache issues
**Solution**: Clear config cache:
```bash
php artisan config:clear
```

### Issue: Views not updating
**Solution**: Clear view cache:
```bash
php artisan view:clear
```

### Issue: Routes not working
**Solution**: Clear route cache:
```bash
php artisan route:clear
```

### Issue: Storage link missing
**Solution**: Create storage link:
```bash
php artisan storage:link
```

### Issue: APP_KEY not set
**Solution**: Generate application key:
```bash
php artisan key:generate
```

## Quick Fix Commands

Run these commands if you encounter issues:

```bash
# Clear all caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Regenerate key (if needed)
php artisan key:generate

# Create storage link
php artisan storage:link
```

## Database Setup

Before using database sessions/cache/queues:

1. **Create Database**
   ```sql
   CREATE DATABASE dcms_saas;
   ```

2. **Update .env**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=dcms_saas
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

3. **Run Migrations**
   ```bash
   php artisan migrate
   ```

4. **Switch to Database Drivers** (optional)
   ```env
   SESSION_DRIVER=database
   CACHE_STORE=database
   QUEUE_CONNECTION=database
   ```

---

**Last Updated**: January 23, 2026
