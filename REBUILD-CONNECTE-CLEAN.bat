@echo off
REM ============================================
REM  NOVASKOL CONNECTE - CLEAN REBUILD v0.2.0
REM ============================================
REM This script performs a COMPLETE CLEAN rebuild
REM Deletes old build and creates fresh version

setlocal enabledelayedexpansion

echo.
echo ============================================
echo   NOVASKOL CONNECTE - CLEAN REBUILD
echo   Version 0.2.0
echo ============================================
echo.

cd /d "%~dp0"
set ROOT_DIR=%cd%

echo [1/8] Deleting old build directory...
if exist "storage\app\desktop-connecte-dist" (
    echo   Removing: storage\app\desktop-connecte-dist
    rmdir /s /q "storage\app\desktop-connecte-dist"
    if exist "storage\app\desktop-connecte-dist" (
        echo   WARNING: Could not fully delete old directory
    ) else (
        echo   [OK] Old build directory deleted
    )
) else (
    echo   [OK] No old build directory found
)

echo.
echo [2/8] Checking Node.js installation...
where node >nul 2>nul
if errorlevel 1 (
    echo   [ERROR] Node.js not installed!
    echo   Please install from: https://nodejs.org/
    exit /b 1
)
for /f "tokens=*" %%i in ('node --version') do set NODE_VERSION=%%i
echo   [OK] Node.js version: !NODE_VERSION!

echo.
echo [3/8] Checking npm installation...
where npm >nul 2>nul
if errorlevel 1 (
    echo   [ERROR] npm not found!
    exit /b 1
)
for /f "tokens=*" %%i in ('npm --version') do set NPM_VERSION=%%i
echo   [OK] npm version: !NPM_VERSION!

echo.
echo [4/8] Navigating to connecte directory...
cd /d "%ROOT_DIR%\apps\novaskol-connecte-desktop"
if not exist "package.json" (
    echo   [ERROR] package.json not found!
    exit /b 1
)
echo   [OK] In connecte directory

echo.
echo [5/8] Cleaning node_modules...
if exist "node_modules" (
    echo   Deleting node_modules...
    rmdir /s /q "node_modules"
    echo   [OK] node_modules deleted
) else (
    echo   [OK] No node_modules to delete
)

if exist "package-lock.json" (
    echo   Deleting package-lock.json...
    del /q "package-lock.json"
    echo   [OK] package-lock.json deleted
) else (
    echo   [OK] No package-lock.json to delete
)

echo.
echo [6/8] Installing fresh dependencies with npm install...
echo   This may take 2-3 minutes...
call npm install
if errorlevel 1 (
    echo   [ERROR] npm install failed!
    exit /b 1
)
echo   [OK] Dependencies installed

echo.
echo [7/8] Building application with npm run dist...
echo   This may take 5-10 minutes...
call npm run dist
if errorlevel 1 (
    echo   [ERROR] Build failed!
    exit /b 1
)
echo   [OK] Build completed

echo.
echo [8/8] Verifying output file...
cd /d "%ROOT_DIR%"
if exist "storage\app\desktop-connecte-dist\Novaskol-Connecte-Setup-0.2.0-x64.exe" (
    for %%F in ("storage\app\desktop-connecte-dist\Novaskol-Connecte-Setup-0.2.0-x64.exe") do (
        echo   [OK] Installer found
        echo   File: %%~nxF
        echo   Size: %%~zF bytes
        echo   Date: %%~tF
    )
) else (
    echo   [ERROR] Installer file not created!
    exit /b 1
)

echo.
echo ============================================
echo   BUILD COMPLETED SUCCESSFULLY!
echo ============================================
echo.
echo   Output file:
echo   storage\app\desktop-connecte-dist\Novaskol-Connecte-Setup-0.2.0-x64.exe
echo.
echo   Ready for installation and testing!
echo.

exit /b 0
