#!/usr/bin/env bash
# Render Build Script for Laravel

set -e

echo "🚀 Starting build process..."

# Install dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Run database migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Create storage symlink
echo "🔗 Creating storage symlink..."
php artisan storage:link || true

# Clear and cache config
echo "⚙️  Optimizing configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build frontend assets if needed
if [ -f "package.json" ]; then
    echo "🎨 Building frontend assets..."
    npm install
    npm run build
fi

echo "✅ Build completed successfully!"
