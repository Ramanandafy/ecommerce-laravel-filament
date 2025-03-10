<?php

namespace App\Livewire;

use App\Models\Produit;
use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('Produit Detail - DCodeEdson')]

class ProduitDetailPage extends Component
{
    public $slug;
    public function mount($slug){
        $this->slug = $slug;
    }
    public function render()
    {
        return view('livewire.produit-detail-page', [
            'produit' => Produit::where('slug', $this->slug)->firstOrFail(),
        ]);
    }
}
