<?php

namespace App\Http\Livewire\Shop;

use Livewire\Component;

class CheckoutComponent extends Component
{
    public $fullname, $address, $city, $state, $zipcode, $phone;

    public function render()
    {
        return view('livewire.shop.checkout-component')
        ->extends('layouts.app')
        ->section('content');
    }

    public function make_order(){
        $this->validate([
            'fullname' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zipcode' => 'required',
            'phone' => 'required',
        ]);
    }
}
