<?php

namespace App\Console\Commands;

use App\Paid;
use Illuminate\Console\Command;

class ChangeStatusPaids extends Command
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
    protected $description = 'change status paids';

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
        Paid::where('statusDelivery',1)->whereNull('idDelivery')->update(array('statusDelivery' => 0));

        $this->info('Se realizo el cambio correctamente!');
    }
}