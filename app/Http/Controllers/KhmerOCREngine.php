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

        $successfully_upload = $storage->put("public/".$img_file_name .'.'.$extension, File::get($img_file_uploaded));

        if($successfully_upload == true)
        {
            // get path of uploaded file
            $get_file = $storage->url('public/' . $img_file_name.'.'.$extension);

            $txt_file = $img_file_name. '.txt';

            // Calling Tesseract OCR Engine
            /**
                // Tesseract command to recognise and return output (stdout) as array value from terminal
                $command = "tesseract " . $get_file . " stdout -l khm ";
            */

            $command = "tesseract " . $get_file . " -l khm " . $storage->url('public/'.$img_file_name);
            exec($command);

            $result = array(
                'result' => null,
                'download' => false
            );

            //logger(url('/public/storage/'. $img_file_name_no_extension.".txt"));
            $read_file_content = File::get($storage->url('public/'. $img_file_name .".txt"));

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
                                        . url('storage/'.$txt_file) . " target='_blank'> Download text file </a>";
            }
            else
            {
                $result['result'] = $read_file_content;
            }
            return json_encode($result) ;
        }
    }
}
