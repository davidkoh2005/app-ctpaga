<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Paid;
use App\Events\AlarmUrgent;
use Illuminate\Console\Command;

class VerifyAlarm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:verifyAlarm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar Alarma';

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
        $alarm = Paid::where("alarm", "<=", Carbon::now())->whereNull("idDelivery")->first();

        if($alarm){
            $success = event(new AlarmUrgent());
            $this->info('Alarma Pendiente!');
        }else{
            $this->info('No hay alarma pendiente');
        }
            
    }
}
