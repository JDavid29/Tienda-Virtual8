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
 * Registra el BREAD de la tabla `productos` en Voyager.
 *
 * Equivale a usar la interfaz BREAD → Database → "Add BREAD",
 * pero de forma programática para reproducirlo en cualquier entorno:
 *   php artisan db:seed --class=ProductosBreadSeeder
 *
 * Campos configurados:
 *   Browse : nombre, precio, cantidad, referencia, cover_img, category_id, created_at
 *   Read   : todos los campos visibles
 *   Edit   : nombre, descripcion, descripcion_larga, precio, cantidad, referencia, cover_img, category_id, shop_id
 *   Add    : igual que Edit
 */
class ProductosBreadSeeder extends Seeder
{
    public function run()
    {
        // ── 1. Crear / actualizar el Data Type ──────────────────────
        $dataType = DataType::firstOrNew(['slug' => 'productos']);
        $dataType->fill([
            'name'                  => 'productos',
            'display_name_singular' => 'Producto',
            'display_name_plural'   => 'Productos',
            'icon'                  => 'voyager-box',
            'model_name'            => 'App\\Models\\Producto',
            'policy_name'           => null,
            'controller'            => null,
            'description'           => 'Catálogo de productos de la tienda',
            'generate_permissions'  => true,
            'server_side'           => false,
            'details'               => json_encode([
                'order_column'         => null,
                'order_display_column' => null,
                'order_direction'      => 'desc',
                'default_search_key'   => null,
            ]),
        ])->save();

        // ── 2. Definir los campos (data_rows) ───────────────────────
        // browse = listado, read = detalle, edit = formulario edición,
        // add = formulario alta, delete = campo visible al eliminar.
        $rows = [
            ['field' => 'id',               'type' => 'number',        'display_name' => 'ID',                'required' => true,  'browse' => false, 'read' => false, 'edit' => false, 'add' => false, 'delete' => false, 'order' => 1,  'details' => '{}'],
            ['field' => 'nombre',           'type' => 'text',          'display_name' => 'Nombre',            'required' => true,  'browse' => true,  'read' => true,  'edit' => true,  'add' => true,  'delete' => false, 'order' => 2,  'details' => '{}'],
            ['field' => 'descripcion',      'type' => 'text_area',     'display_name' => 'Descripción corta', 'required' => false, 'browse' => false, 'read' => true,  'edit' => true,  'add' => true,  'delete' => false, 'order' => 3,  'details' => '{}'],
            ['field' => 'descripcion_larga','type' => 'rich_text_box', 'display_name' => 'Descripción larga', 'required' => false, 'browse' => false, 'read' => true,  'edit' => true,  'add' => true,  'delete' => false, 'order' => 4,  'details' => '{}'],
            ['field' => 'precio',           'type' => 'number',        'display_name' => 'Precio (Bs)',       'required' => true,  'browse' => true,  'read' => true,  'edit' => true,  'add' => true,  'delete' => false, 'order' => 5,  'details' => json_encode(['step' => '0.01', 'min' => '0'])],
            ['field' => 'cantidad',         'type' => 'number',        'display_name' => 'Stock (cantidad)',  'required' => false, 'browse' => true,  'read' => true,  'edit' => true,  'add' => true,  'delete' => false, 'order' => 6,  'details' => json_encode(['min' => '0'])],
            ['field' => 'referencia',       'type' => 'text',          'display_name' => 'Referencia / SKU',  'required' => false, 'browse' => true,  'read' => true,  'edit' => true,  'add' => true,  'delete' => false, 'order' => 7,  'details' => '{}'],
            ['field' => 'cover_img',        'type' => 'image',         'display_name' => 'Imagen de portada', 'required' => false, 'browse' => true,  'read' => true,  'edit' => true,  'add' => true,  'delete' => false, 'order' => 8,  'details' => json_encode(['resize' => ['width' => '800', 'height' => null], 'quality' => '90', 'upsize' => true])],
            ['field' => 'category_id',      'type' => 'select_dropdown','display_name' => 'Categoría',        'required' => false, 'browse' => true,  'read' => true,  'edit' => true,  'add' => true,  'delete' => false, 'order' => 9,  'details' => json_encode(['model' => 'App\\Models\\Category', 'table' => 'categories', 'column' => 'category_id', 'key' => 'id', 'label' => 'name', 'pivot_table' => null, 'pivot' => '0', 'taggable' => '0'])],
            ['field' => 'shop_id',          'type' => 'number',        'display_name' => 'Tienda (ID)',        'required' => false, 'browse' => false, 'read' => true,  'edit' => true,  'add' => true,  'delete' => false, 'order' => 10, 'details' => '{}'],
            // proveedor_id: dropdown poblado desde la tabla proveedores (relación N:1)
            ['field' => 'proveedor_id',     'type' => 'select_dropdown','display_name' => 'Proveedor',        'required' => false, 'browse' => true,  'read' => true,  'edit' => true,  'add' => true,  'delete' => false, 'order' => 11, 'details' => json_encode(['model' => 'App\\Models\\Proveedore', 'table' => 'proveedores', 'column' => 'proveedor_id', 'key' => 'id', 'label' => 'nombre', 'pivot_table' => null, 'pivot' => '0', 'taggable' => '0'])],
            ['field' => 'created_at',       'type' => 'timestamp',     'display_name' => 'Creado',             'required' => false, 'browse' => true,  'read' => false, 'edit' => false, 'add' => false, 'delete' => false, 'order' => 12, 'details' => '{}'],
            ['field' => 'updated_at',       'type' => 'timestamp',     'display_name' => 'Actualizado',        'required' => false, 'browse' => false, 'read' => false, 'edit' => false, 'add' => false, 'delete' => false, 'order' => 13, 'details' => '{}'],
        ];

        foreach ($rows as $rowData) {
            // firstOrNew evita duplicados si el seeder se corre más de una vez
            $row = DataRow::firstOrNew([
                'data_type_id' => $dataType->id,
                'field'        => $rowData['field'],
            ]);
            $row->fill(array_merge($rowData, ['data_type_id' => $dataType->id]))->save();
        }

        // ── 3. Generar permisos CRUD y asignarlos al rol admin ──────
        Permission::generateFor('productos');

        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $perms = Permission::where('table_name', 'productos')->pluck('id');
            $adminRole->permissions()->syncWithoutDetaching($perms);
        }

        // ── 4. Agregar ítem al menú lateral de Voyager ──────────────
        $menu = Menu::where('name', 'admin')->first();
        if ($menu && !MenuItem::where('menu_id', $menu->id)->where('route', 'voyager.productos.index')->exists()) {
            MenuItem::create([
                'menu_id'    => $menu->id,
                'title'      => 'Productos',
                'url'        => '',
                'route'      => 'voyager.productos.index',
                'target'     => '_self',
                'icon_class' => 'voyager-box',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 10,
            ]);
        }

        $this->command->info('✓ BREAD de Productos registrado correctamente.');
    }
}
