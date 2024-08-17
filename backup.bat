@echo off
setlocal enabledelayedexpansion

rem Read .env file and set environment variables
for /f "tokens=1,* delims==" %%a in (.env) do (
    set "var=%%a"
    set "value=%%b"
    set "!var!=!value!"
)

rem Get the current date and time
for /f "tokens=1-4 delims=/ " %%a in ('date /t') do (
    set day=%%a
    set month=%%b
    set year=%%c
    set time=%%d
)
for /f "tokens=1-2 delims=:." %%a in ("%time%") do (
    set hour=%%a
    set minute=%%b
)

rem Remove leading spaces from hour
set hour=%hour: =0%

rem Set timestamp format
set "timestamp=%year%-%month%-%day%_%hour%-%minute%-%second%"

rem Define filename
set "filename=%DB_DATABASE%-%timestamp%.sql"

rem Define output path
set "OUTPUT_PATH=%cd%\storage\backups"

rem Run mysqldump
start "" "C:\Program Files\MySQL\MySQL Server 8.3\bin\mysqldump.exe" --user=%DB_USERNAME% --password=%DB_PASSWORD% %DB_DATABASE% --result-file="%OUTPUT_PATH%\%filename%"
