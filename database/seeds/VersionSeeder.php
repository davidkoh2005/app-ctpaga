<?php

use Illuminate\Database\Seeder;

class VersionSeeder extends Seeder
{
    /**php artisan migrate:refresh && php artisan db:seed --class=AdminSeeder
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(! \DB::table("version")->whereId(1)->first())
            \DB::table("version")->insert(
                array(
                    'id'            => 1,
                    'version'       => '1.0.42',
                    'url'           => 'https://drive.google.com/file/d/1CMtAtfHiVajGsJ3SUjlinuqH18dWg2AX/view?usp=sharing',
                    'created_at'    => date('Y-m-d H:m:s'),
                    'updated_at'    => date('Y-m-d H:m:s'),
                    'app'           => env('APP_NAME'),
                    'app_ios'       => 'https://www.apple.com/la/app-store/',
                    'app_android'   => 'https://play.google.com/store/apps/details?id=compralotodo.appBusiness',
                )
            );
        
        if(! \DB::table("version")->whereId(2)->first())
            \DB::table("version")->insert(
                array(
                    'id'            => 2,
                    'version'       => '1.0.23',
                    'url'           => 'https://drive.google.com/file/d/1l_2D-lmk88X0HYzuyYyjx6iuduJH6-3C/view?usp=sharing',
                    'created_at'    => date('Y-m-d H:m:s'),
                    'updated_at'    => date('Y-m-d H:m:s'),
                    'app'           => 'delivery '.env('APP_NAME'),
                    'app_ios'       => 'https://www.apple.com/la/app-store/',
                    'app_android'   => 'https://play.google.com/store/apps/details?id=ctlleva.ctlleva',
                )
            );
    }
}
