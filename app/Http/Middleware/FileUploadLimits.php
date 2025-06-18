<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FileUploadLimits
{
    /**
     * Handle an incoming request and set PHP limits for file uploads.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */    public function handle(Request $request, Closure $next)
    {
        // Set PHP limits for file uploads
        ini_set('upload_max_filesize', '100M');
        ini_set('post_max_size', '102M');
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 600); // 10 minutes
        ini_set('max_input_time', 600); // 10 minutes

        // Set session timeout to be longer for file uploads
        if ($request->hasFile('pdf_file') || $request->hasFile('file')) {
            ini_set('session.gc_maxlifetime', 10800); // 3 hours
            config(['session.lifetime' => 180]); // 3 hours in minutes
        }

        return $next($request);
    }
}
