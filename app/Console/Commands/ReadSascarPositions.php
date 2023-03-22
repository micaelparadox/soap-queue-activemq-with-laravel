<?php

namespace App\Console\Commands;

use App\Http\Controllers\Readers\SascarReader;
use Illuminate\Console\Command;

class ReadSascarPositions extends Command
{
    protected $signature = 'sascar:read-positions';
    protected $description = 'Read Sascar positions and store the received data';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Reading Sascar positions...');
        $sascarReader = new SascarReader();
        $sascarReader->readPositions();
    }
}
