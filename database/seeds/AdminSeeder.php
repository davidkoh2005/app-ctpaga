<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(! \DB::table("admins")->whereId(1)->first())
            \DB::table("admins")->insert(
                array(
                    'id'        => 1,
                    'email'   => 'admin@compralotodo.com',
                    'password' => bcrypt("Ee81887127*"),
                    'created_at'=> date('Y-m-d H:m:s'),
                    'updated_at'=> date('Y-m-d H:m:s'),
                )
            );
    }
}
