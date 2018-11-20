<?php

namespace App;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\UnauthorizedException;

class CustomHelper {

    /**
     *
     *
     */
    public static function base64ToImageAndOCRImageToText($photo)
    {
        $current_date = date("Y-m-d_H-i-s");
        //var_dump($current_date);
        if(!empty($photo))
        {
            // check image mime type; save base64 to image
//            if (file_exists(Storage::disk('public')->path($current_date . ".jpg")))
//            {
//                unlink(Storage::disk('public')->path($current_date . ".jpg"));
//            }

            //$img = str_replace('data:image/*;charset=utf-8;base64,', '', $photo);
            $img = str_replace('data:image/jpeg;base64', '', $photo);
            $img_file_name = $current_date.".jpg";
            $data = base64_decode($img);

            // save image to the storage
            $success=Storage::disk('public')->put($img_file_name, $data);

            // converted image to OCR text
            if ($success){

                $text_result = CustomHelper::ocrImageToText($img_file_name);

                if(!empty($text_result)){
                    return collect([
                        'code' => '200',
                        'message' => "success",
                        'ocr_generated_text' => $text_result
                    ]);
                }
                else{
                    return collect([
                        'code' => '404',
                        'message' => "Result is null",
                        'ocr_generated_text' => $text_result
                    ]);
                }
            }
        }


    }

    /**
     * @param $image_file: image file name
     * @return generated text content
     */
    public static function ocrImageToText($image_file)
    {
        // set storage name to be used
        $storage = Storage::disk(env('OCR_STORAGE'));

        // check if file exists
        if (file_exists(Storage::disk('public')->path($image_file)))
        {
            // get path of uploaded file from Local storage
            $get_file = $storage->url('public/' . $image_file);

            // get path of uploaded file from S3 storage
            // $get_file = $storage->url($image_file);

            // get file extenstion
            // $extension = pathinfo($image_file, PATHINFO_EXTENSION);

            // Generated Text file name
            $txt_file = $image_file.'.txt';

            // Calling Tesseract OCR Engine
            /**
            *   Tesseract command to recognise and return output (stdout) as array value from terminal
            *   $command = "tesseract " . $get_file . " stdout -l khm ";
            */

            // work well in Local but not in server
            // $command = "tesseract " . $get_file . " -l khm " . $storage->url('public/'.$img_file_name);

            // work well in server
            $command = "tesseract " . $get_file . " --tessdata-dir " . env('TESSDATA_PREFIX')
                            . " -l khm " . $get_file ;
            exec($command);

            //upload img and text file to S3 cd
//            $upload_to_s3 = Storage::disk('s3')->put($image_file, File::get($get_file), 'public');
//
////             delete img file after upload to S3
//            if($upload_to_s3  == true)
//            {
//                File::Delete($get_file);
//            }

            $read_file_content = File::get($storage->url('public/'. $txt_file));
            return $read_file_content;
        }
    }

}




