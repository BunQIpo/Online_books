<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    /**
     * Upload an image
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'nullable|string|in:book,author,user',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        // Get the image type
        $type = $request->input('type', 'book');

        // Define path and prefix based on type
        $path = $type . 's';
        $prefix = $type . '_';

        // Upload the image
        try {
            $imagePath = ImageHelper::uploadImage($request->file('image'), $path, $prefix);

            return response()->json([
                'success' => true,
                'path' => $imagePath,
                'url' => asset($imagePath)
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to upload image: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete an image
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $path = $request->input('path');

        if (ImageHelper::deleteImage($path)) {
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Image not found or could not be deleted'], 404);
    }
}
