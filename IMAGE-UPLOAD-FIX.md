# Image Upload Troubleshooting Guide

If you're experiencing issues with image uploads in the E-Book Management System, follow these steps to diagnose and fix the problems:

## Common Issues and Solutions

### 1. Storage Directory Permissions

Make sure the storage directory has proper permissions:

```bash
# For Linux/Mac environments
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# For Windows, ensure the web server has write access to these directories
```

### 2. Storage Symbolic Link

The application uses Laravel's storage linking to access uploaded files. If images aren't displaying, ensure the symbolic link is created:

```bash
php artisan storage:link
```

### 3. Missing Directories

Make sure these directories exist in your storage:

```bash
storage/app/public/books
```

If they don't exist, create them:

```bash
mkdir -p storage/app/public/books
```

### 4. PHP Upload Limits

Check your PHP configuration to ensure upload limits are set appropriately:

-   Edit your `php.ini` file to increase upload limits:
    ```ini
    upload_max_filesize = 10M
    post_max_size = 10M
    max_execution_time = 30
    ```

### 5. Image Processing Extensions

Ensure that your PHP installation has the required extensions:

-   For GD library: `php_gd.dll` or `gd.so`
-   For Intervention/Image: `php_fileinfo.dll` or `fileinfo.so`

### 6. Debug Common Errors

#### CORS Issues

If you see CORS errors in your browser console, check your CORS configuration in `config/cors.php`.

#### File Permission Errors

Look for errors like "Permission denied" in your Laravel logs (`storage/logs/laravel.log`).

#### Server Limits

Your web server might have additional limits on file uploads. Check your Nginx or Apache configuration.

## Verifying Uploads Work

To verify that image uploads are working:

1. Inspect your browser's network tab while uploading to see if the request completes
2. Check the Laravel log file at `storage/logs/laravel.log` for any errors
3. Verify that files appear in your `storage/app/public/books` directory after upload

If issues persist, the system will fall back to using default images.
