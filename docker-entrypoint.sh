#!/bin/sh
set -e

# Generate APP_KEY if not set (prevents Laravel from crashing on boot)
if [ -z "$APP_KEY" ]; then
    echo "WARNING: APP_KEY is not set. Generating a temporary key..."
    php artisan key:generate --force
fi

# Cache Laravel configuration, routes, and views for production performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run any pending migrations (optional — remove if you prefer manual migrations)
# php artisan migrate --force

# Hand off to FrankenPHP as PID 1
exec frankenphp run --config /etc/caddy/Caddyfile --adapter caddyfile
