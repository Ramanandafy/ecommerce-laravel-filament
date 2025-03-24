<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Marque;
use App\Models\Categorie;
use App\Models\Produit;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Produit - DCodeEdson')]

class ProduitsPage extends Component
{


    use WithPagination;
    #[Url()]
    public $selected_categories = [];
    #[Url()]
    public $selected_marques = [];
    #[Url()]
    public $featured;
    #[Url()]
    public $on_sale;
    #[Url()]
    public $price_range = 3000;
    #[Url()]
    public $sort = 'latest';
    //add produit to cart methode
    public function addToCart($produit_id){
       $total_count = CartManagement::addItemsToCart($produit_id);

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
    $produitQuery = Produit::query()->where('is_active', 1);

        if(!empty($this->selected_categories)){
            $produitQuery->whereIn('categorie_id', $this->selected_categories);
        }

        if(!empty($this->selected_marques)){
            $produitQuery->whereIn('marque_id', $this->selected_marques);
        }

        if($this->featured){
            $produitQuery->where('is_featured', 1);
        }
        if($this->on_sale){
            $produitQuery->where('on_sale', 1);
        }
        if($this->price_range){
            $produitQuery->whereBetween('price',[0, $this->price_range]);
        }
        if($this->sort == 'latest'){
            $produitQuery->latest();
        }
        if($this->sort == 'price'){
            $produitQuery->orderBy('price');
        }

        return view('livewire.produits-page',[
         'produits' => $produitQuery->paginate(9),
         'marques' => Marque::where('is_active', 1)->get(['id', 'name', 'slug']),
         'categories' => Categorie::where('is_active', 1)->get(['id', 'name', 'slug']),
        ]);
    }
}
