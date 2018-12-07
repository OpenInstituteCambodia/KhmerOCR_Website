<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mtype = finfo_file($finfo, $file_uploaded);
        finfo_close($finfo);
        // echo "mtype: " . $mtype . "<br>";
        // mtype: application/pdf
        // mtype: image/jpeg
        // mtype: image/png

        // output: pdf, jpg, png
        $extension = $file_uploaded->getClientOriginalExtension();
        //$img_file_name = $img_file_uploaded->getFilename().'.'.$extension;
        //echo "extension: " . $extension . "<br>";

        // new file name only without extension
        $file_name = date('dmY_His');

        // set storage name to be used
        $storage = Storage::disk(env('OCR_STORAGE'));

        // For Local storage
        $success_file_upload = $storage->put("public/" . $file_name . '.' . $extension, File::get($file_uploaded));
        // echo "file_uploaded: " . $file_uploaded . "<br>";

        if ($success_file_upload == true) {
            // get path of uploaded file from Local storage
            $get_file = $storage->url('public/' . $file_name . '.' . $extension);
//            // get path of uploaded file from S3 storage
//            // $get_file = $storage->url($img_file_name.'.'.$extension);

            $result = array(
                'firstImg' => null,
                'firstOCRText' => null,
                'download' => false
            );
            /** PDF file consists of multiple output OCR generated Text & image files */
            if ($mtype == 'application/pdf') {
                /** Get Total pdf pages */
                $totalPDFPages = $this->getPDFPages($get_file);
                //$totalPDFPages = 65;
                // echo "Total Pages: " . $totalPDFPages;

                $testing_concvert_console_cmd = Artisan::call('command:convert',
                    [ 'convertInputFile' => $storage->url('public/' . $file_name . '.' . $extension),
                        'convertOutputFile' => $storage->url('public/' . $file_name . '.jpg')
                    ], null);

                for ($i = 0; $i < $totalPDFPages; $i++) {
                    $testing_tesseract_console_cmd = Artisan::call('command:tesseract',
                        [
                            'inputFile' => $storage->url('public/' . $file_name . "-" . $i . '.jpg'),
                            'tessdata_dir' => env('TESSDATA_PREFIX'),
                            'outputFile' => $storage->url('public/' . $file_name . "-" . $i)
                        ], null);
//                    [
//                        'inputFile' => $storage->url('public/' . $file_name . "-" . $i . '.jpg'),
//                        'tessdata_dir' => env('TESSDATA_PREFIX'),
//                        'outputFile' => $storage->url('public/' . $file_name . "-" . $i)
//                    ], null);
                }
                //dd($testing_concvert_console_cmd);

//                $testing_console_cmd = Artisan::call('command:tesseract',
//                    [
//                        'inputFile' => $storage->url('public/1.jpg'),
//                        'tessdata_dir' => env('TESSDATA_PREFIX'),
//                        'language' => '-l khm',
//                        'outputFile' => $storage->url('public/1')
//                    ], null);
//                dd($testing_console_cmd);

//                /** Convert pdf to images */
//                // convert -density 300 test1.pdf -depth 8 -strip -background white -alpha off test1.jpg
//                $command_pdf2Images = "convert -density 300 " . $get_file
//                    . " -depth 8 -strip -background white -alpha off "
//                    . $storage->url('public/' . $file_name . '.jpg');
//                exec($command_pdf2Images, $command_output);
//
//                // if command convert pdf to image finished executed
//                if ($command_output == []) {
//                    /** Write a list of all images into a single text file (filename_savedlist) */
//                    $str_savedlist = "";
//                    // Create a list of image names for write to a textfile
//                    for ($i = 0; $i < $totalPDFPages; $i++) {
//                        $str_savedlist = $str_savedlist . $file_name . "-" . $i . '.jpg' . "\n";
//
//                        /** Executed Tesseract Command for individual files */
//                        // tesseract -l khm test1.tiff test1tiff.txt
//                        if (file_exists($storage->url('public/' . $file_name . "-" . $i . '.jpg'))) {
//                            $command_individual_tesseract = "tesseract "
//                                . $storage->url('public/' . $file_name . "-" . $i . '.jpg')
//                                . " --tessdata-dir " . env('TESSDATA_PREFIX')
//                                . " -l khm " . $storage->url('public/' . $file_name . "-" . $i);
//                            exec($command_individual_tesseract, $command_individual_tesseract_output);
//
//                            if ($command_individual_tesseract_output == []) {
//                                // append text files into a single text file for all output of generated text
//                                // $storage->url('public/'. $file_name . "_all");
//                                $read_file_content_i = File::get($storage->url('public/' . $file_name . "-" . $i . '.txt'));
//                                // Write the contents to the file,
//                                // using the FILE_APPEND flag to append the content to the end of the file
//                                // and the LOCK_EX flag to prevent anyone else writing to the file at the same time
//                                file_put_contents($storage->url('public/' . $file_name . "_all"), $read_file_content_i, FILE_APPEND | LOCK_EX);
//
//                                // set pagination in controller and send back to form with name=filename
//
//
//                            }
//
//                        }
//                    }
////                    // OCR generate .tiff to .text
////                    if ($storage->url('public/' . $file_name.'.tiff'))
////                    {
////                        // work well in server
////                        $command_tesseract_all = "tesseract "
////                                . $storage->url('public/' . $file_name.'.tiff')
////                                . " --tessdata-dir " . env('TESSDATA_PREFIX')
////                                . " -l khm "
////                                . $storage->url('public/'. $file_name . "_all");
////                        exec($command_tesseract_all, $command_tesseract_all_output);
////                    }
//
////                    if($command_tesseract_all_output == [])
////                    {
//                    $img_str_0 = '/storage/' . $file_name . '-0.jpg';
//                    $result['firstImg'] = "<img src='" . $img_str_0 . "'>";
//                    $read_file_content_0 = File::get($storage->url('public/' . $file_name . '-0.txt'));
//                    $result['firstOCRText'] = $read_file_content_0;
//
//                    $result['download'] = "<a href="
//                        . url('storage/' . $file_name . "_all.txt")
//                        . " target='_blank'>
//                            <button class=\"btn btnBigLightPurple\">
//                            <i class=\"fas fa-cloud-download-alt fa-1x\"></i> Download OCR Text
//                            </button></a>";
//                    return json_encode($result);
////                    }
//
//                }
//
            } // .if($mtype == 'application/pdf')
            /** image file has only 1 output OCR generated Text */
            else if ($mtype == 'image/jpeg' || $mtype == 'image/png') {

                $txt_file = $file_name . '.txt';
                $command_img_tesseract = "tesseract " . $get_file
                    . " --tessdata-dir " . env('TESSDATA_PREFIX')
                    . " -l khm " . $storage->url('public/' . $file_name);
                exec($command_img_tesseract, $command_img_tesseract_output);

                /** if command_img_tesseract is completely executed; then the result in text file is already generated */
                if ($command_img_tesseract_output == []) {
                    $img_str = '/storage/' . $file_name . '.jpg';
                    $result['firstImg'] = "<img src='" . $img_str . "'>";
                    $read_file_content = File::get($storage->url('public/' . $txt_file));
                    $result['firstOCRText'] = $read_file_content;
                    $result['download'] = "<a href="
                        . url('storage/' . $txt_file)
                        . " target='_blank'>
                        <button class=\"btn btnBigLightPurple\">
                        <i class=\"fas fa-cloud-download-alt fa-1x\"></i> Download OCR Text 
                        </button></a>";
                    return json_encode($result);
                }
            }

        } // .if($success_file_upload == true)

    } // end of function RecognitionEngine()

    /**
     * Function to get Total number of pdf pages
     * @param $pdfFile
     * @return int
     */
    function getPDFPages($pdfFile)
    {
        // $cmd = "/path/to/pdfinfo";           // Linux

        // Parse entire output
        // Surround with double quotes if file name has spaces
        // exec("$cmd \"$document\"", $output);

        // Show all pdf file info
        logger("Start");
        exec("pdfinfo \"$pdfFile\" 2>&1", $output);

        logger($output);
        logger("Done");

        // Iterate through lines
        $pagecount = 0;
        foreach ($output as $op) {
            // Extract the number from output e.g Pages:   10
            if (preg_match("/Pages:\s*(\d+)/i", $op, $matches) === 1) {
                $pagecount = intval($matches[1]);
                break;
            }
        }
        return $pagecount;
    }

} // end of class KhmerOCREngine
