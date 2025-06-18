<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Exception;

class ImageHelper
{
    /**
     * Upload and process image with error handling
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @param string $path
     * @param string $prefix
     * @param int $width
     * @param int $height
     * @return string|null
     */
    public static function uploadImage($image, $path = 'books', $prefix = 'book_', $width = 600, $height = 800)
    {
        try {
            if (!$image || !$image->isValid()) {
                Log::error('Invalid image provided for upload. Image is null or invalid.');
                return null;
            }

            // Log image information for debugging
            Log::info('Uploading image: ' . $image->getClientOriginalName() . ', size: ' . $image->getSize() . ', mime: ' . $image->getMimeType());

            // Validate file type
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
            if (!in_array($image->getMimeType(), $allowedMimes)) {
                Log::error('Invalid image type: ' . $image->getMimeType());
                return null;
            }

            // Create unique filename
            $filename = $prefix . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();

            // Ensure storage directory exists
            $storagePath = "public/{$path}";
            if (!Storage::exists($storagePath)) {
                Storage::makeDirectory($storagePath);
                Log::info("Created directory: {$storagePath}");
            }

            try {
                // Initialize ImageManager with GD driver
                $manager = new ImageManager(new Driver());

                // Process image with Intervention Image
                $img = $manager->read($image->getRealPath());

                // Resize while maintaining aspect ratio
                $img->scale(width: $width, height: $height);

                // Save processed image to storage
                $outputPath = storage_path('app/' . $storagePath . '/' . $filename);
                $img->toJpeg(80)->save($outputPath);

                Log::info("Image saved successfully to: {$outputPath}");
            } catch (Exception $e) {
                // Fallback to direct file storage if image processing fails
                Log::warning('Image processing failed, using direct storage: ' . $e->getMessage());
                $image->storeAs($storagePath, $filename);
            }

            return "{$path}/" . $filename;
        } catch (Exception $e) {
            Log::error('Image upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete image from storage with error handling
     *
     * @param string $path
     * @return bool
     */
    public static function deleteImage($path)
    {
        if (!$path) {
            return false;
        }

        try {
            // Get the correct path for Storage facade
            $storagePath = 'public/' . $path;

            if (Storage::exists($storagePath)) {
                return Storage::delete($storagePath);
            }

            return false;
        } catch (Exception $e) {
            Log::error('Image deletion failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get default image if no image exists
     *
     * @param string|null $path
     * @param string $type
     * @return string
     */
    public static function getImage($path, $type = 'book')
    {
        if ($path && Storage::exists('public/' . $path)) {
            return asset('storage/' . $path);
        }

        // Return appropriate default image
        switch ($type) {
            case 'book':
                return asset('images/default-book.jpg');
            case 'author':
                return asset('images/default-author.jpg');
            case 'user':
                return asset('images/default-user.jpg');
            default:
                return asset('images/default.jpg');
        }
    }

    /**
     * Check if file exists
     *
     * @param string $path
     * @return bool
     */
    public static function exists($path)
    {
        if (!$path) {
            return false;
        }

        $storagePath = 'public/' . $path;
        return Storage::exists($storagePath);
    }
}
