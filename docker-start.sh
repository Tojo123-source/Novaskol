#!/bin/bash
PORT=${PORT:-80}
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80>/:${PORT}>/" /etc/apache2/sites-available/000-default.conf

if [ ! -f /var/www/html/storage/novaskol.sqlite ]; then
    echo "First deploy: copying initial SQLite database to persistent volume..."
    cp /var/www/html/storage.bak/novaskol.sqlite /var/www/html/storage/novaskol.sqlite 2>/dev/null || true
    chown -R www-data:www-data /var/www/html/storage
fi

apache2-foreground
