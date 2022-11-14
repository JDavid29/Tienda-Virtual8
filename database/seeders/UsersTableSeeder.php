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
                'role_id' => 2,
                'name' => 'Cliente',
                'email' => 'el.cliente@gmail.com',
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
                'role_id' => 2,
                'name' => 'Cliente02',
                'email' => 'el.cliente02@gmail.com',
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
                'remember_token' => '5Q2xRxTZIZzj4Uom9f9Vo9axCTvuVc9nPNuGqv34wpJeBElKeOggZjc3oiqU',
                'settings' => NULL,
                'created_at' => '2022-10-22 22:51:11',
                'updated_at' => '2022-10-22 22:55:51',
            ),
            3 =>
            array (
                'id' => 4,
                'role_id' => 3,
                'name' => 'Vendedor',
                'email' => 'vendedor@gmail.com',
                'avatar' => 'users/default.png',
                'email_verified_at' => NULL,
                'password' => '$2y$10$gCqxUNZTazCWu0I/UiDV7egE/Bx/.KKzAbPYNf22EdE5LLZWUMxwe',
                'remember_token' => NULL,
                'settings' => NULL,
                'created_at' => '2022-11-06 14:39:17',
                'updated_at' => '2022-11-07 01:00:41',
            ),
            4 =>
            array (
                'id' => 5,
                'role_id' => 3,
                'name' => 'Vendedor N2',
                'email' => 'vendedorn2@gmail.com',
                'avatar' => 'users/default.png',
                'email_verified_at' => NULL,
                'password' => '$2y$10$LpTGnYXPKgMks31DrNA1hueX3PR/H0EbxhzMpb7HMVcmEmE/HDhde',
                'remember_token' => NULL,
                'settings' => NULL,
                'created_at' => '2022-11-09 01:11:13',
                'updated_at' => '2022-11-09 01:14:55',
            ),
        ));


    }
}
