<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadHelper
{
    public static function uploadImage(UploadedFile $file, string $folder = 'image'): string
    {
        $folder = $folder . '/' . date('Y/m');
        $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($folder, $filename);
    }

    public static function deleteFile(?string $path): void
    {
        if (!empty($path) && Storage::exists($path)) {
            Storage::delete($path);
        }
    }
}
