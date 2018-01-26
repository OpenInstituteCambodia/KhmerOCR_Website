<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class KhmerOCREngine extends Controller
{
    function RecognitionEngine(Request $request)
    {
        $img_file_uploaded = $request->file('image_file');
        $extension = $img_file_uploaded->getClientOriginalExtension();
        //$img_file_name = $img_file_uploaded->getFilename().'.'.$extension;
        $img_file_name = date('m-d-Y_H_i_s').'.'.$extension;
        $storage = Storage::disk('local');
        $successfully_store = $storage->put($img_file_name, File::get($img_file_uploaded));
        if($successfully_store == true)
        {
            // get path of uploaded file
            $get_file = $storage->url($img_file_name);
            // Calling Tesseract OCR Engine
            $command = "tesseract " . $get_file . " stdout -l khm ";
            exec($command, $output);
            return($output);
        }
    }
}
