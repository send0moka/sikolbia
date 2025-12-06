FROM php:8.3-fpm

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev \
    zip unzip libzip-dev \
    && docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-install zip pdo_mysql mbstring exif pcntl bcmath gd opcache \
    && pecl install redis && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy dependencies files first (better caching)
COPY composer.json composer.lock package*.json ./
COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

# Configure git safe directory
RUN git config --global --add safe.directory /var/www/html

# Install dependencies
RUN composer config --global process-timeout 2000 && \
    composer install --no-scripts --no-autoloader --optimize-autoloader --prefer-dist
RUN npm ci

# Copy application
COPY . .

# Create cache directories for build phase (build uses file cache, not redis)
RUN mkdir -p bootstrap/cache storage/logs && \
    chmod -R 777 bootstrap/cache storage/logs storage

# Generate autoloader & build assets (skip artisan commands that need Redis)
RUN composer dump-autoload --optimize || true
RUN npm run build

# Storage link & permissions
RUN rm -rf public/storage && ln -s /var/www/html/storage/app/public /var/www/html/public/storage
RUN chmod +x copy_assets.sh && ./copy_assets.sh || true
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configure PHP-FPM
RUN echo "pm = dynamic" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.max_children = 50" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.start_servers = 10" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.min_spare_servers = 5" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.max_spare_servers = 20" >> /usr/local/etc/php-fpm.d/www.conf

# Copy and setup entrypoint
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh
ENTRYPOINT ["docker-entrypoint.sh"]

EXPOSE 9000
CMD ["php-fpm"]
