@ECHO off

cd /d D:projects\FoodEase\FoodEase-Web

call backup.bat

php artisan serve
