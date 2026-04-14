FROM dunglas/frankenphp:latest-php8.2

# Install system dependencies and Node.js
RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install required PHP extensions
RUN install-php-extensions \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    opcache \
    redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer files and install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy package files and install/build frontend assets
COPY package.json package-lock.json ./
RUN npm ci

COPY . .

# Run npm build
RUN npm run build

# Run composer scripts now that full app is present
RUN composer run-script post-autoload-dump --no-interaction || true

# Copy Caddyfile
COPY Caddyfile /etc/caddy/Caddyfile

# Copy and configure the entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Set permissions for Laravel storage and bootstrap cache
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache

EXPOSE 8080

# Use the entrypoint to bootstrap Laravel then exec FrankenPHP in the foreground
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
