FROM wordpress:6.7-php8.2-apache

# Persistent storage for WordPress data
ENV WORDPRESS_DB_HOST=localhost
ENV WORDPRESS_DB_NAME=wordpress
ENV WORDPRESS_DB_USER=root
ENV WORDPRESS_DB_PASSWORD=root

# Install SQLite extension (already included in this image)
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    unzip \
    wget \
    && rm -rf /var/lib/apt/lists/*

# Download and install SQLite Database Integration plugin
RUN wget -q https://downloads.wordpress.org/plugin/sqlite-database-integration.2.1.13.zip -O /tmp/sqlite.zip \
    && unzip -q /tmp/sqlite.zip -d /usr/src/wordpress/wp-content/plugins/ \
    && rm /tmp/sqlite.zip

# Copy our plugin
COPY gc-technik-db/ /usr/src/wordpress/wp-content/plugins/gc-technik-db/

# Setup script
COPY setup.sh /usr/local/bin/setup.sh
RUN chmod +x /usr/local/bin/setup.sh

# Custom entrypoint
COPY entrypoint.sh /usr/local/bin/custom-entrypoint.sh
RUN chmod +x /usr/local/bin/custom-entrypoint.sh

# Render uses PORT env var
EXPOSE 10000

ENTRYPOINT ["custom-entrypoint.sh"]
CMD ["apache2-foreground"]
