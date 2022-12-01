<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Producto;
//use App\Models\Shop;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    // Permisos Admin al policy de la tiendas con diferentes items para la seguridad
    public function before(User $user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }

    public function browse(User $user){
        return $user->hasRole('seller');
    }

    public function read(User $user, Producto $producto){
        if (empty($producto->shop)) {
            return false;
        }
        return $user->id == $producto->shop->user_id;
    }

    public function edit(User $user, Producto $producto){
        if (empty($producto->shop)) {
            return false;
        }
        return $user->id == $producto->shop->user_id;
    }

    public function add(User $user){
        return $user->hasRole('seller'); //para agregar el user debe ser vendedor
    }

    public function delete(User $user, Producto $producto){
        if (empty($producto->shop)) {
            return false;
        }
        return $user->id == $producto->shop->user_id;
    }
}
