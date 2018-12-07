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
        $file_uploaded = $request->file('file_upload');

        // check mime of file:  application/pdf, image/jpeg, image/png
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mtype = finfo_file($finfo, $file_uploaded);
        finfo_close($finfo);

        // file extension: pdf, jpg, png
        $extension = $file_uploaded->getClientOriginalExtension();
        // new file name only without extension
        $file_name = date('dmY_His');

        // set storage name to be used
        $storage = Storage::disk(env('OCR_STORAGE'));
        // For Local storage
        $success_file_upload = $storage->put("public/" . $file_name . '.' . $extension, File::get($file_uploaded));

        $return_result = array(
            'firstImg' => null,
            'firstOCRText' => null,
            'download' => null
        );

        if ($success_file_upload == true) {
            // get path of uploaded file from Local storage
            $get_file = $storage->url('public/' . $file_name . '.' . $extension);
//            // get path of uploaded file from S3 storage
//            // $get_file = $storage->url($img_file_name.'.'.$extension);


            /** PDF file consists of multiple output OCR generated Text & image files */
            if ($mtype == 'application/pdf') {
                /** Get Total pdf pages */
                $totalPDFPages = $this->getPDFPages($get_file);

                // Calling Convert Command Console
                Artisan::call('command:convert',
                    [ 'convertInputFile' => $storage->url('public/' . $file_name . '.' . $extension),
                        'convertOutputFile' => $storage->url('public/' . $file_name . '.jpg')
                    ], null);

                // foreach PDF page
                for ($i = 0; $i < $totalPDFPages; $i++) {
                    // Calling each Tesseract Command Console
                    Artisan::call('command:tesseract',
                        [
                            'inputFile' => $storage->url('public/' . $file_name . "-" . $i . '.jpg'),
                            'tessdata_dir' => env('TESSDATA_PREFIX'),
                            'outputFile' => $storage->url('public/' . $file_name . "-" . $i)
                        ], null);

                        // append text files into a single text file for all output of generated text
                        $read_file_content_i = File::get($storage->url('public/' . $file_name . "-" . $i . '.txt'));
                        /* Write and append the contents to the file (FILE_APPEND) and (LOCK_EX) flag to prevent anyone else writing to the file at the same time */
                        file_put_contents($storage->url('public/' . $file_name . "_all.txt"), $read_file_content_i, FILE_APPEND | LOCK_EX);
                } // end of for ($i = 0; $i < $totalPDFPages; $i++)

                $img_str_0 = '/storage/' . $file_name . '-0.jpg';
                $return_result['firstImg'] = "<img src='" . $img_str_0 . "'>";
                $read_file_content_0 = File::get($storage->url('public/' . $file_name . '-0.txt'));
                $return_result['firstOCRText'] = $read_file_content_0;

                $return_result['download'] = "<a href="
                    . url('storage/' . $file_name . "_all.txt")
                    . " target='_blank' class='btn btnBigLightPurple'>
                            <i class=\"fas fa-cloud-download-alt fa-1x\"></i> Download OCR Text 
                        </a>";
                return json_encode($return_result);

                // pagination



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
                    $return_result['firstImg'] = "<img src='" . $img_str . "'>";
                    $read_file_content = File::get($storage->url('public/' . $txt_file));
                    $return_result['firstOCRText'] = $read_file_content;
                    $return_result['download'] = "<a href="
                        . url('storage/' . $txt_file)
                        . " target='_blank' class='btn btnBigLightPurple'>
                                <i class=\"fas fa-cloud-download-alt fa-1x\"></i> Download OCR Text 
                            </a>";
                    return json_encode($return_result);
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
        exec("pdfinfo \"$pdfFile\" 2>&1", $output);
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
    } // .function getPDFPages($pdfFile)

} // end of class KhmerOCREngine
