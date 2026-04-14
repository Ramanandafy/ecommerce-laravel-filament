<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Mail\CommandePlaced;
use App\Models\Adresse;
use App\Models\Commande;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Title;
use Livewire\Component;
use Stripe\Checkout\Session;
use Stripe\Stripe;

#[Title('Verifier')]
class VerifiéPage extends Component
{
    public $first_name;
    public $last_name;
    public $phone;
    public $street_adress;
    public $city;
    public $state;
    public $zip_code;
    public $payment_method;

    public function mount(){
        $cart_items = CartManagement::getCartItemsFromCookie();
        if(count($cart_items) == 0){
            return redirect('/produits');
        }
    }
    public function placeOrder(){
       $this->validate([
         'first_name' => 'required',
         'last_name' => 'required',
         'phone' => 'required',
         'street_adress' => 'required',
         'city' => 'required',
         'state' => 'required',
         'zip_code' => 'required',
         'payment_method' => 'required',
       ]);
       $cart_items = CartManagement::getCartItemsFromCookie();
       $line_items = [];
       foreach($cart_items as $item){
        $line_items[] = [
           'price_data' => [
            'currency' => 'eur',
            'unit_amount' => $item['unit_amount'] * 100,
            'product_data' => [
            'name' => $item['name'],
            ]
            ],
            'quantity' => $item['quantity'],
        ];
       }
       $commande = new Commande();
       $commande->user_id = auth()->user()->id;
       $commande->grand_total = CartManagement::calculateGrandTotal($cart_items);
       $commande->payment_method = $this->payment_method;
       $commande->payment_status = 'pending';
       $commande->status = 'new';
       $commande->currency = 'eur';
       $commande->shipping_amount = 0;
       $commande->shipping_method = 'none';
       $commande->notes = 'Commande placer pour' . auth()->user()->name;

       $adress = new Adresse();
       $adress->first_name = $this->first_name;
       $adress->last_name = $this->last_name;
       $adress->phone = $this->phone;
       $adress->street_adress = $this->street_adress;
       $adress->city = $this->city;
       $adress->state = $this->state;
       $adress->zip_code = $this->zip_code;

       $redirect_url = '';

       if($this->payment_method == 'stripe'){
         Stripe::setApiKey(env('STRIPE_SECRET'));
         $sessionChekout = Session::create([
           'payment_method_types' => ['card'],
           'customer_email' => auth()->user()->email,
           'line_items' => $line_items,
           'mode' => 'payment',
           'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
           'cancel_url' => route('cancel'),
         ]);
         $redirect_url = $sessionChekout->url;
       }else{
         $redirect_url = route('success');
       }
        $commande->save();
        $adress->commande_id = $commande->id;
        $adress->save();
        $commande->items()->createMany($cart_items);
        CartManagement::clearCartFromItems();
        Mail::to(request()->user())->send(new CommandePlaced($commande));
        return redirect($redirect_url);
    }
    public function render()
    {
        $cart_items = CartManagement::getCartItemsFromCookie();
        $grand_total = CartManagement::calculateGrandTotal($cart_items);
        return view('livewire.verifié-page', [
            'cart_items' => $cart_items,
            'grand_total' => $grand_total,
        ]);
    }
}
