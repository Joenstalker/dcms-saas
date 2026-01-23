# DCMS - Dental Clinic Management System (SaaS Multi-Tenant)

A comprehensive Dental Clinic Management System built with Laravel 11, Blade, Tailwind CSS, and DaisyUI.

## 🚀 Technology Stack

- **Backend**: Laravel 11, PHP 8.2+
- **Frontend**: Blade Templates, Tailwind CSS, DaisyUI, Alpine.js
- **Database**: MySQL/PostgreSQL
- **Build Tool**: Vite
- **Authentication**: Laravel Sanctum
- **Authorization**: Spatie Laravel Permission

## 📋 Prerequisites

- PHP >= 8.2
- Composer
- Node.js >= 18.x and npm
- MySQL/PostgreSQL
- Git

## 🛠️ Installation

### 1. Clone the Repository
```bash
git clone <repository-url>
cd dcms-saas
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Install Node Dependencies
```bash
npm install
```

### 4. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 5. Configure Database
Edit `.env` file and set your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dcms_saas
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 6. Run Migrations
```bash
php artisan migrate
```

### 7. Create Storage Link
```bash
php artisan storage:link
```

### 8. Build Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### 9. Start Development Server
```bash
# Terminal 1: Laravel Server
php artisan serve

# Terminal 2: Vite Dev Server (if using npm run dev)
npm run dev
```

Visit `http://localhost:8000` in your browser.

## 📁 Project Structure

```
dcms-saas/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Application controllers
│   │   ├── Middleware/       # Custom middleware
│   │   └── Requests/         # Form request validation
│   ├── Models/               # Eloquent models
│   ├── Services/             # Business logic services
│   └── Traits/               # Reusable traits
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── resources/
│   ├── views/                # Blade templates
│   ├── css/                  # CSS files
│   └── js/                   # JavaScript files
├── routes/
│   ├── web.php               # Web routes
│   └── console.php           # Artisan commands
└── public/                   # Public assets
```

## 🎨 UI Components

This project uses **DaisyUI** for UI components. Available components include:
- Buttons, Cards, Forms
- Modals, Dropdowns, Navigation
- Tables, Badges, Alerts
- And many more...

See [DaisyUI Documentation](https://daisyui.com/) for full component list.

## 🔐 Multi-Tenancy

The application uses subdomain-based multi-tenancy:
- Each clinic gets a unique subdomain (e.g., `clinic1.dcmsapp.com`)
- Tenant isolation is enforced at the middleware level
- Shared database with `tenant_id` column for data separation

## 📝 Development Workflow

1. **Create a feature branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. **Make your changes**

3. **Run tests**
   ```bash
   php artisan test
   ```

4. **Build assets**
   ```bash
   npm run build
   ```

5. **Commit and push**
   ```bash
   git add .
   git commit -m "Add your feature"
   git push origin feature/your-feature-name
   ```

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter TestName
```

## 📦 Key Packages

- `laravel/framework` - Laravel core
- `laravel/sanctum` - API authentication
- `spatie/laravel-permission` - Role & permission management
- `daisyui` - Tailwind CSS component library
- `alpinejs` - Lightweight JavaScript framework

## 🔧 Configuration

### Tailwind CSS & DaisyUI
Configuration files:
- `tailwind.config.js` - Tailwind and DaisyUI configuration
- `postcss.config.js` - PostCSS configuration
- `vite.config.js` - Vite build configuration

### Custom DCMS Theme
The project includes a custom DaisyUI theme named "dcms" with:
- Primary: Sky Blue (#0ea5e9)
- Secondary: Emerald Green (#10b981)
- Accent: Orange (#f97316)

## 📚 Documentation

- [Laravel Documentation](https://laravel.com/docs)
- [Blade Templates](https://laravel.com/docs/blade)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [DaisyUI Components](https://daisyui.com/components/)
- [Alpine.js](https://alpinejs.dev/)

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## 📄 License

This project is licensed under the MIT License.

## 🆘 Support

For support, email support@dcmsapp.com or create an issue in the repository.

---

**Made by Filipino Dentist, for every Filipino Dentist.** 🇵🇭
