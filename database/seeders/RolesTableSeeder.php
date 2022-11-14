<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'admin',
                'display_name' => 'Administrator',
                'created_at' => '2022-10-22 22:34:41',
                'updated_at' => '2022-10-22 22:34:41',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'user',
                'display_name' => 'Normal User',
                'created_at' => '2022-10-22 22:34:41',
                'updated_at' => '2022-10-22 22:34:41',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'seller',
                'display_name' => 'Vendedor',
                'created_at' => '2022-11-07 00:38:05',
                'updated_at' => '2022-11-07 00:38:05',
            ),
        ));
        
        
    }
}