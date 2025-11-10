<?php

namespace App\Http\Livewire;

use Livewire\Component;

class HomeComponent extends Component
{
    public function render()
    {
        return view("home.index")->layout("layouts.toolbar");
    }
    public function mount()
    {
        //
    }
    //crear login y register
    public function login()
    {
        return view('auth.login-client');
    }
    public function register()
    {
        return view('auth.register-client');
    }
}
