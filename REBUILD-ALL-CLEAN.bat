@echo off
REM ============================================
REM  NOVASKOL COMPLETE REBUILD - BOTH APPS
REM ============================================
REM Cleans and rebuilds both Desktop and Connecte
REM in sequence

setlocal enabledelayedexpansion

echo.
echo ============================================
echo   NOVASKOL COMPLETE REBUILD
echo   Desktop v1.0.5 + Connecte v0.2.0
echo ============================================
echo.

set ROOT_DIR=%cd%

echo.
echo PHASE 1: Building Novaskol Desktop v1.0.5
echo ============================================
echo.
call REBUILD-DESKTOP-CLEAN.bat
if errorlevel 1 (
    echo.
    echo [ERROR] Desktop build failed!
    exit /b 1
)

echo.
echo.
echo PHASE 2: Building Novaskol Connecte v0.2.0
echo ============================================
echo.
call REBUILD-CONNECTE-CLEAN.bat
if errorlevel 1 (
    echo.
    echo [ERROR] Connecte build failed!
    exit /b 1
)

echo.
echo ============================================
echo   ALL BUILDS COMPLETED SUCCESSFULLY!
echo ============================================
echo.
echo   Desktop installer:
echo   storage\app\desktop-dist\Novaskol-Setup-1.0.5-x64.exe
echo.
echo   Connecte installer:
echo   storage\app\desktop-connecte-dist\Novaskol-Connecte-Setup-0.2.0-x64.exe
echo.
echo   Both applications are ready for testing!
echo.

exit /b 0
