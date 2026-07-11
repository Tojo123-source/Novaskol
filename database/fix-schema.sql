-- Add last_activity column to utilisateurs table if it doesn't exist
-- This is a compatibility fix for SQLite databases that were created before this column was needed

PRAGMA table_info(utilisateurs);
