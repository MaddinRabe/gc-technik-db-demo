#!/bin/bash
set -e

# Render uses PORT env, default to 10000
PORT="${PORT:-10000}"

# Change Apache to listen on Render's PORT
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT}/" /etc/apache2/sites-available/000-default.conf

# Create persistent data directory
mkdir -p /var/www/html/wp-content/database
mkdir -p /var/www/html/wp-content/uploads
chown -R www-data:www-data /var/www/html/wp-content

# Run the WordPress setup
/usr/local/bin/setup.sh

# Call the original WordPress entrypoint
exec docker-entrypoint.sh "$@"
