<?php

namespace App\Livewire;

use App\Models\Commande;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Stripe\Checkout\Session;
use Stripe\Stripe;
#[Title("Success - DCodeEdson")]
class SuccessPage extends Component
{
    #[Url]
    public $session_id;

    public function render()
    {

       $latest_commande = Commande::with('adresse')->where('user_id', auth()->user()->id)->latest()->first();

       if($this->session_id){
         Stripe::setApiKey(env('STRIPE_SECRET'));
         $session_info = Session::retrieve($this->session_id);

         if($session_info->payment_status != 'paid'){
            $latest_commande->payment_status = 'failed';
            $latest_commande->save();
            return redirect()->route('cancel');
         }else if($session_info->payment_status == 'paid'){
            $latest_commande->payment_status = 'paid';
            $latest_commande->save();
         }
       }
        return view('livewire.success-page',[
            'commande' => $latest_commande,
        ]);
    }
}
