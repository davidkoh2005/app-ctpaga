<?php

namespace App\Console\Commands;

use App\Paid;
use App\Sale;
use Illuminate\Console\Command;

class RemoveSale extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:removesale';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'remove sale without using';

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
        $sales = Sale::leftJoin('paids', 'sales.codeUrl', '=', 'paids.codeUrl')
                    ->whereNull('paids.nameCompanyPayments')
                    ->select('sales.id','sales.codeUrl')->get();

        foreach($sales as $sale)
        {
            $sale->delete();
        }
        
        $this->info('Se realizo el cambio correctamente!');
    }
}
