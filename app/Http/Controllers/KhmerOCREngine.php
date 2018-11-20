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
        // echo "RecognitionEngine()";
        $file_uploaded = $request->file('file_upload');

        // check mime of file
        $finfo = finfo_open( FILEINFO_MIME_TYPE );
        $mtype = finfo_file( $finfo, $file_uploaded );
        finfo_close( $finfo );
        // echo "mtype: " . $mtype . "<br>";
        // mtype: application/pdf
        // mtype: image/jpeg
        // mtype: image/png

        // output: pdf, jpg, png
        $extension = $file_uploaded->getClientOriginalExtension();
        //$img_file_name = $img_file_uploaded->getFilename().'.'.$extension;
        //echo "extension: " . $extension . "<br>";

        // new file name only without extension
        $file_name = date('m-d-Y_H_i_s');

        // set storage name to be used
        $storage = Storage::disk(env('OCR_STORAGE'));

        // For Local storage
        $success_file_upload = $storage->put("public/".$file_name .'.'.$extension, File::get($file_uploaded));
        // echo "file_uploaded: " . $file_uploaded . "<br>";

        if($success_file_upload == true) {
            // get path of uploaded file from Local storage
//            $get_file = $storage->url('public/' . $file_name.'.'.$extension);
//            //echo "get_file_path: " . $get_file . "<br>";
//
//            // get path of uploaded file from S3 storage
//            // $get_file = $storage->url($img_file_name.'.'.$extension);
//
//            $txt_file = $file_name. '.txt';

        }





//        if($success_file_upload == true)
//        {
//            // get path of uploaded file from Local storage
//            $get_file = $storage->url('public/' . $file_name.'.'.$extension);
//            //echo "get_file_path: " . $get_file . "<br>";
//
//            // get path of uploaded file from S3 storage
//            // $get_file = $storage->url($img_file_name.'.'.$extension);
//
//            $txt_file = $file_name. '.txt';
//
//            // pdf file
//            if($extension == 'pdf'){
//                // convert -density 300 test1.pdf -depth 8 -strip -background white -alpha off test1.tiff
//                $command_pdf2tiff = "convert -density 300 " . $get_file
//                        . " -depth 8 -strip -background white -alpha off "
//                        . $storage->url('public/'.$file_name.'.tiff') ;
//                exec($command_pdf2tiff);
//
//                // Calling Tesseract OCR Engine
//                /**
//                // Tesseract command to recognise and return output (stdout) as array value from terminal
//                $command = "tesseract " . $get_file . " stdout -l khm ";
//                 */
//
//                // work well in Local but not in server
//                // $command_tesseract = "tesseract " . $get_file . " -l khm " . $storage->url('public/'.$img_file_name);
//
//                // work well in server
//                $command_tesseract = "tesseract " . $storage->url('public/'.$file_name.'.tiff') . " --tessdata-dir " . env('TESSDATA_PREFIX')
//                    //local
//                    . " -l khm " . $storage->url('public/'.$file_name);
//                exec($command_tesseract);
//
//                //upload img and text file to S3
//                $upload_to_s3 = Storage::disk('s3')->put($file_name .'.'.$extension, File::get($get_file), 'public');
//                // delete img file after upload
//                if($upload_to_s3  == true)
//                {
//                    File::Delete($get_file);
//                    File::Delete($storage->url('public/'.$file_name.'.tiff'));
//                }
//            }
//            // image files
//            else{
//                // Calling Tesseract OCR Engine
//                /**
//                // Tesseract command to recognise and return output (stdout) as array value from terminal
//                $command = "tesseract " . $get_file . " stdout -l khm ";
//                 */
//
//                // work well in Local but not in server
//                // $command = "tesseract " . $get_file . " -l khm " . $storage->url('public/'.$img_file_name);
//
//                // work well in server
//                $command_tesseract = "tesseract " . $get_file . " --tessdata-dir " . env('TESSDATA_PREFIX')
//                    //local
//                    . " -l khm " . $storage->url('public/'.$file_name);
//                exec($command_tesseract);
////                //upload img and text file to S3
////                $upload_to_s3 = Storage::disk('s3')->put($file_name .'.'.$extension, File::get($get_file), 'public');
////                // delete img file after upload
////                if($upload_to_s3  == true)
////                {
////                    File::Delete($get_file);
////                }
//            }
//
//            $result = array(
//                'result' => null,
//                'download' => false
//            );
//
//            //logger(url('/public/storage/'. $img_file_name_no_extension.".txt"));
//            // $read_file_content = File::get($storage->url('public/'. $img_file_name .".txt"));
//
//            $read_file_content = File::get($storage->url('public/'. $txt_file));
//
//            /* count number of output text
//               if
//                    the output > 1000 characters shows only 500 characters in textarea and display downloadable txt button
//               else
//                    show text in textarea
//            */
//            if(mb_strlen(serialize($read_file_content), 'UTF-8')>1000)
//            {
//                $result['result'] = Str::limit($read_file_content, 1000,'....');
//                // count number of character of string
//                //logger(mb_strlen($result['result'], 'UTF-8'));
//                $result['download'] = "Please Download the whole text here: <a class='btn btn-primary' href="
//                                        . url('storage/'.$txt_file)
//                                        . " target='_blank'> Download text file </a>";
//            }
//            else
//            {
//                $result['result'] = $read_file_content;
//            }
//            return json_encode($result) ;
//        }

    } // end of function RecognitionEngine()
} // end of class KhmerOCREngine
