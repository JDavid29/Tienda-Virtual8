<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;//Agregando conexion con los archivos
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //con este seeder podemos decir que cantidad de productos vamos a crear
        Producto::factory()->count(20)->create([
            'shop_id' => 1,
        ]);

        Producto::factory()->count(15)->create([
            'shop_id' => 2,
        ]);
        // antes de realizar las migraciones y cambios, guardar todos esos cambio en voyager
    }
}
