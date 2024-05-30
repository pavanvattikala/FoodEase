#!/bin/bash
# commands start

# Install Composer dependencies
composer install

# Install NPM dependencies 
npm install

# compile assets 
npm run dev # (for development) 
#or
npm run production  # (for production)

# Create an environment file 
cp .env.example .env

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Seed the database using DatabaseSeeder
php artisan db:seed --class=DatabaseSeeder

# Give appropriate permissions to storage and cache directories
chmod -R 775 storage bootstrap/cache


# commands end

# Inform the user about for additional steps needed
echo "Installation completed. Don't forget to configure your web server and set up pusher service if needed."

echo "dont forget to configure pusher in .env file"

read -p "Press Enter to exit..."


exit 0
