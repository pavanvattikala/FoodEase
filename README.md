# Food Ease

Welcome to the Food Ease, a Laravel-based application for managing orders, tables, and more.

## Features

-   **Table Management:** Easily manage restaurant tables, track their status, and assign orders.
-   **Order Management:** Efficiently handle customer orders, view order history, and process different order types.
-   **Waiter Dashboard:** Waiters can use a user-friendly interface to manage tables and orders.
-   **Kitchen Display:** Streamline kitchen operations with real-time order updates for chefs.

## Getting Started

### Prerequisites

-   [Laravel](https://laravel.com/docs/8.x/installation)
-   [Composer](https://getcomposer.org/download/)
-   [Node.js and NPM](https://nodejs.org/)

### Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/pavanvattikala/FoodEase.git

    ```

2. Navigate to the project directory:

    ```bash
    cd FoodEase

    ```

3. Install dependencies:

    ```bash
    composer install

    npm install && npm run dev

    ```

4. Configure the environment:

    ```bash
    cp .env.example .env

    php artisan key:generate

    ```

5. Update the .env file with your database configuration.

6. Migrate the database:

    ```bash
    php artisan migrate

    ```

7. Start the development server:

    ```bash
    php artisan serve

    ```

8. Access the application in your browser: http://localhost:8000

## Contributing

We welcome contributions! Feel free to submit issues, feature requests, or pull requests.

## License

This project is licensed under the MIT License - see the LICENSE file for details.
