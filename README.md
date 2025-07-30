# FoodEase - Restaurant Management System

<img width="1915" height="919" alt="image" src="https://github.com/user-attachments/assets/8510ca23-8497-441f-91f4-3a4d97b7fe51" />

**FoodEase** is a comprehensive, Laravel-based application designed to streamline all aspects of restaurant management. From table and order processing to a real-time kitchen display, FoodEase provides a complete solution for modern restaurants.

---

## ✨ Key Features

-   **POS System**: A simple and intuitive Point of Sale interface for placing orders, managing customer details, and processing payments.
-   **Table Management**: Visually track table status (available, running, bill printed) and manage orders efficiently.
-   **Real-time KOT**: A live Kitchen Order Ticket (KOT) view for chefs to track incoming orders and manage their workflow.
-   **Waiter & Biller Roles**: Dedicated, permission-based dashboards for waiters and billers, including a mobile-friendly view for waiters to place orders remotely.
-   **Admin Dashboard**: Centralized control panel to manage menus, categories, tables, users, and overall restaurant configuration.
-   **Flexible Setup**: Supports both **MySQL** and **SQLite** databases and includes options for demo or production-ready data seeding.
-   **One-Click Server Start**: Includes helper scripts for Windows users to create a desktop shortcut and launch the server with a single click, automatically detecting the correct network IP.

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
<img width="1890" height="903" alt="image" src="https://github.com/user-attachments/assets/61eab694-cea2-4718-bba0-47eca5ce50ca" />

They can configure the printer of kitchen and billing sections.

<img width="1902" height="351" alt="image" src="https://github.com/user-attachments/assets/bcd7dc53-313f-49aa-8ba6-763d837ccd93" />

They can enable or disbles modules according to their business needs.

<img width="1912" height="574" alt="image" src="https://github.com/user-attachments/assets/35471025-f8dd-49e7-8ac8-9f925e5cc30a" />

### Point of Sale (POS)

The primary interface for billers and admins to place orders. It features a clean UI, menu search (including by shortcode), customer details, custom notes, and various payment options. The screen is split between categories and the current order for quick entry.
<img width="1919" height="914" alt="image" src="https://github.com/user-attachments/assets/cddbdd07-2c58-4282-88d3-0be66b9107e0" />

### Table Management

A visual, color-coded screen to select tables for dine-in orders or to initiate a pickup order. It provides quick access to print bills or view orders for a specific table.
<img width="1915" height="910" alt="image" src="https://github.com/user-attachments/assets/eea49a45-640c-433b-8077-0b24912f1b32" />

### Kitchen Order Ticket (KOT) Display

A real-time display for the kitchen staff, powered by Pusher. New orders appear instantly, categorized by order type. Chefs can see item counts across all live orders and update an order's status as it's prepared.
<img width="1885" height="908" alt="image" src="https://github.com/user-attachments/assets/9f2fcf46-2c18-44af-8a7e-95ae7e32b870" />

### Waiter Module

A mobile-first interface designed for waiters to take orders directly at the table using any phone or tablet. This streamlines the ordering process and reduces errors.

<img width="300" height="800" alt="image" src="https://github.com/user-attachments/assets/09272c98-dfa4-47ec-b870-3b1195b5af9f" />

### Kitchen Module

A dynamic Kitchen Display System (KDS) where new orders appear instantly. Chefs can manage their workflow by moving tickets from 'Pending' to 'In Progress', and see a live count of all items needed across active orders.

<img width="1905" height="843" alt="image" src="https://github.com/user-attachments/assets/9ff227a1-6585-4a7a-84e0-0d5e81e06540" />

### Biller Module & Bill Management

Users with the "Biller" role have a restricted version of the dashboard. They can place and update orders but cannot perform sensitive admin actions. Admins have full access to view, print, and manage all historical bills.
<img width="1916" height="907" alt="image" src="https://github.com/user-attachments/assets/78a346ab-59f0-414d-ae83-b2ea42f631a6" />

---

## 🚀 Getting Started

Follow these instructions to get the project up and running on your local machine.

### Quick Start for Windows (Recommended)

This is the easiest way to get the server running.

1.  **Complete the Manual Installation first:** Follow all the steps in the "Manual Installation" section below to set up your project dependencies and `.env` file.
2.  **Create Desktop Shortcut (One-time only):** Find the `create-shortcut.bat` file in the project folder and double-click it. This will create a "Start FoodEase" shortcut on your desktop with the app logo.
3.  **Start the Server:** Double-click the new **"Start FoodEase"** shortcut on your desktop anytime you want to run the application. It will automatically find your IP address and start the server.

### Manual Installation

Follow these steps for a complete manual setup.

#### 1. Clone the Repository

```bash
git clone [https://github.com/pavanvattikala/FoodEase.git](https://github.com/pavanvattikala/FoodEase.git)
cd FoodEase
```

#### 2. Install Dependencies

```bash
composer install
npm install
```

#### 3. Environment Setup

```bash
# Create your environment file
cp .env.example .env

# Generate an application key
php artisan key:generate
```

#### 4. Database Configuration

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

#### 5. Pusher Configuration (Required for KOT)

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
4.  Rebuild the frontend assets
    ```bash
    npm run dev
    ```

#### 6. Database Migration & Seeding

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

#### 7. Build Assets & Start the Server

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
