# Module Authoring Guide

This document explains **how to create modules** for OnePointHub.

It is written for **contributors and third‑party developers** who want to extend the platform in a clean, supported way.

---

## 🎯 What Is a Module?

A **module** is a self‑contained feature package.

Each module may provide:

* Database schema
* Models
* Permissions
* Navigation entries
* Livewire UI
* Seeders

Modules can be:

* Enabled / disabled at runtime
* Installed via the installer
* Shipped as first‑party or third‑party packages

---

## 📁 Module Structure

All modules live in:

```
/app/Modules
```

Example:

```
/app/Modules/CRM
├── module.json
├── ModuleServiceProvider.php
├── Database
│   ├── Migrations
│   └── Seeders
├── Models
├── Permissions
├── Navigation
├── Livewire
├── Routes
│   └── web.php
└── Resources
    └── views
```

---

## 🧩 module.json

Each module must define a `module.json` file.

```json
{
  "name": "CRM",
  "slug": "crm",
  "version": "0.1.0",
  "description": "Client relationship management",
  "dependencies": ["core"],
  "permissions": true,
  "navigation": true
}
```

---

## 🚀 Module Service Provider

Each module registers itself via a service provider.

```php
class CRMServiceProvider extends ModuleServiceProvider
{
    protected string $module = 'crm';

    public function boot(): void
    {
        $this->loadMigrations();
        $this->loadRoutes();
        $this->registerPermissions();
        $this->registerNavigation();
        $this->registerLivewire();
    }
}
```

---

## 🗃 Database Migrations

Place migrations in:

```
/app/Modules/{ModuleName}/Database/Migrations
```

They are automatically discovered and executed when the module is installed.

All tables **must be team‑scoped**:

```php
$table->foreignId('team_id')->constrained()->cascadeOnDelete();
```

---

## 🧠 Models

All models must:

* Extend `AbstractModel`
* Use `team_id`
* Avoid global scopes inside modules

Example:

```php
class Client extends AbstractModel
{
    protected $fillable = ['team_id', 'name', 'email'];
}
```

---

## 🔐 Permissions

Permissions live in:

```
/app/Modules/{ModuleName}/Permissions/permissions.php
```

```php
return [
    'clients.view',
    'clients.create',
    'clients.update',
    'clients.delete',
];
```

They are automatically registered during module installation.

---

## 🧭 Navigation

Navigation entries live in:

```
/app/Modules/{ModuleName}/Navigation/navigation.php
```

```php
return [
    'crm' => [
        'label' => 'CRM',
        'icon' => 'users',
        'permission' => 'clients.view',
        'children' => [
            [
                'label' => 'Clients',
                'route' => 'crm.clients.index',
                'permission' => 'clients.view',
            ],
        ],
    ],
];
```

Navigation is automatically filtered by:

* Enabled modules
* User permissions

---

## ⚡ Livewire Components

Livewire components live in:

```
/app/Modules/{ModuleName}/Resources/Pages
```

Each component should:

* Use permission guards
* Be team‑aware

Example:

```php
class ClientIndex extends Component
{
    public function mount()
    {
        abort_unless(auth()->user()->can('clients.view'), 403);
    }
}
```

---

## 🛣 Routes

Routes live in:

```
/app/Modules/{ModuleName}/Routes/web.php
```

```php
Route::middleware(['web', 'auth'])
    ->prefix('crm')
    ->name('crm.')
    ->group(function () {
        Route::get('/clients', ClientIndex::class)
            ->name('clients.index');
    });
```

---

## 🌱 Seeders

Optional demo or default seeders live in:

```
/app/Modules/{ModuleName}/Database/Seeders
```

Seeders should:

* Be idempotent
* Never assume existing data

---

## 📦 Module Installation Lifecycle

1. Module discovered
2. Dependencies checked
3. Migrations executed
4. Permissions registered
5. Navigation registered
6. Seeders executed (optional)

---

## 🚫 What NOT to Do

* ❌ Hardcode navigation in Blade
* ❌ Assume a single team
* ❌ Bypass permission checks
* ❌ Modify core tables directly

---

## ✅ Best Practices

* Keep modules small and focused
* Use descriptive permission keys
* Prefer composition to inheritance
* Document new permissions

---

## 🧠 Philosophy

> Modules should feel like **first‑class citizens**, not plugins bolted on later.

If your module can be disabled without breaking the app — you did it right.

---

Happy building 🚀
