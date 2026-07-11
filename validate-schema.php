#!/usr/bin/env php
<?php

/**
 * Novaskol Database Validation Script
 * Validates that the database schema fixes have been applied correctly
 * 
 * Usage: php validate-schema.php
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "\n";
echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║     Novaskol Database Schema Validation Report           ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n";
echo "\n";

$dbPath = __DIR__ . '/storage/novaskol.sqlite';
$errors = [];
$warnings = [];
$successes = [];

// Check if database file exists
if (!file_exists($dbPath)) {
    echo "DATABASE STATUS: Not yet created (will be created on first run)\n";
    echo "This is normal for fresh installations.\n";
    echo "\nTo test:\n";
    echo "1. Run 'Build-Desktop.cmd' to build the Electron app\n";
    echo "2. Install and run Novaskol Desktop\n";
    echo "3. The database will be created during first run\n";
    echo "4. Run this script again after first run\n";
    exit(0);
}

// Open database
try {
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $successes[] = "Database file exists and is accessible";
} catch (Exception $e) {
    echo "❌ ERROR: Cannot open database\n";
    echo "   {$e->getMessage()}\n";
    exit(1);
}

// Check utilisateurs table
echo "\n▶ Checking 'utilisateurs' table...\n";
try {
    $result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='utilisateurs'");
    if ($result->fetch()) {
        $successes[] = "Table 'utilisateurs' exists";
        
        // Get table columns
        $columns = $db->query("PRAGMA table_info(utilisateurs)")->fetchAll(PDO::FETCH_ASSOC);
        echo "  Columns found: " . count($columns) . "\n";
        
        $requiredColumns = [
            'id' => 'INTEGER',
            'nom' => 'TEXT',
            'email' => 'TEXT',
            'mot_de_passe' => 'TEXT',
            'role' => 'TEXT',
            'last_activity' => 'DATETIME',  // This is the critical one
        ];
        
        $columnMap = [];
        foreach ($columns as $col) {
            $columnMap[$col['name']] = $col['type'];
            printf("    ✓ %-20s (%s)\n", $col['name'], $col['type']);
        }
        
        // Verify critical columns
        echo "\n  Verifying critical columns:\n";
        $hasCriticalIssue = false;
        foreach ($requiredColumns as $colName => $expectedType) {
            if (!isset($columnMap[$colName])) {
                $errors[] = "CRITICAL: Column '$colName' is missing from 'utilisateurs' table";
                echo "    ❌ Missing: $colName\n";
                $hasCriticalIssue = true;
            } else {
                echo "    ✓ Found: $colName\n";
                if ($colName === 'last_activity') {
                    $successes[] = "Column 'last_activity' exists (SCHEMA FIX VERIFIED)";
                }
            }
        }
        
        if ($hasCriticalIssue) {
            echo "\n  ⚠️  CRITICAL ISSUE: The database is missing required columns!\n";
            echo "  This will cause the application to crash.\n";
            echo "\n  SOLUTION: Run the database migration\n";
            echo "  $ php artisan migrate --force\n";
        }
    } else {
        $errors[] = "Table 'utilisateurs' does not exist";
        echo "  ❌ Table 'utilisateurs' not found\n";
        echo "  The database appears to be uninitialized.\n";
    }
} catch (Exception $e) {
    $warnings[] = "Could not query utilisateurs table: {$e->getMessage()}";
    echo "  ⚠️  {$e->getMessage()}\n";
}

// Check for other critical tables
echo "\n▶ Checking other critical tables...\n";
$requiredTables = ['eleves', 'classes', 'permissions', 'revenus', 'depenses'];
foreach ($requiredTables as $tableName) {
    try {
        $result = $db->query("SELECT COUNT(*) as cnt FROM sqlite_master WHERE type='table' AND name='$tableName'");
        if ($result->fetch()['cnt'] > 0) {
            echo "  ✓ Table '$tableName' exists\n";
        } else {
            $warnings[] = "Table '$tableName' does not exist";
            echo "  ⚠️  Table '$tableName' not found (expected for fresh installs)\n";
        }
    } catch (Exception $e) {
        // Table doesn't exist - this is OK for fresh installs
    }
}

// Print summary
echo "\n";
echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║                      VALIDATION REPORT                   ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n";

if (!empty($successes)) {
    echo "\n✅ PASSED (" . count($successes) . "):\n";
    foreach ($successes as $msg) {
        echo "  • $msg\n";
    }
}

if (!empty($warnings)) {
    echo "\n⚠️  WARNINGS (" . count($warnings) . "):\n";
    foreach ($warnings as $msg) {
        echo "  • $msg\n";
    }
}

if (!empty($errors)) {
    echo "\n❌ ERRORS (" . count($errors) . "):\n";
    foreach ($errors as $msg) {
        echo "  • $msg\n";
    }
    echo "\nFIX REQUIRED: Run migrations\n";
    echo "  $ php artisan migrate --force\n";
    exit(1);
}

echo "\n";
if (empty($errors)) {
    echo "✅ All checks passed! Database schema is correct.\n";
    echo "The application should run without the 'last_activity' error.\n";
    exit(0);
} else {
    echo "❌ Schema validation failed. Please review the errors above.\n";
    exit(1);
}
?>
