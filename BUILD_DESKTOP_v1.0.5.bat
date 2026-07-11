@echo off
REM ============================================================================
REM Novaskol Desktop Application v1.0.5 - Complete Fresh Rebuild
REM ============================================================================
setlocal enabledelayedexpansion

echo.
echo ============================================================================
echo Novaskol Desktop v1.0.5 - Complete Fresh Rebuild
echo ============================================================================
echo.

REM Step 1: Delete old build directory
echo [Step 1/8] Deleting old build directory...
echo Path: g:\wamp64\www\novaskol-laravel\storage\app\desktop-dist
rmdir /s /q "g:\wamp64\www\novaskol-laravel\storage\app\desktop-dist" 2>nul
if %errorlevel% equ 0 (
    echo  ✓ Build directory deleted successfully
) else (
    echo  ✓ Build directory didn't exist (OK)
)
echo.

REM Step 2: Navigate to desktop folder
echo [Step 2/8] Navigating to desktop folder...
cd /d "g:\wamp64\www\novaskol-laravel\desktop"
if %errorlevel% neq 0 (
    echo  ✗ ERROR: Failed to change to desktop directory
    exit /b 1
)
echo  ✓ Current directory: !cd!
echo.

REM Step 3: Check and delete node_modules
echo [Step 3/8] Checking node_modules...
if exist "node_modules" (
    echo  - Deleting node_modules directory...
    rmdir /s /q node_modules
    if %errorlevel% neq 0 (
        echo  ✗ ERROR: Failed to delete node_modules
        exit /b 1
    )
    echo  ✓ node_modules deleted successfully
) else (
    echo  ✓ node_modules doesn't exist (OK)
)
echo.

REM Step 4: Check and delete package-lock.json
echo [Step 4/8] Checking package-lock.json...
if exist "package-lock.json" (
    echo  - Deleting package-lock.json...
    del package-lock.json
    if %errorlevel% neq 0 (
        echo  ✗ ERROR: Failed to delete package-lock.json
        exit /b 1
    )
    echo  ✓ package-lock.json deleted successfully
) else (
    echo  ✓ package-lock.json doesn't exist (OK)
)
echo.

REM Step 5: npm install
echo [Step 5/8] Running npm install (fresh install)...
echo.
call npm install
if %errorlevel% neq 0 (
    echo.
    echo  ✗ ERROR: npm install failed with error code %errorlevel%
    exit /b 1
)
echo.
echo  ✓ npm install completed successfully
echo.

REM Step 6: npm run dist
echo [Step 6/8] Running npm run dist (building application)...
echo.
call npm run dist
if %errorlevel% neq 0 (
    echo.
    echo  ✗ ERROR: npm run dist failed with error code %errorlevel%
    exit /b 1
)
echo.
echo  ✓ npm run dist completed successfully
echo.

REM Step 7: Verify exe file exists
echo [Step 7/8] Verifying exe file was created...
if exist "..\storage\app\desktop-dist\Novaskol-Setup-1.0.5-x64.exe" (
    echo  ✓ Exe file found!
) else (
    echo  ✗ ERROR: Exe file NOT found at expected location
    echo    Expected: ..\storage\app\desktop-dist\Novaskol-Setup-1.0.5-x64.exe
    echo.
    echo  Checking what was actually created in desktop-dist:
    if exist "..\storage\app\desktop-dist" (
        dir "..\storage\app\desktop-dist\"
    ) else (
        echo  ERROR: desktop-dist directory doesn't exist!
    )
    exit /b 1
)
echo.

REM Step 8: Show file details
echo [Step 8/8] File details...
echo.
dir "..\storage\app\desktop-dist\Novaskol-Setup-1.0.5-x64.exe"
echo.

REM Success!
echo.
echo ============================================================================
echo ✓ SUCCESS! Build completed successfully
echo ============================================================================
echo.
echo Build artifact created:
echo   Path: ..\storage\app\desktop-dist\Novaskol-Setup-1.0.5-x64.exe
echo.
pause
exit /b 0
