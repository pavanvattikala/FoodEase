@echo off

REM Install Composer dependencies
composer install

REM Install NPM dependencies and compile assets if needed
npm install
REM or yarn install
REM npm run dev (for development) or npm run production (for production)

REM Create an environment file if it doesn't exist
copy .env.example .env

REM Generate application key
php artisan key:generate

REM Run database migrations
php artisan migrate

REM Seed the database using DatabaseSeeder
php artisan db:seed --class=DatabaseSeeder

REM Give appropriate permissions to storage and cache directories
echo y|cacls storage /t /c /g everyone:f
echo y|cacls bootstrap/cache /t /c /g everyone:f

REM Inform the user about any additional steps needed
echo Installation completed. Don't forget to configure your web server and set up any additional services if needed.

echo Dont forget to configure pusher in .env file

pause
