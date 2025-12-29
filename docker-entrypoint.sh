#!/bin/bash

# Exit on error
set -e

echo "🚀 Starting container startup script..."

# Run migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Create storage symlink
echo "🔗 Creating storage symlink..."
php artisan storage:link || true

# Create super admin (optional - controlled by env var)
if [ "$CREATE_ADMIN" = "true" ]; then
    echo "👤 Creating super admin user..."
    php artisan db:seed --class=SuperAdminSeeder --force
fi

# Create test customer (optional - controlled by env var)
if [ "$CREATE_TEST_CUSTOMER" = "true" ]; then
    echo "👤 Creating test customer..."
    php artisan db:seed --class=TestCustomerSeeder --force
fi

# Clear and cache config
echo "⚙️  Optimizing configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Startup tasks completed. Starting Apache..."

# Execute the main container command (apache2-foreground)
exec "$@"
