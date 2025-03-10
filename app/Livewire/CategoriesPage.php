<?php

namespace App\Livewire;

use App\Models\Categorie;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Categorie - DCodeEdson')]
class CategoriesPage extends Component
{
    public function render()
    {
        $categories = Categorie::where('is_active', 1)->get();
        return view('livewire.categories-page',[
            'categories' => $categories,
        ]);
    }
}
