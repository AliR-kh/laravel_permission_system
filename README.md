# Role & Permission Management System

This project is a **Role and Permission Management System** built using **Laravel 12**, **Sanctum**, and a **customized version of the Spatie Permission package**. It is designed to manage users, teams, roles, and fine-grained access to products within a multi-team architecture.

---

## 🔧 Architecture Overview

### 1. Super Admin
- Has full access to all parts of the system.
- Can create teams and assign roles within them.

### 1-a:
- The super admin can create teams and define their internal roles.

### 2. Teams
Each team has three predefined roles:
- **Admin**
- **Editor**
- **Viewer**

#### 2-a:
- All three roles can **view** the products accessible to the team.

#### 2-b:
- Only **Admins** and **Editors** can **edit** the products related to the team.

#### 2-c:
- Any user who has **Admin** or **Editor** role in at least one team can **create** a product specifically for that team.

### 3. Users
- A user can belong to multiple teams.
- A user can have different roles in different teams.

### 4. Super Admin Permissions
- The Super Admin can grant specific users access to individual products for viewing, regardless of team memberships.

---

## 🛠 Technologies Used

- Laravel 12
- Laravel Sanctum (for API authentication)
- Customized Spatie Permission Package
- OTP & Email-based authentication system

---

## 🚀 Installation

### 1. Clone the repository

```
git clone https://your-repo-url.git
cd your-project-directory
```

### 2. Install dependencies

```
composer install
```

### 3. Configure environment

Create a `.env` file and set your **MySQL database credentials**:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Run migrations

```
php artisan migrate
```

### 5. Create the initial Super Admin

```
php artisan app:create_super_admin
```

This will create a user with the following credentials:

- **Phone**: `09123456789`
- **Role**: `super admin`

---

## 📌 Notes

- Ensure Sanctum is correctly configured if you're using token-based authentication.
- This project supports both OTP-based login and traditional email/password login methods.

🔐 Permission System Architecture
The permission system in this project is extensible. In the future, if access control is needed for resources beyond product, the system is designed to support that easily.

🧩 Core Design
Each product has a corresponding permission record in the permissions table.

Products have a many-to-many relationship with both users and teams via permissions.

👁️ Access Rules
A user can only view products:

If they have the permission directly.

Or if they belong to a team with roles admin, editor, or viewer, which is granted access to that product.

Product access to users and teams is managed by the Super Admin.

Users with either the admin or editor role in a specific team are allowed to edit the products that belong to that team.

