<?php

// Quick SQLite database schema checker
$dbPath = 'g:\\wamp64\\www\\novaskol-laravel\\storage\\novaskol.sqlite';

if (file_exists($dbPath)) {
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if utilisateurs table exists
    $result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='utilisateurs'");
    if ($result->fetch()) {
        echo "Table 'utilisateurs' exists\n";
        
        // Get table info
        $columns = $db->query("PRAGMA table_info(utilisateurs)")->fetchAll(PDO::FETCH_ASSOC);
        echo "Current columns:\n";
        foreach ($columns as $col) {
            echo "  - {$col['name']} ({$col['type']})\n";
        }
        
        // Check if last_activity exists
        $hasLastActivity = false;
        foreach ($columns as $col) {
            if ($col['name'] === 'last_activity') {
                $hasLastActivity = true;
                break;
            }
        }
        
        if (!$hasLastActivity) {
            echo "\n⚠️  Missing column: last_activity\n";
        } else {
            echo "\n✓ Column 'last_activity' exists\n";
        }
    } else {
        echo "Table 'utilisateurs' does NOT exist\n";
    }
} else {
    echo "Database file not found at: $dbPath\n";
}
?>
