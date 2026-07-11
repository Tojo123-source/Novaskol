@echo off
REM Build script for Novaskol Connecte Application
REM This script builds the Novaskol Connecte application into an executable installer

setlocal enabledelayedexpansion

REM Get the repository root directory
for %%I in ("%~dp0.") do set REPO_ROOT=%%~dpI
cd /d "%REPO_ROOT%"

echo.
echo ====================================
echo   Building Novaskol Connecte v0.2.0
echo ====================================
echo.

REM Check if Node.js is installed
where node >nul 2>nul
if errorlevel 1 (
    echo ERROR: Node.js is not installed or not in PATH
    echo Please install Node.js from https://nodejs.org/
    exit /b 1
)

REM Check if npm is installed
where npm.cmd >nul 2>nul
if errorlevel 1 (
    echo ERROR: npm is not installed or not in PATH
    exit /b 1
)

echo [1/4] Node.js version:
node --version

echo [2/4] npm version:
npm --version

echo.
echo [3/4] Installing dependencies...
cd /d "%REPO_ROOT%apps\novaskol-connecte-desktop"
call npm.cmd install
if errorlevel 1 (
    echo ERROR: Failed to install dependencies
    exit /b 1
)

echo.
echo [4/4] Building Electron application...
echo This may take several minutes...
echo.

call npm.cmd run dist
if errorlevel 1 (
    echo ERROR: Failed to build application
    exit /b 1
)

echo.
echo ====================================
echo   Build completed successfully!
echo ====================================
echo.
echo The installer is located at:
echo   storage\app\desktop-connecte-dist\
echo.
echo Look for: Novaskol-Connecte-Setup-0.2.0-x64.exe
echo.

endlocal
