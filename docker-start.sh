#!/bin/bash
set -e

echo "=== Starting Aplikasi Parkir ==="

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

# Cache config, routes, views
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage symlink
echo "Creating storage symlink..."
php artisan storage:link || true

# Run database migrations
echo "Running migrations..."
php artisan migrate --force

echo "=== Starting PHP server on port $PORT ==="
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
