<?php

namespace App\Widgets;

use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Widgets\BaseDimmer;

class ProductoDimmer extends BaseDimmer
{
    protected $config = [];

    public function run()
    {
        $total = Producto::count();
        // Asumimos columna 'estado' (1=activo, 0=inactivo). Si no existe, los cálculos no romperán.
        try {
            $active = Producto::where('estado', 1)->count();
            $inactive = Producto::where('estado', 0)->count();
        } catch (\Throwable $e) {
            $active = $total;
            $inactive = 0;
        }
        $den = max(1, ($active + $inactive) ?: $total);
        $activePct = (int) round(($active / $den) * 100);
        $inactivePct = 100 - $activePct;

        $text = sprintf(
            '<div style="color:#fff">'
                . '<div>Activos: <strong>%d</strong> • Inactivos: <strong>%d</strong></div>'
                . '<div style="margin-top:8px;height:8px;background:#eee;border-radius:4px;overflow:hidden">'
                . '<div style="width:%d%%;height:100%%;background:#28a745;float:left"></div>'
                . '<div style="width:%d%%;height:100%%;background:#dc3545;float:left"></div>'
                . '</div>'
                . '<small style="display:block;margin-top:6px">%d%% activos de %d productos</small>'
                . '</div>',
            $active,
            $inactive,
            $activePct,
            $inactivePct,
            $activePct,
            $total
        );

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-bag',
            'title'  => 'Productos',
            'text'   => $text,
            'button' => [
                'text' => 'Ir a productos',
                'link' => route('voyager.productos.index'),
            ],
            'image'  => voyager_asset('images/widget-backgrounds/02.jpg'),
        ]));
    }

    public function shouldBeDisplayed()
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        // Detectar rol por relación Voyager o role_id
        try {
            $roleName = optional($user->role)->name;
        } catch (\Throwable $e) {
            $roleName = null;
        }
        if ($roleName) {
            return in_array(strtolower($roleName), ['admin', 'seller'], true);
        }
        if (isset($user->role_id)) {
            return in_array((int) $user->role_id, [1, 3], true);
        }
        return false;
    }
}
