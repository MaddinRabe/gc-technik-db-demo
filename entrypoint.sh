#!/bin/bash
set -e

# Render uses PORT env, default to 10000
PORT="${PORT:-10000}"

# Change Apache to listen on Render's PORT
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT}/" /etc/apache2/sites-available/000-default.conf

# Unset MySQL env vars so docker-entrypoint doesn't create a MySQL wp-config
unset WORDPRESS_DB_HOST
unset WORDPRESS_DB_NAME
unset WORDPRESS_DB_USER
unset WORDPRESS_DB_PASSWORD

# Let WordPress docker-entrypoint copy WP files to /var/www/html
# We call it with a dummy command first, then override config
docker-entrypoint.sh apache2 -v > /dev/null 2>&1 || true

# Now run our setup (creates wp-config.php for SQLite + installs drop-in)
/usr/local/bin/setup.sh

# Create persistent data directory
mkdir -p /var/www/html/wp-content/database
mkdir -p /var/www/html/wp-content/uploads
chown -R www-data:www-data /var/www/html/wp-content

# Start Apache directly (not via docker-entrypoint again)
exec apache2-foreground
