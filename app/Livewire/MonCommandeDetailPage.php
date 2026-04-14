<?php

namespace App\Livewire;

use App\Models\Adresse;
use App\Models\Commande;
use App\Models\CommandeItem;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Commande Tetails")]
class MonCommandeDetailPage extends Component
{
    public $commande_id;
    public function mount($commande_id){
      $this->commande_id = $commande_id;

    }
    public function render()
    {
        $commande_items = CommandeItem::with('produit')->where('commande_id', $this->commande_id)->get();
        $adresse = Adresse::where('commande_id', $this->commande_id)->first();
        $commande = Commande::where('id', $this->commande_id)->first();
        return view('livewire.mon-commande-detail-page',[
            'commande_items' => $commande_items,
            'adresse' => $adresse,
            'commande' => $commande
        ]);
    }
}
