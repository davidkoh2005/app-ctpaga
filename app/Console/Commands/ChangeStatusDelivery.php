<?php

namespace App\Console\Commands;

use App\Delivery;
use Illuminate\Console\Command;

class ChangeStatusDelivery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:changeStatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'change status delivery';

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
     * @return int
     */
    public function handle()
    {
        Delivery::where('status',true)->update(array('status' => false));

        $this->info('Se realizo el cambio correctamente!');
    }
}
