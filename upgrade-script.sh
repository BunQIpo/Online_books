#!/bin/bash
# Script to perform Laravel 11 upgrade tasks

echo "Applying Laravel 11 specific changes..."

# Clear all caches
php artisan clear-compiled
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Republish vendor files if needed
php artisan vendor:publish --tag=laravel-assets --ansi --force

# Update app config files based on Laravel 11 stubs
echo "You may need to manually update config files based on Laravel 11 defaults"

# Rebuild frontend assets
npm run build

# Check for any compatibility issues
composer validate

echo "Upgrade process completed. Please check your application for any errors."
echo "Refer to UPGRADE-NOTES.md for more information on Laravel 11 changes."
