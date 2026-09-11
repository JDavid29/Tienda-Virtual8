<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de ejemplo de proveedores + asignación a productos por marca.
 *
 * Reproduce en cualquier entorno lo que en el laptop original se hizo a mano,
 * para que la página pública /marcas tenga contenido tras un migrate:fresh --seed.
 *
 * La asignación busca palabras clave en el nombre del producto; si el catálogo
 * sembrado por ProductoFactory (nombres aleatorios) no contiene esas marcas,
 * simplemente no asigna nada — no falla.
 */
class ProveedoresSeeder extends Seeder
{
    public function run()
    {
        $proveedores = [
            'Samsung'  => 'samsung@nlg.com',
            'Logitech' => 'logitech@nlg.com',
            'Apple'    => 'apple@nlg.com',
            'MSI'      => 'msi@nlg.com',
        ];

        // updateOrInsert evita duplicados si el seeder se corre más de una vez
        foreach ($proveedores as $nombre => $contacto) {
            DB::table('proveedores')->updateOrInsert(
                ['nombre' => $nombre],
                ['contacto' => $contacto, 'updated_at' => now(), 'created_at' => now()]
            );
        }

        $ids = DB::table('proveedores')->pluck('id', 'nombre');

        // Palabras clave por marca para asignar productos existentes
        $keywords = [
            'Samsung'  => ['SAMSUNG', 'Galaxy'],
            'Apple'    => ['Apple', 'iPhone', 'MacBook', 'AirPods'],
            'Logitech' => ['Logitech', 'Ratón', 'Teclado'],
            'MSI'      => ['MSI', 'Gaming'],
        ];

        foreach ($keywords as $marca => $palabras) {
            if (!isset($ids[$marca])) continue;
            foreach ($palabras as $kw) {
                DB::table('productos')
                    ->where('nombre', 'LIKE', "%{$kw}%")
                    ->update(['proveedor_id' => $ids[$marca]]);
            }
        }

        $this->command->info('✓ Proveedores de ejemplo sembrados y asignados.');
    }
}
