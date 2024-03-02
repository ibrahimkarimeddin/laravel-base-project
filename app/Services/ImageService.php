<?php

namespace App\Services;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageService
{
    public static function upload_image(UploadedFile $new_image, $upload_location = '')
    {
        $image_path_without_public = '/images/' . $upload_location . '/';
        $image_path = public_path() . '/images/' . $upload_location . '/';
        $image_name = $upload_location . '_' . Str::uuid() . '.' . $new_image->getClientOriginalExtension();
        $new_image->move($image_path, $image_name);

        return $image_path_without_public . $image_name;
    }

    public static function update_image(UploadedFile $new_image, $old_image_name, $upload_location = '')
    {
        $new_image_path_without_public = '/images/' . $upload_location . '/';
        $new_image_path = public_path() . '/images/' . $upload_location . '/';
        $new_image_name = $upload_location . '_' . Str::uuid() . '.' . $new_image->getClientOriginalExtension();
        $new_image->move($new_image_path, $new_image_name);
        try {
            unlink(public_path() . $old_image_name);

            return $new_image_path_without_public . $new_image_name;
        } catch (Exception $e) {
            return $new_image_path_without_public . $new_image_name;
        }
    }

    public static function delete_image($image)
    {
        try {
            unlink(public_path() . $image);

            return true;
        } catch (Exception $e) {
            return $e;
        }
    }
}
