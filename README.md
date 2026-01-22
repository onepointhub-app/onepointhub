# OnePointHub

**OnePointHub** is an open-source, modular business suite built with **Laravel** and **Livewire**, designed for **agencies and small teams** that want a single, extensible platform to manage clients, operations, and collaboration.

Instead of juggling multiple SaaS tools, OnePointHub brings everything together — CRM, roles and permissions, activity tracking, and more — with a clean architecture that developers *love* to extend.

---

## ✨ Key Features

### 🧩 Modular Architecture

* First-class **custom module system**
* Enable / disable modules at runtime
* Each module owns:

    * Database schema
    * Permissions
    * Navigation
    * Seeders
    * UI

### 👥 Team-Based by Design

* Multi-team / agency ready
* Role-based access control
* Permission-aware navigation
* Activity tracking across modules

### 📇 CRM Module (included)

* Client management
* Contacts & notes
* Activity timeline
* Demo data for instant usability

### 🔐 Roles & Permissions

* Module-scoped permissions
* Role management UI
* Permission-aware menus and pages
* Livewire guards (no page flicker)

### 🧭 Dynamic Navigation

* Modules register their own navigation
* Automatically filtered by:

    * Module enablement
    * User permissions
* Zero hardcoded menus

### 🧙 Installer & Onboarding Wizard

* Guided first-run experience
* Environment checks
* Admin & team creation
* Module selection
* Role auto-provisioning

### 🎯 Demo Seeders

* Optional demo data
* Perfect for screenshots and evaluation
* Never runs accidentally in production

---

## 📸 Screenshots

### Installer & Onboarding

In Progress...

### CRM – Clients

In Progress...

### CRM – Client Activity Timeline

In Progress...

### Roles & Permissions

In Progress...

---

## 🛠 Tech Stack

* **Laravel 12+**
* **Livewire v4**
* Tailwind CSS
* MySQL / PostgreSQL
* PHP 8.2+

---

## 🚀 Installation

### 1. Clone the repository

```bash
git clone https://github.com/onepointhub-app/onepointhub.git
cd onepointhub
```

### 2. Install dependencies

```bash
composer install
npm install && npm run build
```

### 3. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

Configure your database in `.env`.

### 4. Run migrations

```bash
php artisan migrate
```

### 5. Start the app

```bash
php artisan serve
```

Open: **[http://localhost:8000](http://localhost:8000)**

You’ll be redirected automatically to the **installer wizard**.

---

## 🎥 Demo Data (Optional)

To populate the app with realistic demo data:

```bash
ONEPOINTHUB_DEMO_DATA=true php artisan db:seed
```

This will create:

* Sample team
* Users & roles
* Clients
* CRM activities

Perfect for evaluation and screenshots.

---

## 🧩 Modules

OnePointHub uses a **custom module system**.

Each module can provide:

* Migrations
* Models
* Permissions
* Navigation entries
* Seeders
* Livewire components

Example modules:

* Core (required)
* CRM
* (Upcoming) Invoicing
* (Upcoming) Ticketing
* (Upcoming) Knowledge Base

---

## 🔐 Permissions Model

Permissions are:

* Defined per module
* Assigned to roles
* Enforced at:

    * Navigation level
    * Route/middleware level
    * Livewire component level

This ensures:

* No unauthorized UI leaks
* Clean separation of concerns
* Easy extensibility

---

## 🧭 Navigation System

Navigation is:

* Registered by modules
* Filtered by permissions
* Filtered by enabled modules
* Rendered dynamically

No conditionals in Blade.
No duplicated permission logic.

---

## 🧑‍💻 Contributing

Contributions are welcome!

You can help by:

* Adding new modules
* Improving UI/UX
* Writing tests
* Improving documentation
* Fixing bugs

Please see **[CONTRIBUTING.md](CONTRIBUTING.md)** for guidelines.

---

## 🗺 Roadmap

### v0.1 (current)

* Core platform
* CRM module
* Roles & permissions
* Installer
* Demo seeders

### v0.2

* Invoicing module
* Email notifications
* Audit logs
* API tokens

### v0.3

* File sharing
* Knowledge base
* Ticketing system

---

## 📄 License

OnePointHub is open-source software licensed under the **[MIT license](LICENSE.md)**.

---

## ⭐ Why OnePointHub?

Because agencies need:

* Control
* Extensibility
* Ownership
* No vendor lock-in

And developers need:

* Clean architecture
* Real modularity
* A project worth contributing to

**OnePointHub aims to be both.**
