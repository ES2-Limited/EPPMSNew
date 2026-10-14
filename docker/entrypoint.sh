#!/bin/sh
set -e

# Ensure all storage subdirectories exist
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/app/public/branding
mkdir -p /var/www/html/storage/app/public/signature_files
mkdir -p /var/www/html/database

# Check if SQLite DB file exists, if not touch it
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
fi

# Ensure storage symlink exists
php /var/www/html/artisan storage:link --force || true

# Ensure full read/write permissions for web server
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database /var/www/html/public
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
umask 0000

# Ensure valid APP_KEY exists
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    export APP_KEY="base64:gMRqqhW02cLJKiLvsHHKc4W+BzXtje3ckmX4yOLo2Hg="
fi

echo "Loaded PDO drivers:"
php -r 'echo implode(", ", PDO::getAvailableDrivers()) . PHP_EOL;'


# Run migrations and seed default data if database is fresh
php /var/www/html/artisan migrate --force --isolated || true
php /var/www/html/artisan db:seed --force || true

# Warm caches
php /var/www/html/artisan config:cache || true
php /var/www/html/artisan route:cache || true
php /var/www/html/artisan view:cache || true

echo "Starting Supervisor (Nginx + PHP-FPM)..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

