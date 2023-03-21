<?php

namespace App\Console\Commands;

use App\Http\Controllers\Readers\PositronReader;
use Illuminate\Console\Command;

class ListenToPositronQueue extends Command
{
    protected $signature = 'positron:listen';
    protected $description = 'Listen to the Positron Queue and store the received data';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Listening to the Positron Queue...');
        $positronReader = new PositronReader();
        $positronReader->integra();
    }
}
