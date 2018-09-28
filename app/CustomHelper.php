<?php

namespace App;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\UnauthorizedException;

class CustomHelper {

    /**
     *
     *
     */
    public static function generate_image_and_ocr_image_to_text($photo)
    {
        $current_date = now();
        dd($current_date);
        if(!empty($photo))
        {
            if (file_exists(Storage::disk('public')->path($current_date . ".jpg")))
            {
                unlink(Storage::disk('public')->path($current_date . ".jpg"));
            }
            $img = str_replace('data:image/*;charset=utf-8;base64,', '', $photo);
            $data = base64_decode($img);
            Storage::disk('public')->put($current_date.".jpg", $data);
        }

        // converted image to OCR text



        return collect([
            'code' => '200',
            'message'=> "Profile updated successfully",
            // 'ocr_generated_text' =>
        ]);
    }

}




