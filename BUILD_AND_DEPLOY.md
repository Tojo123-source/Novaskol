# Novaskol Applications - Build & Deploy Guide

## Fixed Issues

### Novaskol Desktop v1.0.5
**Problem**: Application crashed on dashboard with error:
```
SQLSTATE[HY000]: General error: 1 no such column: last_activity
```

**Root Cause**: The `utilisateurs` table was missing the `last_activity` column that the DashboardController tries to update on every page load.

**Solution**: Created Laravel migration `2026_05_18_000000_create_core_tables.php` that:
- Creates the `utilisateurs` table if it doesn't exist (for fresh installations)
- Adds the `last_activity` column to existing `utilisateurs` tables (for upgrades)
- Handles both SQLite and MySQL environments safely

**Files Modified**:
- `database/migrations/2026_05_18_000000_create_core_tables.php` (NEW)
- `storage/app/distribution/novaskol-app-latest/database/migrations/2026_05_18_000000_create_core_tables.php` (NEW)
- `storage/app/distribution/novaskol-app-20260515_032740/database/migrations/2026_05_18_000000_create_core_tables.php` (NEW)

### Novaskol Connecte v0.2.0
**Status**: Fixes inherited from Desktop
- Also uses same Laravel codebase with database migration fix
- Includes pairing/sync functionality for offline-first operation

## Prerequisites

Before building, ensure you have:

1. **Node.js** (v18 or higher)
   - Download: https://nodejs.org/
   - Verify: `node --version`

2. **npm** (comes with Node.js)
   - Verify: `npm --version`

3. **Git** (for version control)
   - Download: https://git-scm.com/

## Building the Applications

### Option 1: Automated Build Scripts (Recommended)

#### Build Novaskol Desktop
```batch
Build-Desktop.cmd
```

#### Build Novaskol Connecte
```batch
Build-Connecte.cmd
```

Output files:
- Desktop installer: `storage/app/desktop-dist/Novaskol-Setup-1.0.5-x64.exe`
- Connecte installer: `storage/app/desktop-connecte-dist/Novaskol-Connecte-Setup-0.2.0-x64.exe`

### Option 2: Manual Build

#### Build Desktop
```batch
cd desktop
npm install
npm run dist
REM Output: ../storage/app/desktop-dist/
```

#### Build Connecte
```batch
cd apps/novaskol-connecte-desktop
npm install
npm run dist
REM Output: ../../storage/app/desktop-connecte-dist/
```

## Testing the Applications

### Test Novaskol Desktop

1. Run the installer: `Novaskol-Setup-1.0.5-x64.exe`
2. Follow the installation wizard
3. After installation, launch Novaskol Desktop
4. The application will:
   - Initialize the local SQLite database
   - Run all pending migrations (including the new core tables migration)
   - Create default tables with all required columns
5. Login with test credentials (configured during installation)
6. Verify dashboard loads without errors

### Test Novaskol Connecte

1. Run the installer: `Novaskol-Connecte-Setup-0.2.0-x64.exe`
2. Follow the installation wizard
3. After installation, launch Novaskol Connecte
4. Follow pairing setup to connect to a Novaskol Desktop instance
5. Test offline functionality and sync when connected

## Technical Details

### Database Schema
The new migration creates/updates the `utilisateurs` table with:
- `id` (Primary Key)
- `nom` (string, 100)
- `email` (string, 100, unique)
- `mot_de_passe` (string, 255) - password hash
- `avatar` (string, 255, nullable) - user avatar path
- `role` (enum: admin, enseignant, staff, parent)
- `cree_le` (datetime) - creation timestamp
- `last_activity` (datetime, nullable) - tracks last user activity
- `created_at`, `updated_at` (timestamps for Laravel)

### How It Works
1. When the Electron app starts, it runs the PowerShell startup script
2. The startup script runs `php artisan migrate --force`
3. Laravel executes all pending migrations in order
4. The core tables migration runs first (timestamp: 000000)
5. If the `utilisateurs` table doesn't exist, it's created
6. If it exists but `last_activity` is missing, the column is added
7. The application then starts the PHP development server
8. The Electron window loads the application UI

### Distribution Sync
The migration is also deployed to the distribution folders used when building the Electron apps:
- `storage/app/distribution/novaskol-app-latest/`
- `storage/app/distribution/novaskol-app-20260515_032740/`

When the Electron app is built, it packages the distribution folder as the seed application, so the migration is included in the final installer.

## Troubleshooting

### "The server stopped responding"
- Ensure the local port (8001 for Desktop, 8002 for Connecte) is not in use
- Check the logs in `storage/logs/`
- Verify PHP is correctly installed

### Database errors
- Check `storage/logs/novaskol-migrate.log` for migration errors
- Verify the SQLite database is writable: `storage/novaskol.sqlite`
- Check `storage/logs/novaskol-bootstrap.log` for startup issues

### Build fails with "Cannot find module"
- Delete `node_modules` and `package-lock.json`
- Run `npm install` again
- Ensure you have at least 2GB free disk space

## Support

For issues with the build or deployment:
1. Check the logs in `storage/logs/`
2. Verify all prerequisites are installed and up-to-date
3. Try a clean rebuild: delete `node_modules` and `package-lock.json`, then rebuild

## Version Information

- **Novaskol Desktop**: v1.0.5
- **Novaskol Connecte**: v0.2.0
- **Electron**: v37.2.1
- **PHP**: 8.2
- **Laravel**: 12.x
- **Database**: SQLite (Desktop/Connecte) or MySQL (Production)

---

**Last Updated**: 2026-05-18
**Build Status**: Ready for distribution
