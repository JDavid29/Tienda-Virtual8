<?php

namespace App\Http\Livewire\Shop;

use App\Models\User;
use Livewire\Component;
use App\Mail\ShopActivationRequest;
use Illuminate\Support\Facades\Mail;

class RegisterComponent extends Component
{
    public $name, $description;

    public function render()
    {
        return view('livewire.shop.register-component')
        ->extends('layouts.app')
        ->section('content');
    }

    public function registerShop(){
        $this->validate([
            'name'=> 'required'
        ]);
        $shop = auth()->user()->shop()->create([
            'name' => $this->name,
            'description' => $this->description,
        ]);
        $admins = User::whereHas('role', function($q){
            $q->where('name', 'admin');
        })->get();

        Mail::to($admins)->send(new ShopActivationRequest($shop));
        //enviar mensaje

        // redireccionar la ruta
        return redirect()->route('shop.index');
    }

}
