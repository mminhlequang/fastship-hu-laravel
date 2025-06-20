<?php

namespace App\Helper;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class FileHelper
{
    public static function uploadAndResizeToSpaces($image, $width = 1349, $height = null)
    {
        if (empty($image)) return null;

        $folder = 'images/stores/';
        $fileExt = 'webp';
        $timestamp = Carbon::now()->format('YmdHis');
        $filename = Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME));
        $filePath = $folder . $timestamp . '-' . $filename . '.' . $fileExt;

        // Resize và convert to webp
        $resizedImage = Image::make($image->getRealPath())
            ->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->encode($fileExt, 90);

        // Upload lên DigitalOcean Spaces
        Storage::disk('spaces')->put($filePath, (string) $resizedImage, 'public');

        // Trả về link CDN
        return rtrim(env('DO_SPACES_CDN_ENDPOINT'), '/') . '/' . ltrim($filePath, '/');
    }
}
