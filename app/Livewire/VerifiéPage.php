<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use Livewire\Attributes\Title;
use Livewire\Component;

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
