<?php

namespace App\Console\Commands;

use App\Bank;
use App\Picture;
use App\Balance;
use App\Deposits;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CutDeposits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:cutDeposits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cut Deposits';

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

        $balancesAll = Balance::join('commerces', 'commerces.id', '=', 'balances.commerce_id')
                ->where('balances.total', '>=', 1)
                ->where('commerces.confirmed', true)
                ->select('balances.id', 'balances.user_id', 'balances.commerce_id', 'balances.coin', 'balances.total',
                'commerces.name')
                ->orderBy('name', 'asc')
                ->orderBy('coin', 'desc')->get();

        foreach ($balancesAll as $balance)
        {
 
            if($balance->coin == 0)
                $coin = "USD";
            else
                $coin = "Bs";

            $bank = Bank::where('user_id', $balance->user_id)
                        ->where('coin', $coin)->first();

            if($bank){
                Deposits::create([
                    "user_id"       => $balance->user_id,
                    "commerce_id"   => (int)$balance->commerce_id,
                    "coin"          => $balance->coin,
                    "total"         => floatval($balance->total),
                    "date"          => Carbon::now(),
                ]);

                $balance->total -= floatval($balance->total);
                $balance->save();
            }
        }

        $this->info('El corte se ha realizado correctamente');
    }
}
