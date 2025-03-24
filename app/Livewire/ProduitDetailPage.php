<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Produit;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('Produit Detail - DCodeEdson')]

class ProduitDetailPage extends Component
{
    public $slug;
    public $quantity = 1;
    public function mount($slug){
        $this->slug = $slug;
    }
    public function increaseQty(){
        $this->quantity++;
    }
    public function descreaseQty(){
        if($this->quantity>1){
            $this->quantity--;
        }

    }

        //add produit to cart methode
        public function addToCart($produit_id){
            $total_count = CartManagement::addItemsToCartWithQty($produit_id, $this->quantity);

            $this->dispatch('update-cart-count',total_count: $total_count)->to(Navbar::class);

          LivewireAlert::title('Pannier ajouter avec success')
                ->success()
                ->timer(3000)
                ->toast(true)
                ->position('bottom-end')
                ->show();

         }
    public function render()
    {
        return view('livewire.produit-detail-page', [
            'produit' => Produit::where('slug', $this->slug)->firstOrFail(),
        ]);
    }
}
