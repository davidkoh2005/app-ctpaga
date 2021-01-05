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
        if(! \DB::table("version")->whereId(1)->first())
            \DB::table("version")->insert(
                array(
                    'id'        => 1,
                    'version'   => '1.0.15',
                    'url'       => 'https://drive.google.com/file/d/1CMtAtfHiVajGsJ3SUjlinuqH18dWg2AX/view?usp=sharing',
                    'created_at'=> date('Y-m-d H:m:s'),
                    'updated_at'=> date('Y-m-d H:m:s'),
                    'app'       => 'ctpaga',
                )
            );
        
        if(! \DB::table("version")->whereId(2)->first())
            \DB::table("version")->insert(
                array(
                    'id'        => 2,
                    'version'   => '1.0.1',
                    'url'       => 'https://drive.google.com/',
                    'created_at'=> date('Y-m-d H:m:s'),
                    'updated_at'=> date('Y-m-d H:m:s'),
                    'app'       => 'delivery ctpaga',
                )
            );
    }
}
