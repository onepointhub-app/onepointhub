# Contributing to OnePointHub

First — **thank you** for considering contributing to OnePointHub 🎉

OnePointHub exists to become a **high‑quality, modular, open‑source business platform** for agencies and small teams. Contributions of all kinds are welcome.

---

## 🧭 Project Philosophy

Before contributing, please understand the core principles of OnePointHub:

* **Modularity first** — everything is a module
* **Team‑scoped by default** — no global data leaks
* **Permissions everywhere** — UI, routes, Livewire
* **Clarity over cleverness** — readable code wins
* **Contributor‑friendly** — predictable patterns

If a change breaks these principles, it will likely be rejected.

---

## 🧑‍💻 Ways to Contribute

You can contribute by:

* 🐛 Fixing bugs
* ✨ Adding features
* 🧩 Creating new modules
* 🎨 Improving UI / UX
* 📘 Improving documentation
* 🧪 Writing tests

All contributions are welcome — even small ones.

---

## 🚀 Getting Started

### 1. Fork & Clone

```bash
git clone https://github.com/onepointhub-app/onepointhub.git
cd onepointhub
```

### 2. Install Dependencies

```bash
composer install
npm install && npm run build
```

### 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Configure your database in `.env`.

### 4. Migrate & Seed (Optional Demo Data)

```bash
php artisan migrate
ONEPOINTHUB_DEMO_DATA=true php artisan db:seed
```

### 5. Run the App

```bash
php artisan serve
```

---

## 🧩 Working with Modules

Please read the **[Module Authoring Guide](docs/ModuleAuthoring.md)** before creating or modifying modules.

Key rules:

* One feature = one module
* No hardcoded navigation
* No bypassing permissions
* All models must be team‑scoped

---

## 🔐 Permissions Rules

When adding features:

* Define permissions in the module
* Guard routes **and** Livewire components
* Register navigation with permission keys

If a feature is visible, it **must** be authorized.

---

## 🧪 Testing Expectations

Testing is encouraged, not mandatory (yet).

If you add:

* Business logic → add tests if possible
* New modules → ensure clean install
* UI changes → manual sanity check

Tests should be:

* Clear
* Readable
* Independent

---

## 🧹 Code Style

* Follow Laravel conventions
* Use meaningful names
* Avoid magic behavior
* Prefer composition to inheritance
* Keep methods small

If in doubt: **opt for readability**.

---

## 📝 Commit Guidelines

* Write clear commit messages
* One logical change per commit

Examples:

```
Add CRM client activity timeline
Fix permission check on role editor
Refactor module installer logic
```

---

## 📦 Pull Request Guidelines

Before submitting a PR:

* [ ] Code compiles
* [ ] App boots on fresh installation
* [ ] No debug code left
* [ ] Permissions respected
* [ ] Relevant docs updated

PRs should include:

* What changed
* Why it changed
* Screenshots (if UI-related)

---

## 🐞 Reporting Bugs

Please open an issue and include:

* Steps to reproduce
* Expected behavior
* Actual behavior
* Screenshots (if applicable)
* Environment details

---

## 🧭 Feature Requests

Feature ideas are welcome.

Please:

* Explain the use case
* Describe the target users
* Consider modular impact

Large features should usually be implemented as **modules**.

---

## 🤝 Code of Conduct

Be respectful.
Be constructive.
Assume good intent.

Harassment, discrimination, or toxic behavior will not be tolerated.

---

## ❤️ Thank You

Open‑source lives on community effort.

Whether you fix a typo or build a full module — **you matter**.

Happy contributing 🚀
