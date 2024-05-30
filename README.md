# Food Ease

Welcome to the Food Ease, a Laravel-based application for managing orders, tables, and more.

## Features

-   **Table Management:** Easily manage restaurant tables, track their status, and assign orders.
-   **Order Management:** Efficiently handle customer orders, view order history, and process different order types.
-   **Waiter Dashboard:** Waiters can use a user-friendly interface to manage tables and orders.
-   **Kitchen Display:** Streamline kitchen operations with real-time order updates for chefs.

## APP UI
### Login
Foodease supports two login mechanism
1. Admin Login ( only via email and password ) ( email : admin@gmail.com , password : foodease )
2. PIN login this login is for faster login ( view users table for pins )
   
![image](https://github.com/pavanvattikala/FoodEase/assets/88368215/f2bb74f5-6b4a-436d-bd62-2cd8a2fd60ce)

### Tables
Tables Screen to place orders by selecting table
1. Table Status displayed by colors
2. Table options to print KOT, Save Bill
3. Pickup option for pickup orders
   
![image](https://github.com/pavanvattikala/FoodEase/assets/88368215/c1185c5f-776b-4b56-8b11-f3642ae14943)

### POS Screen
Simple UI for POS Screen to place orders
1. Search By Shortcode ( see on menus )
2. Searchle Menu from Categories
3. Displays the categories on left side
4. Can add customer naem, mobile to order
5. Can add custom Notes to order
6. Select Payment Options
7. Send KOT or Print 

![image](https://github.com/pavanvattikala/FoodEase/assets/88368215/17bfca44-238e-4e7a-b8b0-89799bd502de)

### Dashboard 
1. Menu To create New Menu Items
2. Categories To Create new Category
3. Tables to Create new Tables
4. Reservation to create new Reservation
   
![image](https://github.com/pavanvattikala/FoodEase/assets/88368215/622f9933-e968-417f-a70f-6d7ea0228534)

#### KOT View
1. adpatable KOT view
2. displays self placed orders for waiter
3. displays all orders for admin and biller
4. categories order by order types
5. easy quick button for modifying status
   
![image](https://github.com/pavanvattikala/FoodEase/assets/88368215/7b0e1072-0944-4498-bf22-1833769fc0d7)

#### Bills ( only admin )
1. view placed bills
2. CRUD Operation
3. Print , export bills
4. analytics will be added soon...
   
![image](https://github.com/pavanvattikala/FoodEase/assets/88368215/3b57e8aa-ead9-4c2c-8539-66f9b9c7d715)

#### Restuarant Management
1. these options will be used on printing KOT's and Bills
2. Order Placing options
3. ASC / Desc options
4. new options will be added soon..

![image](https://github.com/pavanvattikala/FoodEase/assets/88368215/5b078588-a01d-4c28-a008-b1a6b21830b6)


### Kitchen Module
1. Kitchen layout has three areas
2. To accept the order ( by default after 5 mins order is auto accepted )
3. current order ( order which are in progress )
4. Items count ( helps the managemnent to quickly process items based on count and type )
   
![image](https://github.com/pavanvattikala/FoodEase/assets/88368215/2b10dd83-1c92-46c7-bc11-665b3b8aa900)


### Waiter Module
Waiter module is designed for waiter so that they can login and place orders remotly from mobile phones
1. Shortcut to web link can be placed on home screen to qucik access
2. Mobile UI for easy order placement
3. Table Selection ( same as POS table selection )
4. ![image](https://github.com/pavanvattikala/FoodEase/assets/88368215/e874dc80-248a-4d6c-a492-b2141d796193)
5. Simple Order Placement UI
6. ![image](https://github.com/pavanvattikala/FoodEase/assets/88368215/486375e9-37d1-40ce-b11c-d9baae76e614)
7. Submit to kitchen, also we can add custom notes
8. ![image](https://github.com/pavanvattikala/FoodEase/assets/88368215/20f8f931-1b04-4f38-9561-3899837cb466)

### Biller Module
Biller module is cut down version of admin module
1. can only place orders, update
2. Restricted to create or view bills

## How to install for youself
To get this application you can go two ways
1. You can contact me Pavan Vattikala - [pavanvattikala54@gmail.com](mailto:pavanvattikala54@gmail.com) for Desktop App ( runs on your system easily and can be accesed across restaurent )
2.  You can follow the below steps for local development and dev server

## Getting Started for Local Installation

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

### Kitchen Module
Foodease depends on PUSHER for transimitting the orders , copy and paste the keys from pusher dashboard in .env
after copying run the command
```bash
npm run dev
```

## Contributing

We welcome contributions! Feel free to submit issues, feature requests, or pull requests.

## License

This project is licensed under the MIT License - see the LICENSE file for details.
