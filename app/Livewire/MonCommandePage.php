<?php

namespace App\Livewire;

use App\Models\Commande;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title("Mon Commande")]
class MonCommandePage extends Component
{
    use WithPagination;
    public function render()
    {
        $mon_commandes = Commande::where('user_id', auth()->id())->latest()->paginate(5);
        return view('livewire.mon-commande-page', [
            'commandes' => $mon_commandes,
        ]);
    }
}
