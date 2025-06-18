# How to Increase PHP Upload File Size Limits

You're encountering an error because your PHP configuration has a file upload size limit of 2MB (2048 KiB), but you're trying to upload a larger file. Here's how to fix this issue:

## Option 1: Update your php.ini file

1. Open your `php.ini` file. You can find its location by checking the phpinfo page we added at `/phpinfo`.

2. Change the following directives:

```
; Increase maximum upload size
upload_max_filesize = 20M

; Increase post data size (should be larger than upload_max_filesize)
post_max_size = 21M

; Optional: Increase memory limit if needed
memory_limit = 128M
```

3. Save the file and restart your web server.

## Option 2: Create a .htaccess file (for Apache)

If you don't have access to php.ini, you can create or edit a `.htaccess` file in your project root:

```
# PHP Values
php_value upload_max_filesize 20M
php_value post_max_size 21M
php_value max_execution_time 300
php_value max_input_time 300
```

## Option 3: Create a .user.ini file (for PHP-FPM or FastCGI)

If you're using PHP-FPM or FastCGI, create a `.user.ini` file in your project root:

```
upload_max_filesize = 20M
post_max_size = 21M
max_execution_time = 300
max_input_time = 300
```

## Option 4: Update Laravel's validation only

While this won't fix the PHP limitation, you can limit file uploads in your Laravel validation to match your PHP settings:

```php
$request->validate([
    'file' => 'required|file|max:2048|mimes:pdf,epub,doc,docx,txt'
]);
```

## Testing Your Configuration

After making changes, visit the `/phpinfo` route we added to verify that your settings have been updated.

## Note for Shared Hosting

If you're on shared hosting, you might need to contact your hosting provider to increase these limits if the above methods don't work.
