<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class KhmerOCREngine extends Controller
{
    function RecognitionEngine(Request $request)
    {
        $img_file_uploaded = $request->file('image_file');
        $extension = $img_file_uploaded->getClientOriginalExtension();

        //$img_file_name = $img_file_uploaded->getFilename().'.'.$extension;
        // file name only without extension
        $img_file_name = date('m-d-Y_H_i_s');

        // set storage name to be used
        $storage = Storage::disk(env('OCR_STORAGE'));
        // For Local storage
        $success_img_upload = $storage->put("public/".$img_file_name .'.'.$extension, File::get($img_file_uploaded));

        // For S3 Amazon storage
        // $success_img_upload = $storage->put($img_file_name .'.'.$extension, File::get($img_file_uploaded), 'public');

        if($success_img_upload == true)
        {
            // get path of uploaded file from Local storage
            $get_file = $storage->url('public/' . $img_file_name.'.'.$extension);

            // get path of uploaded file from S3 storage
            // $get_file = $storage->url($img_file_name.'.'.$extension);

            $txt_file = $img_file_name. '.txt';

            // Calling Tesseract OCR Engine
            /**
                // Tesseract command to recognise and return output (stdout) as array value from terminal
                $command = "tesseract " . $get_file . " stdout -l khm ";
            */

            // work well in Local but not in server
            // $command = "tesseract " . $get_file . " -l khm " . $storage->url('public/'.$img_file_name);

            // work well in server
            $command = "tesseract " . $get_file . " --tessdata-dir " . env('TESSDATA_PREFIX')
                        //local
                        . " -l khm " . $storage->url('public/'.$img_file_name);
                        //s3
                        // . " -l khm " . $storage->url(''$img_file_name);
                        // . " -l khm " . $storage->put($txt_file, $storage->url($img_file_name), 'public');

            exec($command);

            //upload img and text file to S3
            Storage::disk('s3')->put($txt_file, File::get($txt_file), 'public');
            Storage::disk('s3')->put($img_file_name .'.'.$extension, File::get($img_file_name .'.'.$extension), 'public');

            $result = array(
                'result' => null,
                'download' => false
            );

            //logger(url('/public/storage/'. $img_file_name_no_extension.".txt"));
            // $read_file_content = File::get($storage->url('public/'. $img_file_name .".txt"));

            // $read_file_content = File::get($storage->url('public/'. $txt_file));
            $read_file_content = File::get($storage->url($txt_file));

            /* count number of output text
               if
                    the output > 1000 characters shows only 500 characters in textarea and display downloadable txt button
               else
                    show text in textarea
            */
            if(mb_strlen(serialize($read_file_content), 'UTF-8')>1000)
            {
                $result['result'] = Str::limit($read_file_content, 1000,'....');
                // count number of character of string
                //logger(mb_strlen($result['result'], 'UTF-8'));
                $result['download'] = "Please Download the whole text here: <a class='btn btn-primary' href="
                                        // . url('storage/'.$txt_file)
                                        . url($storage->url($txt_file))
                                        . " target='_blank'> Download text file </a>";
            }
            else
            {
                $result['result'] = $read_file_content;
            }
            return json_encode($result) ;
        }
    }
}
