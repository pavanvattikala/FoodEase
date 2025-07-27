# FoodEase - Restaurant Management System

![image](https://github.com/pavanvattikala/FoodEase/assets/88368215/622f9933-e968-417f-a70f-6d7ea0228534)

**FoodEase** is a comprehensive, Laravel-based application designed to streamline all aspects of restaurant management. From table and order processing to a real-time kitchen display, FoodEase provides a complete solution for modern restaurants.

---

## ✨ Key Features

-   **POS System**: A simple and intuitive Point of Sale interface for placing orders, managing customer details, and processing payments.
-   **Table Management**: Visually track table status (available, running, bill printed) and manage orders efficiently.
-   **Real-time KOT**: A live Kitchen Order Ticket (KOT) view for chefs to track incoming orders and manage their workflow.
-   **Waiter & Biller Roles**: Dedicated, permission-based dashboards for waiters and billers, including a mobile-friendly view for waiters to place orders remotely.
-   **Admin Dashboard**: Centralized control panel to manage menus, categories, tables, users, and overall restaurant configuration.
-   **Flexible Setup**: Supports both **MySQL** and **SQLite** databases and includes options for demo or production-ready data seeding.

---

## 🛠️ Technology Stack

-   **Backend**: Laravel, PHP
-   **Frontend**: Blade, Tailwind CSS, Alpine.js
-   **Real-time**: Pusher

---

## 🍽️ Application Modules

Here's a breakdown of the core modules and how they function.

### Admin Dashboard

The central hub for managing the entire restaurant. Admins can create and manage menus, categories, tables, and users. This is also where you configure restaurant details like name, address, and sync settings.
![image](https://github.com/pavanvattikala/FoodEase/assets/88368215/5b078588-a01d-4c28-a008-b1a6b21830b6)

### Point of Sale (POS)

The primary interface for billers and admins to place orders. It features a clean UI, menu search (including by shortcode), customer details, custom notes, and various payment options. The screen is split between categories and the current order for quick entry.
![image](https://github.com/pavanvattikala/FoodEase/assets/88368215/17bfca44-238e-4e7a-b8b0-89799bd502de)

### Table Management

A visual, color-coded screen to select tables for dine-in orders or to initiate a pickup order. It provides quick access to print bills or view orders for a specific table.
![image](https://github.com/pavanvattikala/FoodEase/assets/88368215/c1185c5f-776b-4b56-8b11-f3642ae14943)

### Kitchen Order Ticket (KOT) Display

A real-time display for the kitchen staff, powered by Pusher. New orders appear instantly, categorized by order type. Chefs can see item counts across all live orders and update an order's status as it's prepared.
![image](https://github.com/pavanvattikala/FoodEase/assets/88368215/7b0e1072-0944-4498-bf22-1833769fc0d7)

### Waiter Module

A mobile-first interface designed for waiters to take orders directly at the table using any phone or tablet. This streamlines the ordering process and reduces errors.
![image](https://github.com/pavanvattikala/FoodEase/assets/88368215/e874dc80-248a-4d6c-a492-b2141d796193)

### Biller Module & Bill Management

Users with the "Biller" role have a restricted version of the dashboard. They can place and update orders but cannot perform sensitive admin actions. Admins have full access to view, print, and manage all historical bills.
![image](https://github.com/pavanvattikala/FoodEase/assets/88368215/3b57e8aa-ead9-4c2c-8539-66f9b9c7d715)

---

## 🚀 Getting Started

Follow these instructions to get the project up and running on your local machine.

### 1. Clone the Repository

```bash
git clone [https://github.com/pavanvattikala/FoodEase.git](https://github.com/pavanvattikala/FoodEase.git)
cd FoodEase
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Setup

```bash
# Create your environment file
cp .env.example .env

# Generate an application key
php artisan key:generate
```

### 4. Database Configuration

Open the `.env` file and configure your database. You can use either **MySQL** or **SQLite**.

**For MySQL:**
Make sure you have a MySQL server running and update the following lines in your `.env` file:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=foodease
DB_USERNAME=root
DB_PASSWORD=
```

**For SQLite:**
Change the `DB_CONNECTION` and create an empty database file.

1.  Update your `.env` file:
    ```ini
    DB_CONNECTION=sqlite
    ```
2.  Create the database file:
    ```bash
    touch database/database.sqlite
    ```

### 5. Pusher Configuration (Required for KOT)

For real-time order transmission to the kitchen, you need Pusher credentials.

1.  Sign up for a free account at [pusher.com](https://pusher.com).
2.  Create a new "Channels" app.
3.  Copy your keys into the `.env` file:
    ```ini
    PUSHER_APP_ID=...
    PUSHER_APP_KEY=...
    PUSHER_APP_SECRET=...
    PUSHER_HOST=...
    PUSHER_PORT=...
    PUSHER_SCHEME=...
    PUSHER_APP_CLUSTER=...
    ```

### 6. Database Migration & Seeding

You have two options for setting up the database.

**Option A: For a Full Demo**
This option will create all tables and fill them with example data (users, menus, categories) so you can use the app right away.

```bash
php artisan migrate:fresh --seed
```

_`migrate:fresh` will drop all existing tables and re-run migrations. Use with caution if you have existing data._

**Option B: For Production / Minimal Setup**
This option creates the tables and seeds only the essential data (default admin user and restaurant settings).

1.  Run the migrations:
    ```bash
    php artisan migrate
    ```
2.  Run the basic seeder:
    ```bash
    php artisan db:seed --class=BasicSeeder
    ```

### 7. Build Assets & Start the Server

```bash
# Build frontend assets
npm run dev

# Start the local development server
php artisan serve
```

Your application is now running at **http://localhost:8000**.

---

## 🔑 Usage & Default Credentials

FoodEase supports two login methods:

-   **Admin Login (Email & Password):**

    -   **Email:** `admin@gmail.com`
    -   **Password:** The default password is set in your `.env` file with the `DEFAULT_PASSWORD` key (it defaults to `foodease2024`). You can change it there.

-   **Staff Login (PIN):**
    -   Other roles (Biller, Waiter) can log in using a 4-digit PIN.
    -   You can find or set the PINs for each user in the `users` table after seeding the database.

---

## 🤝 Contributing

Contributions are welcome! If you have a suggestion or find a bug, please feel free to open an issue or submit a pull request.

## 📄 License

This project is licensed under the **Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License (CC BY-NC-SA 4.0)**.

This means you are free to:

-   **Share** — copy and redistribute the material in any medium or format.
-   **Adapt** — remix, transform, and build upon the material.

Under the following terms:

-   **Attribution** — You must give appropriate credit.
-   **NonCommercial** — You may not use the material for commercial purposes.
-   **ShareAlike** — If you remix, transform, or build upon the material, you must distribute your contributions under the same license as the original.

See the [LICENSE](LICENSE) file for the full legal text.
