<?php

use Illuminate\Database\Seeder;

class VersionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table("version")->insert(
            array(
                'id'        => 1,
                'version'   => '1.0.0',
                'url'       => 'wwww.google.com',
                'created_at'=> date('Y-m-d H:m:s'),
                'updated_at'=> date('Y-m-d H:m:s'),
            )
        );
    }
}
