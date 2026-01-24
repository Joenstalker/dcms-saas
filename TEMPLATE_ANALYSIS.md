# DCMS Template Architecture Analysis

This document analyzes the Blade templates that form the foundation of the DCMS (Dental Clinic Management System) and how they support system development.

---

## 1. Layout Hierarchy (Foundation)

The system uses **three main layouts** that everything else extends or includes:

| Layout | Path | Purpose | Used By |
|--------|------|---------|---------|
| **app** | `layouts/app.blade.php` | **Base layout** — public/marketing pages, auth, registration, onboarding | Welcome, Login, Registration, Setup Wizard, Subscription, Verification |
| **admin** | `layouts/admin.blade.php` | **Super-admin layout** — platform management (tenants, users) | Admin Dashboard, Tenant CRUD |
| **tenant** | `layouts/tenant.blade.php` | **Clinic portal layout** — authenticated tenant users with sidebar | Tenant Dashboard, Patients, Appointments, Services, Masterfile, Expenses, Settings |

**Development impact:** When building a new page, you choose one layout based on audience (public vs admin vs clinic). This keeps structure consistent and avoids duplication.

---

## 2. Layout Structure & What Each Provides

### `layouts/app.blade.php` (Base)
- **Includes:** `components.navigation`, `components.footer`
- **Yields:** `@yield('content')`
- **Variables:** `$title` (optional)
- **Stack:** Vite (CSS + JS), CSRF, Figtree font, `data-theme="dcms"`
- **Use for:** Unauthenticated or pre-login flows (landing, register, login, verify, setup, select plan)

### `layouts/admin.blade.php`
- **Includes:** `admin.components.sidebar` (desktop + mobile), `admin.components.navbar`
- **Yields:** `@yield('content')`
- **Built-in:** Success/error flash alerts (dismissible)
- **Layout:** Fixed sidebar (lg: 64), main content area, mobile overlay + drawer
- **Use for:** Super-admin only (tenant management, platform dashboard)

### `layouts/tenant.blade.php`
- **Includes:** `tenant.components.navbar`, `tenant.components.sidebar`
- **Expects:** `$tenant` (from route or `auth()->user()->tenant`)
- **Yields:** `@yield('content')`
- **Built-in:** Success/error flash alerts
- **Layout:** DaisyUI drawer (sidebar + main), responsive
- **Fallback:** “No Tenant Found” message with link home if `$tenant` is missing
- **Use for:** All authenticated clinic portal pages (dashboard, modules, settings)

**Development impact:** Flash messages and nav/sidebar are centralized. New tenant pages only need to `@extends('layouts.tenant', ['tenant' => $tenant])` and fill `@section('content')`.

---

## 3. Reusable Components

### Global (used by `app` layout)
| Component | Path | Role |
|-----------|------|------|
| **navigation** | `components/navigation.blade.php` | Top navbar: DCMS logo, Home, Features, Pricing, Docs, Register, Login |
| **footer** | `components/footer.blade.php` | Footer: branding, Terms, Privacy |

### Admin-specific
| Component | Path | Role |
|-----------|------|------|
| **navbar** | `admin/components/navbar.blade.php` | Admin top bar (likely user menu, etc.) |
| **sidebar** | `admin/components/sidebar.blade.php` | Admin nav (Dashboard, Tenants, etc.) |

### Tenant-specific
| Component | Path | Role |
|-----------|------|------|
| **navbar** | `tenant/components/navbar.blade.php` | Clinic top bar (tenant branding, user menu) |
| **sidebar** | `tenant/components/sidebar.blade.php` | Clinic nav: Home, Patients, Appointments, Services, Masterfile, Expenses, Admin Settings, Subscription (owner) |

**Development impact:** Nav and sidebar live in one place per context. Adding a new tenant module = new route + new view + one new link in `tenant/components/sidebar.blade.php`.

---

## 4. Page Templates by Area

### Public / Auth (`layouts.app`)
- `welcome.blade.php` — Landing, “Get Started” → registration
- `auth/login.blade.php` — Login form, link to register
- `tenant/registration/index.blade.php` — Full registration form
- `tenant/registration/success.blade.php` — Post-registration success
- `tenant/verification/success.blade.php`, `tenant/verification/failed.blade.php` — Email verification result
- `tenant/setup/wizard.blade.php`, `tenant/setup/success.blade.php` — Setup flow
- `tenant/subscription/select-plan.blade.php`, `tenant/subscription/payment.blade.php`, `tenant/subscription/success.blade.php` — Plan selection & payment

**Pattern:** Centered card-style forms with gradient background, `card bg-base-100 shadow-2xl`, alerts for errors/success.

### Admin (`layouts.admin`)
- `admin/dashboard.blade.php` — Admin dashboard
- `admin/tenants/index.blade.php` — Tenant list
- `admin/tenants/create.blade.php`, `admin/tenants/edit.blade.php` — Tenant create/edit
- `admin/tenants/show.blade.php` — Tenant detail

**Pattern:** Table/card-based UIs, CRUD actions, reuse of admin sidebar/navbar.

### Tenant Portal (`layouts.tenant`)
- `tenant/dashboard/index.blade.php` — Main dashboard: welcome, stats cards, quick actions, plan alert
- **Module placeholders:** `tenant/patients/index`, `appointments`, `services`, `masterfile`, `expenses`, `settings/index`

**Pattern:** `tenant/dashboard`: grid of stat cards, quick action buttons, optional “select plan” alert. Module pages currently simple “Coming soon” cards; same layout can be extended for real CRUD.

**Development impact:** Clear separation between marketing/auth, admin, and clinic UX. New clinic features = new or updated views under `tenant/*` using `layouts.tenant`.

---

## 5. Design System & Stack

- **CSS:** Tailwind + **DaisyUI**
- **Theme:** Custom `dcms` theme in `tailwind.config.js` (primary, secondary, accent, etc.)
- **Font:** Figtree (Bunny Fonts)
- **Build:** Vite (`resources/css/app.css`, `resources/js/app.js`)

**Development impact:** Use DaisyUI components (`btn`, `card`, `alert`, `form-control`, `drawer`, `navbar`, etc.) and `dcms` theme tokens so new pages match existing look and feel without custom CSS.

---

## 6. Template Conventions That Help Development

1. **Layout choice by role:** Public → `app`; Admin → `admin`; Clinic → `tenant`.
2. **Consistent `@section('content')`:** All full-page views yield `content`; no custom section names.
3. **Flash messages in layout:** Success/error handled in `admin` and `tenant` layouts; pages just `session('success')` / `session('error')`.
4. **Tenant in scope:** Tenant layout receives `$tenant`; sidebar/navbar use it for branding and links.
5. **Responsive:** Admin and tenant use drawer/sidebar patterns for mobile.
6. **Alerts:** Shared alert markup (e.g. `alert alert-error`, `alert alert-success`) across registration, login, and portal.

---

## 7. Summary: Templates That Help Development First

| Priority | Template / Layer | Why It Helps |
|----------|------------------|--------------|
| 1 | **Layouts (app, admin, tenant)** | Define structure, nav, and flash handling so new pages only add content. |
| 2 | **Tenant layout + tenant sidebar** | Single place to add clinic modules; dashboard and list/detail pages follow same shell. |
| 3 | **DaisyUI + dcms theme** | Consistent components and colors; faster UI work. |
| 4 | **Shared components (navigation, footer, sidebars, navbars)** | One update reflects everywhere. |
| 5 | **Form/flow templates (registration, login, setup, subscription)** | Reusable patterns for validation, errors, and success states. |
| 6 | **Module placeholder views (patients, appointments, etc.)** | Clear targets to replace with real features without changing layout. |

---

## 8. Recommended Order When Extending

1. **New tenant module (e.g. Invoices):**
   - Add route under `tenant/{tenant}/...`.
   - Add sidebar link in `tenant/components/sidebar.blade.php`.
   - Create `tenant/invoices/index.blade.php` (and later `show`, `create`, etc.) extending `layouts.tenant`.
   - Reuse patterns from `tenant/dashboard` (cards, grids) and `tenant/patients` (placeholder → real content).

2. **New public page (e.g. Pricing):**
   - Create view extending `layouts.app`.
   - Add route; link from `components/navigation`.

3. **New admin feature:**
   - Create view extending `layouts.admin`.
   - Add route under `admin/` and link in `admin/components/sidebar` if needed.

Keeping this layout → components → pages order will keep the system consistent and easier to maintain.
