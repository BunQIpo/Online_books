<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class ViteFixServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Check if we need to create a manifest.json in the public/build directory
        $viteManifestDir = public_path('build/.vite');
        $buildDir = public_path('build');

        if (File::exists($viteManifestDir . '/manifest.json') && !File::exists($buildDir . '/manifest.json')) {
            // Make sure the build directory exists
            if (!File::exists($buildDir)) {
                File::makeDirectory($buildDir, 0755, true);
            }

            // Copy the manifest file
            File::copy($viteManifestDir . '/manifest.json', $buildDir . '/manifest.json');
        }
    }
}
