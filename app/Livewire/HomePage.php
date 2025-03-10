<?php

namespace App\Livewire;

use App\Models\Categorie;
use App\Models\Marque;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Acceui - DCodeEdson')]
class HomePage extends Component
{
    public function render()
    {
        $marques = Marque::where('is_active', 1)->get();
        $categories = Categorie::where('is_active',1)->get();

        return view('livewire.home-page', [
            'marques' => $marques,
            'categories' => $categories
        ]);
    }
}
