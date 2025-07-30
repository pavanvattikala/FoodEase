@echo off
cls
echo ===================================
echo  Starting FoodEase Local Server...
echo ===================================
echo.

:: --- Pre-flight Checks ---

echo Verifying setup requirements...

:: 1. Check if the 'artisan' file exists in the current directory
if not exist "artisan" (
    echo [ERROR] Artisan file not found.
    echo Please run this script from the root of your Laravel project.
    echo.
    pause
    exit /b
)

:: 2. Check if PHP is installed and available in the PATH
where php >nul 2>nul
if %errorlevel% neq 0 (
    echo [ERROR] PHP command not found.
    echo Please ensure PHP is installed and added to your system's PATH.
    echo.
    pause
    exit /b
)

:: 3. Check if Composer dependencies are installed
if not exist "vendor" (
    echo [WARNING] 'vendor' directory not found.
    echo It looks like you haven't installed the Composer dependencies.
    echo Running 'composer install' for you...
    composer install
    if %errorlevel% neq 0 (
        echo [ERROR] 'composer install' failed. Please run it manually.
        pause
        exit /b
    )
    echo.
)

echo Setup verified successfully!
echo.

:: --- Start the Server ---

php artisan serve:local

echo.
echo ===================================
echo  Server stopped.
echo ===================================
echo Press any key to close this window.
pause >nul