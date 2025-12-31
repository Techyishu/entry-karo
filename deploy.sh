#!/bin/bash

# Entry Karo - Deployment Script for Hostinger
# This script automates the deployment process

echo "🚀 Starting Entry Karo Deployment..."
echo "=================================="

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Function to print success message
success() {
    echo -e "${GREEN}✓ $1${NC}"
}

# Function to print warning message
warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

# Function to print error message
error() {
    echo -e "${RED}✗ $1${NC}"
}

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    error "Error: artisan file not found. Please run this script from the Laravel root directory."
    exit 1
fi

# Put application in maintenance mode
echo ""
echo "� Enabling maintenance mode..."
php artisan down || warning "Could not enable maintenance mode"
success "Maintenance mode enabled"

# Pull latest changes from Git
echo ""
echo "� Pulling latest changes from Git..."
git pull origin main
if [ $? -eq 0 ]; then
    success "Git pull successful"
else
    error "Git pull failed"
    php artisan up
    exit 1
fi

# Install/Update Composer dependencies
echo ""
echo "� Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev
if [ $? -eq 0 ]; then
    success "Composer dependencies installed"
else
    error "Composer install failed"
    php artisan up
    exit 1
fi

# Install/Update NPM dependencies and build assets (if Node.js is available)
if command -v npm &> /dev/null; then
    echo ""
    echo "📦 Installing NPM dependencies..."
    npm install
    
    echo ""
    echo "🔨 Building assets..."
    npm run build
    success "Assets built successfully"
else
    warning "Node.js not found, skipping npm install and build"
fi

# Run database migrations
echo ""
echo "🗄️  Running database migrations..."
php artisan migrate --force
if [ $? -eq 0 ]; then
    success "Migrations completed"
else
    warning "Migrations failed or no new migrations to run"
fi

# Clear and optimize cache
echo ""
echo "🧹 Clearing caches..."
php artisan optimize:clear
success "Cache cleared"

echo ""
echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
success "Application optimized"

# Ensure storage link exists
echo ""
echo "🔗 Ensuring storage link..."
php artisan storage:link 2>/dev/null || warning "Storage link already exists or failed"

# Set proper permissions
echo ""
echo "🔐 Setting permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage
success "Permissions set"

# Bring application back online
echo ""
echo "✅ Disabling maintenance mode..."
php artisan up
success "Application is now live!"

echo ""
echo "=================================="
echo -e "${GREEN}🎉 Deployment completed successfully!${NC}"
echo "=================================="
echo ""
echo "Next steps:"
echo "1. Visit your website to verify everything works"
echo "2. Check storage/logs/laravel.log for any errors"
echo "3. Test critical functionality (login, registration, etc.)"
echo ""
