<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class tesseractCMD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:tesseract 
                            { inputFile } 
                            { tessdata_dir }
                            { outputFile }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To execute Tesseract Command';

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
        $command_individual_tesseract = "tesseract "
            . $this->argument('inputFile')
            . " --tessdata-dir " . $this->argument('tessdata_dir')
            . " -l khm "
            . $this->argument('outputFile');
        // exec($command_individual_tesseract . "2>&1" , $output);
        // logger("Tesseract_CMD: " . $command_individual_tesseract);
        exec($command_individual_tesseract, $output);
        // logger("Tesseract_OP: " . var_dump($output));
    }
}
