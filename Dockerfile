FROM php:8.3-fpm

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-install zip pdo_mysql mbstring exif pcntl bcmath gd opcache \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get update \
    && apt-get install -y nodejs \
    && node --version \
    && npm --version \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first for better layer caching
COPY composer.json composer.lock ./

# Copy custom php.ini for performance
COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

# Configure git safe directory
RUN git config --global --add safe.directory /var/www/html

# Copy composer files first for better caching
COPY composer.json composer.lock ./

# Configure composer for better reliability
RUN composer config --global process-timeout 2000 && \
    composer config --global repositories.packagist composer https://packagist.org && \
    composer install --no-scripts --no-autoloader --no-interaction --prefer-dist

# Copy package.json if exists
COPY package*.json ./

# Copy application code (needed for artisan to work)
COPY . .

# Generate autoloader and finalize composer
RUN composer dump-autoload --optimize

# Install npm dependencies
RUN if [ -f package.json ]; then \
      echo "Installing npm dependencies..." && \
      npm ci --only=production && \
      npm cache clean --force; \
    else \
      echo "No package.json found, skipping npm install"; \
    fi

# Build assets if needed
RUN if [ -f package.json ]; then \
      echo "Building assets..." && \
      npm run build; \
    else \
      echo "No package.json found, skipping npm build"; \
    fi

# Create storage link and set permissions
RUN rm -rf public/storage && ln -s /var/www/html/storage/app/public /var/www/html/public/storage

# Copy Livewire and Flux assets
RUN chmod +x copy_assets.sh && ./copy_assets.sh

# Make optimization script executable
RUN chmod +x optimize_laravel.sh

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configure PHP-FPM for better performance
RUN echo "pm = dynamic" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.max_children = 50" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.start_servers = 10" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.min_spare_servers = 5" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.max_spare_servers = 20" >> /usr/local/etc/php-fpm.d/www.conf

EXPOSE 9000
CMD ["php-fpm"]