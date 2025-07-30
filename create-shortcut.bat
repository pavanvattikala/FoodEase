@echo off
echo.
echo =======================================
echo  Creating FoodEase Desktop Shortcut
echo =======================================
echo.

:: --- Configuration ---
:: This finds the script's current directory
set SCRIPT_DIR=%~dp0

:: Set the full paths to your files
set TARGET_FILE=%SCRIPT_DIR%foodease.bat
set SHORTCUT_NAME=%USERPROFILE%\Desktop\Start FoodEase.lnk
set ICON_FILE=%SCRIPT_DIR%public\FoodEase.ico

echo Target script: %TARGET_FILE%
echo Shortcut location: %SHORTCUT_NAME%
echo Icon file: %ICON_FILE%
echo.

:: Check if the icon file exists before creating the shortcut
if not exist "%ICON_FILE%" (
    echo ERROR: Icon file not found at %ICON_FILE%
    echo Please make sure the icon is in the public folder.
    echo.
    pause
    exit /b
)

:: Use PowerShell to create the shortcut with the custom icon
powershell.exe -Command "$ws = New-Object -ComObject WScript.Shell; $s = $ws.CreateShortcut('%SHORTCUT_NAME%'); $s.TargetPath = '%TARGET_FILE%'; $s.WorkingDirectory = '%SCRIPT_DIR%'; $s.IconLocation = '%ICON_FILE%'; $s.Save()"

echo =======================================
echo  Shortcut created successfully!
echo  You can now find "Start FoodEase" on your desktop.
echo =======================================
echo.
pause