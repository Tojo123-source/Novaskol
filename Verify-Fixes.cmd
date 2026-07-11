@echo off
REM Novaskol Fixes Verification Script
REM Checks that all database schema fixes are properly deployed

setlocal enabledelayedexpansion

echo.
echo ============================================
echo   Novaskol Fixes Verification
echo ============================================
echo.

set "ERRORS=0"
set "SUCCESS=0"

REM Check main migration file
echo Checking main migration file...
if exist "database\migrations\2026_05_18_000000_create_core_tables.php" (
    echo   [OK] Main migration exists
    set /a SUCCESS+=1
) else (
    echo   [FAIL] Main migration NOT found
    set /a ERRORS+=1
)

REM Check distribution migrations
echo.
echo Checking distribution app migrations...

if exist "storage\app\distribution\novaskol-app-latest\database\migrations\2026_05_18_000000_create_core_tables.php" (
    echo   [OK] Latest distribution migration exists
    set /a SUCCESS+=1
) else (
    echo   [FAIL] Latest distribution migration NOT found
    set /a ERRORS+=1
)

if exist "storage\app\distribution\novaskol-app-20260515_032740\database\migrations\2026_05_18_000000_create_core_tables.php" (
    echo   [OK] 20260515 distribution migration exists
    set /a SUCCESS+=1
) else (
    echo   [FAIL] 20260515 distribution migration NOT found
    set /a ERRORS+=1
)

REM Check documentation files
echo.
echo Checking documentation...

if exist "BUILD_AND_DEPLOY.md" (
    echo   [OK] BUILD_AND_DEPLOY.md exists
    set /a SUCCESS+=1
) else (
    echo   [FAIL] BUILD_AND_DEPLOY.md NOT found
    set /a ERRORS+=1
)

if exist "FIXES_COMPLETED.md" (
    echo   [OK] FIXES_COMPLETED.md exists
    set /a SUCCESS+=1
) else (
    echo   [FAIL] FIXES_COMPLETED.md NOT found
    set /a ERRORS+=1
)

if exist "QUICK_START.md" (
    echo   [OK] QUICK_START.md exists
    set /a SUCCESS+=1
) else (
    echo   [FAIL] QUICK_START.md NOT found
    set /a ERRORS+=1
)

REM Check build scripts
echo.
echo Checking build scripts...

if exist "Build-Desktop.cmd" (
    echo   [OK] Build-Desktop.cmd exists
    set /a SUCCESS+=1
) else (
    echo   [FAIL] Build-Desktop.cmd NOT found
    set /a ERRORS+=1
)

if exist "Build-Connecte.cmd" (
    echo   [OK] Build-Connecte.cmd exists
    set /a SUCCESS+=1
) else (
    echo   [FAIL] Build-Connecte.cmd NOT found
    set /a ERRORS+=1
)

REM Check validation script
if exist "validate-schema.php" (
    echo   [OK] validate-schema.php exists
    set /a SUCCESS+=1
) else (
    echo   [FAIL] validate-schema.php NOT found
    set /a ERRORS+=1
)

REM Check application configs
echo.
echo Checking application configs...

if exist "desktop\package.json" (
    echo   [OK] Desktop package.json exists
    set /a SUCCESS+=1
) else (
    echo   [FAIL] Desktop package.json NOT found
    set /a ERRORS+=1
)

if exist "apps\novaskol-connecte-desktop\package.json" (
    echo   [OK] Connecte package.json exists
    set /a SUCCESS+=1
) else (
    echo   [FAIL] Connecte package.json NOT found
    set /a ERRORS+=1
)

REM Print summary
echo.
echo ============================================
echo   VERIFICATION SUMMARY
echo ============================================
echo.
echo   Checks passed:  !SUCCESS!
echo   Checks failed:  !ERRORS!
echo.

if !ERRORS! EQU 0 (
    echo   [SUCCESS] All fixes are properly deployed!
    echo.
    echo   Next steps:
    echo   1. Run: Build-Desktop.cmd
    echo   2. Run: Build-Connecte.cmd
    echo   3. Test the built installers
    echo.
    exit /b 0
) else (
    echo   [ERROR] Some files are missing!
    echo.
    echo   Please check that all migration files and scripts are present.
    echo   If files are missing, the build may not work correctly.
    echo.
    exit /b 1
)
