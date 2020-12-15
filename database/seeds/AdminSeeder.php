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
        \DB::table("admins")->insert(
            array(
                'id'        => 1,
                'email'   => 'ctpaga@admin.com',
                'password' => bcrypt("Ee81887127*"),
                'created_at'=> date('Y-m-d H:m:s'),
                'updated_at'=> date('Y-m-d H:m:s'),
            )
        );
    }
}
