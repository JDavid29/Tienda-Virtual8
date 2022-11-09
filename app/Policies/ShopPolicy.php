<?php

namespace App\Policies;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShopPolicy
{
    use HandlesAuthorization;

    // Permisos Admin al policy de la tiendas con diferentes items
    public function before(User $user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }

    public function browse(User $user){
        return $user->hasRole('seller');
    }

    public function read(User $user, Shop $shop){
        return $user->id == $shop->user_id;
    }

    public function edit(User $user, Shop $shop){
        return $user->id == $shop->user_id;
    }

    public function add(User $user){

    }

    public function delete(User $user, Shop $shop){
        return $user->id == $shop->user_id;
    }

    // public function browse(User $user)
    // {
    //     return true;
    // }

    // /**
    //  * Determine whether the user can view any models.
    //  *
    //  * @param  \App\Models\User  $user
    //  * @return \Illuminate\Auth\Access\Response|bool
    //  */
    // public function viewAny(User $user)
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can view the model.
    //  *
    //  * @param  \App\Models\User  $user
    //  * @param  \App\Models\Shop  $shop
    //  * @return \Illuminate\Auth\Access\Response|bool
    //  */
    // public function view(User $user, Shop $shop)
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can create models.
    //  *
    //  * @param  \App\Models\User  $user
    //  * @return \Illuminate\Auth\Access\Response|bool
    //  */
    // public function create(User $user)
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can update the model.
    //  *
    //  * @param  \App\Models\User  $user
    //  * @param  \App\Models\Shop  $shop
    //  * @return \Illuminate\Auth\Access\Response|bool
    //  */
    // public function update(User $user, Shop $shop)
    // {
    //     return $user->id == $shop->user_id;
    // }

    // /**
    //  * Determine whether the user can delete the model.
    //  *
    //  * @param  \App\Models\User  $user
    //  * @param  \App\Models\Shop  $shop
    //  * @return \Illuminate\Auth\Access\Response|bool
    //  */
    // public function delete(User $user, Shop $shop)
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can restore the model.
    //  *
    //  * @param  \App\Models\User  $user
    //  * @param  \App\Models\Shop  $shop
    //  * @return \Illuminate\Auth\Access\Response|bool
    //  */
    // public function restore(User $user, Shop $shop)
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can permanently delete the model.
    //  *
    //  * @param  \App\Models\User  $user
    //  * @param  \App\Models\Shop  $shop
    //  * @return \Illuminate\Auth\Access\Response|bool
    //  */
    // public function forceDelete(User $user, Shop $shop)
    // {
    //     //
    // }
}
