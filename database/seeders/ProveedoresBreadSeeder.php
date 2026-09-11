<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;

/**
 * Registra el BREAD de la tabla `proveedores` en Voyager.
 * Equivale a usar la interfaz BREAD → Database → "Add BREAD".
 *
 * Campos:
 *   Browse : nombre, contacto, created_at
 *   Read   : nombre, contacto
 *   Edit   : nombre, contacto
 *   Add    : nombre, contacto
 */
class ProveedoresBreadSeeder extends Seeder
{
    public function run()
    {
        // ── 1. Crear / actualizar el Data Type ──────────────────────
        $dataType = DataType::firstOrNew(['slug' => 'proveedores']);
        $dataType->fill([
            'name'                  => 'proveedores',
            'display_name_singular' => 'Proveedor',
            'display_name_plural'   => 'Proveedores',
            'icon'                  => 'voyager-truck',
            'model_name'            => 'App\\Models\\Proveedore',
            'policy_name'           => null,
            'controller'            => null,
            'description'           => 'Proveedores de productos de la tienda',
            'generate_permissions'  => true,
            'server_side'           => false,
            'details'               => json_encode([
                'order_column'         => null,
                'order_display_column' => null,
                'order_direction'      => 'asc',
                'default_search_key'   => null,
            ]),
        ])->save();

        // ── 2. Definir los campos (data_rows) ───────────────────────
        $rows = [
            [
                'field'        => 'id',
                'type'         => 'number',
                'display_name' => 'ID',
                'required'     => true,
                'browse' => false, 'read' => false, 'edit' => false, 'add' => false, 'delete' => false,
                'order'        => 1,
                'details'      => '{}',
            ],
            [
                'field'        => 'nombre',
                'type'         => 'text',
                'display_name' => 'Nombre',
                'required'     => true,
                'browse' => true,  'read' => true,  'edit' => true,  'add' => true,  'delete' => false,
                'order'        => 2,
                'details'      => '{}',
            ],
            [
                'field'        => 'contacto',
                'type'         => 'text',
                'display_name' => 'Contacto',
                'required'     => false,
                'browse' => true,  'read' => true,  'edit' => true,  'add' => true,  'delete' => false,
                'order'        => 3,
                'details'      => '{}',
            ],
            [
                'field'        => 'created_at',
                'type'         => 'timestamp',
                'display_name' => 'Creado',
                'required'     => false,
                'browse' => true,  'read' => false, 'edit' => false, 'add' => false, 'delete' => false,
                'order'        => 4,
                'details'      => '{}',
            ],
            [
                'field'        => 'updated_at',
                'type'         => 'timestamp',
                'display_name' => 'Actualizado',
                'required'     => false,
                'browse' => false, 'read' => false, 'edit' => false, 'add' => false, 'delete' => false,
                'order'        => 5,
                'details'      => '{}',
            ],
        ];

        foreach ($rows as $rowData) {
            $row = DataRow::firstOrNew([
                'data_type_id' => $dataType->id,
                'field'        => $rowData['field'],
            ]);
            $row->fill(array_merge($rowData, ['data_type_id' => $dataType->id]))->save();
        }

        // ── 3. Permisos CRUD para el rol admin ──────────────────────
        Permission::generateFor('proveedores');

        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $perms = Permission::where('table_name', 'proveedores')->pluck('id');
            $adminRole->permissions()->syncWithoutDetaching($perms);
        }

        // ── 4. Ítem en el menú lateral de Voyager ───────────────────
        $menu = Menu::where('name', 'admin')->first();
        if ($menu && !MenuItem::where('menu_id', $menu->id)->where('route', 'voyager.proveedores.index')->exists()) {
            MenuItem::create([
                'menu_id'    => $menu->id,
                'title'      => 'Proveedores',
                'url'        => '',
                'route'      => 'voyager.proveedores.index',
                'target'     => '_self',
                'icon_class' => 'voyager-truck',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 11,
            ]);
        }

        $this->command->info('✓ BREAD de Proveedores registrado correctamente.');
    }
}
