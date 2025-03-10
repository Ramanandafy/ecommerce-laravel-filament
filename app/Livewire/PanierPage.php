<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('panier - DCodeEdson')]

class PanierPage extends Component
{
    public function render()
    {
        return view('livewire.panier-page');
    }
}
