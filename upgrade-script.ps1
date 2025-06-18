# PowerShell script to perform Laravel 11 upgrade tasks

Write-Host "Applying Laravel 11 specific changes..." -ForegroundColor Green

# Clear all caches
php artisan clear-compiled
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Republish vendor files if needed
php artisan vendor:publish --tag=laravel-assets --ansi --force

# Update app config files based on Laravel 11 stubs
Write-Host "You may need to manually update config files based on Laravel 11 defaults" -ForegroundColor Yellow

# Rebuild frontend assets
npm run build

# Check for any compatibility issues
composer validate

Write-Host "Upgrade process completed. Please check your application for any errors." -ForegroundColor Green
Write-Host "Refer to UPGRADE-NOTES.md for more information on Laravel 11 changes." -ForegroundColor Cyan
