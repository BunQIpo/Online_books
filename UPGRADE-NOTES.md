# Laravel 11 Upgrade Notes

This document contains important information about the upgrade from Laravel 9 to Laravel 11 for this project.

## Key Changes in Laravel 11

### Directory Structure Changes

-   Laravel 11 has a more streamlined directory structure
-   The `app/Http/Controllers` directory structure and namespace may need adjustments
-   The `app/Models` directory is now the default location for all models

### Configuration Changes

-   Many configuration files have been updated in Laravel 11
-   Review and update your `config/*.php` files as needed

### Authentication Changes

-   Laravel 11 has updated the authentication system
-   Ensure your user model and authentication controllers are updated

### Database Changes

-   Review your migrations for any incompatibilities
-   Check model factories for changes in syntax

### Breaking Changes

1. **Minimum PHP Version**: Laravel 11 requires PHP 8.2+
2. **Removed Features**:
    - Several legacy helpers and facades have been removed
    - Some deprecated Eloquent methods have been removed
    - Blade syntax changes for some directives

## Next Steps

After the update, you should:

1. Run all tests to ensure functionality
2. Check for any deprecation warnings
3. Update any custom code that may be using deprecated Laravel 9 features

## Resources

-   [Laravel 11 Documentation](https://laravel.com/docs/11.x)
-   [Laravel 11 Upgrade Guide](https://laravel.com/docs/11.x/upgrade)
-   [Laravel 11 Release Notes](https://github.com/laravel/framework/releases)
