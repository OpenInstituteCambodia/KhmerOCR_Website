<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class pdf2ImgConvertCMD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
//$command_pdf2Images = "convert -density 300 " . $get_file
//. " -depth 8 -strip -background white -alpha off "
//. $storage->url('public/' . $file_name . '.jpg');
    protected $signature = 'command:convert 
                { convertInputFile} 
                { convertOutputFile}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To convert from PDF to Imagefile';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $command_pdf2Images = "convert -density 300 " . $this->argument('convertInputFile')
            . " -depth 8 -strip -background white -alpha off "
            . $this->argument('convertOutputFile');
        exec($command_pdf2Images, $output);
       // logger("Convert_OP: " . var_dump($output));
        // return $output;
    }
}
