<?php

namespace App\Http\Livewire\Errors;

use Livewire\Component;

class NotFoundComponent extends Component
{
    public function render()
    {
        return view('livewire.errors.not-found-component');
    }
}
