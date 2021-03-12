<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Paid;
use App\User;
use App\Settings;
use App\Notifications\NotificationAdmin;
use Illuminate\Console\Command;

class NotificationPaids extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:notificationPaids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verification paid time';

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
        $paids= Paid::where('statusDelivery',1)->where('timeDelivery', '<=', Carbon::now())->get(); 
        $emailsGet = Settings::where('name','Email Delivery')->first();

        if($emailsGet){
            $emails = json_decode($emailsGet->value);
            $messageAdmin = "el código de orden: ".$paid->codeUrl." tiene 10 min o más que no ha sido tomado el orden!";
            foreach($emails as $email){
                (new User)->forceFill([
                    'email' => $email,
                ])->notify(
                    new NotificationAdmin($messageAdmin)
                );
            }
        }

        $this->info('Se realizo el cambio correctamente!');
    }
}
