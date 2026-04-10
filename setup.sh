#!/bin/bash
set -e

WP_DIR="/var/www/html"

# Wait for WordPress files to be available
if [ ! -f "$WP_DIR/wp-settings.php" ]; then
    echo "WordPress files not yet available, skipping setup..."
    exit 0
fi

# Force fresh install on each deploy (free tier has no persistent storage anyway)
echo "Cleaning old database for fresh install..."
rm -f "$WP_DIR/wp-content/database/wordpress.db"
rm -f "$WP_DIR/wp-config.php"

# Create wp-config.php for SQLite if it doesn't exist
if [ ! -f "$WP_DIR/wp-config.php" ]; then
    echo "Creating wp-config.php for SQLite..."

    cat > "$WP_DIR/wp-config.php" << 'WPCONFIG'
<?php
define( 'DB_NAME', 'wordpress' );
define( 'DB_USER', '' );
define( 'DB_PASSWORD', '' );
define( 'DB_HOST', '' );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

// SQLite database integration
define( 'DB_DIR', ABSPATH . 'wp-content/database/' );
define( 'DB_FILE', 'wordpress.db' );

// Auth keys — generated for this demo instance
define( 'AUTH_KEY',         'gc-demo-aK8#mP2$nQ5&rT9' );
define( 'SECURE_AUTH_KEY',  'gc-demo-bL3@oR7%sU1^vX4' );
define( 'LOGGED_IN_KEY',    'gc-demo-cM6!pS0&tV2*wY5' );
define( 'NONCE_KEY',        'gc-demo-dN9#qT3$uW6&xZ8' );
define( 'AUTH_SALT',        'gc-demo-eO1@rU4%vX7^yA0' );
define( 'SECURE_AUTH_SALT', 'gc-demo-fP2!sV5&wY8*zB1' );
define( 'LOGGED_IN_SALT',   'gc-demo-gQ3#tW6$xZ9&aC2' );
define( 'NONCE_SALT',       'gc-demo-hR4@uX7%yA0^bD3' );

$table_prefix = 'wp_';

define( 'WP_DEBUG', false );
define( 'WP_DEBUG_LOG', false );
define( 'DISALLOW_FILE_EDIT', true );

// Allow Render's URL
if ( isset( $_SERVER['HTTP_HOST'] ) ) {
    define( 'WP_HOME', 'https://' . $_SERVER['HTTP_HOST'] );
    define( 'WP_SITEURL', 'https://' . $_SERVER['HTTP_HOST'] );
}

// Force HTTPS behind Render's proxy
if ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) {
    $_SERVER['HTTPS'] = 'on';
}

if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

require_once ABSPATH . 'wp-settings.php';
WPCONFIG

    echo "wp-config.php created."
fi

# Copy SQLite db.php drop-in if not present
DROPIN_SRC="$WP_DIR/wp-content/plugins/sqlite-database-integration/db.copy"
DROPIN_DST="$WP_DIR/wp-content/db.php"

if [ ! -f "$DROPIN_DST" ] && [ -f "$DROPIN_SRC" ]; then
    echo "Installing SQLite drop-in..."
    cp "$DROPIN_SRC" "$DROPIN_DST"
    # Fix the path in the drop-in
    sed -i "s|{SQLITE_IMPLEMENTATION_FOLDER_PATH}|$WP_DIR/wp-content/plugins/sqlite-database-integration|g" "$DROPIN_DST"
    sed -i "s|{SQLITE_PLUGIN}|sqlite-database-integration/load.php|g" "$DROPIN_DST"
    echo "SQLite drop-in installed."
fi

# Ensure database directory exists and is writable
mkdir -p "$WP_DIR/wp-content/database"
chown -R www-data:www-data "$WP_DIR/wp-content/database"
chmod -R 755 "$WP_DIR/wp-content/database"

chown -R www-data:www-data "$WP_DIR/wp-content"

echo "Setup complete."
