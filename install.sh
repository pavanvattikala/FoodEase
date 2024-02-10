#!/bin/bash

# Install Composer dependencies
composer install

# Install NPM dependencies and compile assets if needed
npm install
# or yarn install
# npm run dev (for development) or npm run production (for production)

# Create an environment file if it doesn't exist
cp .env.example .env

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Seed the database using DatabaseSeeder
php artisan db:seed --class=DatabaseSeeder

# Give appropriate permissions to storage and cache directories
chmod -R 775 storage bootstrap/cache

# Inform the user about any additional steps needed
echo "Installation completed. Don't forget to configure your web server and set up any additional services if needed."

echo "dont forget to configure pusher in .env file"

exit 0
