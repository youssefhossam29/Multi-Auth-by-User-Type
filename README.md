# Laravel Multi-Auth Starter

This repository contains the source code for a Laravel-based authentication system that supports multiple user roles using Enum, middleware, and role-based routing.

## 🔍 Overview

This Laravel project introduces a simple but effective **multi-auth system** using:

- Enum for defining user roles  
- Middleware to restrict access based on role  
- Cleanly separated dashboards and routes for different user types  

## 🚀 Features

- Role-based authentication and redirection  
- Enum-based `UserType` definition  
- Middleware to restrict access by role  
- Route grouping by user type (Admin, Manager, User)  
- Separate dashboards for each role  

## 🧱 Technologies

- PHP 8.1+  
- Laravel 10  
- MySQL  
- Composer  

## 🧑‍💻 User Roles

Users are assigned a `type` stored in the `users` table:

| Type     | Value |
|----------|-------|
| USER     | 0     |
| ADMIN    | 1     |
| MANAGER  | 2     |

This is represented in code using an `enum`:

```php
// app/Enums/UserType.php

enum UserType: int {
    case USER = 0;
    case ADMIN = 1;
    case MANAGER = 2;
}
````

## 🛠 Getting Started

Follow these steps to set up the project locally:

1. **Clone the Repository**

   ```bash
   git clone https://github.com/your-username/your-repo.git
   cd your-repo
   ```

2. **Install Dependencies**

   ```bash
   composer install
   npm install && npm run build   # if frontend assets exist
   ```

3. **Configure Environment**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   * Edit `.env` and add your database configuration

4. **Run Migrations**

   ```bash
   php artisan migrate
   ```

5. **(Optional) Seed the Database**

   To quickly test role-based authentication, run:

   ```bash
   php artisan db:seed --class=DataSeeder
   ```

   This will create three users:

   | Role    | Email                                             | Password |
   | ------- | ------------------------------------------------- | -------- |
   | Admin   | [admin@example.com](mailto:admin@example.com)     | password |
   | Manager | [manager@example.com](mailto:manager@example.com) | password |
   | User    | [user@example.com](mailto:user@example.com)       | password |

6. **Serve the App**

   ```bash
   php artisan serve
   ```

## 🔐 Multi-Auth Implementation

### 1. Database

A new `type` column was added to the `users` table:

```php
$table->tinyInteger('type')->default(0);
```

### 2. Enum Casting

In the `User` model:

```php
protected $casts = [
    'type' => \App\Enums\UserType::class,
];
```

### 3. Middleware: `CheckTypes`

Custom middleware was created to restrict route access based on role:

```php
if (!in_array(auth()->user()->type, $allowedRoles)) {
    abort(403, 'Unauthorized');
}
```

### 4. Auth Redirection

In `AuthenticatedSessionController`, login redirection is handled like this:

```php
return match ($user->type) {
    UserType::ADMIN => redirect()->route('admin.dashboard'),
    UserType::MANAGER => redirect()->route('manager.dashboard'),
    UserType::USER => redirect()->route('user.dashboard'),
    default => redirect()->route('user.dashboard'),
};
```

### 5. Role-Based Routes

Defined in `routes/web.php`:

```php
Route::middleware(['auth', 'CheckTypes:admin'])->prefix('admin')->name('admin.')->group(...);
Route::middleware(['auth', 'CheckTypes:manager'])->prefix('manager')->name('manager.')->group(...);
Route::middleware(['auth', 'CheckTypes:user'])->prefix('user')->name('user.')->group(...);
```

### 6. Role Dashboards

Each user type has its own controller and dashboard view:

* `AdminController` → `/admin/dashboard`
* `ManagerController` → `/manager/dashboard`
* `UserController` → `/user/dashboard`

## 📁 Project Structure Highlights

```
app/
├── Enums/UserType.php
├── Http/Middleware/CheckTypes.php
├── Http/Controllers/AdminController.php
├── Http/Controllers/ManagerController.php
├── Http/Controllers/UserController.php
resources/views/
├── admin/dashboard.blade.php
├── manager/dashboard.blade.php
├── user/dashboard.blade.php
```

Use these accounts to log in and test role-based functionality.

---

## 📬 Contribution

Feel free to fork, clone, and submit pull requests. Suggestions and improvements are always welcome!

---
