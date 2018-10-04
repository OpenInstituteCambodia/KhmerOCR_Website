<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomHelper;

class APIController extends Controller
{
    /**
     * API for getting image (based-64 format) from mobile and processing OCR recoginition
     *
     */
    public function OCRBase64ImageToText(Request $request){
        if($request->getContentType() == 'json') {
            foreach($request->toArray() as $key1=>$multi_arr) {
                //var_dump($multi_arr);
                foreach ($multi_arr as $each_arr) {
                    // dd($each_arr);
                    return CustomHelper::base64ToImageAndOCRImageToText($each_arr);
                    //foreach ($each_arr as $each_arr_obj) {
                        // Log::info($each_arr_obj['photo']);
                        // return CustomHelper::generate_image_and_ocr_image_to_text($each_arr_obj['photo']);
                    //}
                }
            }
        }
        else {
            return collect([
                'code' => '500',
                'message'=> "Receiving data is not JSON format",
            ]);
        }
    }
}
