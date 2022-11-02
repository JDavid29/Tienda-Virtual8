<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'role_id' => NULL,
                'name' => 'jose',
                'email' => 'el.guerrero.z68@gmail.com',
                'avatar' => 'users/default.png',
                'email_verified_at' => NULL,
                'password' => '$2y$10$Qc8ddbv5TaPHcoAtQV2O/.cNSkIIJLgAfgshoqztyLOidY/PF3KWy',
                'remember_token' => NULL,
                'settings' => NULL,
                'created_at' => '2022-10-20 13:52:30',
                'updated_at' => '2022-10-20 13:52:30',
            ),
            1 => 
            array (
                'id' => 2,
                'role_id' => NULL,
                'name' => 'Jose david',
                'email' => 'jose.david.jaa@gmail.com',
                'avatar' => 'users/default.png',
                'email_verified_at' => NULL,
                'password' => '$2y$10$mYM5QIXJ0Wv4nwklJKoyT.ggBpepYIJOzmJDFzxHQ.rDqwZ.FKIfq',
                'remember_token' => NULL,
                'settings' => NULL,
                'created_at' => '2022-10-20 18:21:14',
                'updated_at' => '2022-10-20 18:21:14',
            ),
            2 => 
            array (
                'id' => 3,
                'role_id' => 1,
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'avatar' => 'users/default.png',
                'email_verified_at' => NULL,
                'password' => '$2y$10$NJzry8HzjWSdDEStY.aIre2g6KJEsGKT.oapW5BW6OjHHnPAOTDvS',
                'remember_token' => '8tuqP5mhg1wgIMb2IItq7o7epZueqlltcYZNjdQQROJgv4USBBxBT3o60To1',
                'settings' => NULL,
                'created_at' => '2022-10-22 22:51:11',
                'updated_at' => '2022-10-22 22:55:51',
            ),
        ));
        
        
    }
}