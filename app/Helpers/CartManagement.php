<?php

namespace App\Helpers;
use App\Models\Produit;
use Illuminate\Support\Facades\Cookie;



class CartManagement{
    // add item to cart

    static public function addItemsToCart($produit_id){
    $cart_items = self::getCartItemsFromCookie();

    if(!is_array($cart_items)){
        $cart_items = [];
    }
    $existing_item = null;

    foreach($cart_items as $key => $item){
        if(isset($item['produit_id']) && $item['produit_id']  == $produit_id){
            $existing_item = $key;
            break;
        }
    }


    if($existing_item !== null){
        $cart_items[$existing_item]['quantity'] ++ ;
        $cart_items[$existing_item]['total_amount'] = $cart_items[$existing_item]['quantity'] * $cart_items[$existing_item]['unit_amount'];

    } else{
        $produit = Produit::where('id', $produit_id)->first(['id', 'name', 'price', 'images']);
        if($produit){
            $cart_items[] = [
          'produit_id' => $produit_id,
          'name' => $produit->name,
          'image' => $produit->images[0],
          'quantity' => 1,
          'unit_amount' => $produit->price,
          'total_amount' => $produit->price

            ];
        }
    }
    self::addCartItemsToCookie($cart_items);
    return count($cart_items);
    }

 // add item to cart with Qqty

 static public function addItemsToCartWithQty($produit_id, $qty = 1){
    $cart_items = self::getCartItemsFromCookie();

    if(!is_array($cart_items)){
        $cart_items = [];
    }
    $existing_item = null;

    foreach($cart_items as $key => $item){
        if($item['produit_id'] == $produit_id){
            $existing_item = $key;
            break;
        }
    }


    if($existing_item !== null){
        $cart_items[$existing_item]['quantity'] = $qty ;
        $cart_items[$existing_item]['total_amount'] = $cart_items[$existing_item]['quantity'] * $cart_items[$existing_item]['unit_amount'];

    } else{
        $produit = Produit::where('id', $produit_id)->first(['id', 'name', 'price', 'images']);
        if($produit){
            $cart_items[] = [
          'produit_id' => $produit_id,
          'name' => $produit->name,
          'image' => $produit->images[0],
          'quantity' => $qty,
          'unit_amount' =>$produit->price,
          'total_amount' => $produit->price

            ];
        }
    }
    self::addCartItemsToCookie($cart_items);
    return count($cart_items);
    }

    //remove item from cart
    static public function removeCartItems($produit_id){
     $cart_items = self::getCartItemsFromCookie();

    foreach($cart_items as $key => $item){
        if($item['produit_id'] == $produit_id){
            unset($cart_items[$key]);
        }
    }
    self::addCartItemsToCookie($cart_items);
    return ($cart_items);
    }

    //add cart items to cookie
     static public function addCartItemsToCookie($cart_items){
       Cookie::queue('cart_items', json_encode($cart_items), 60 * 24 * 30);

     }

    //clear cart from cookie
    static public function clearCartFromItems(){
        cookie::queue(Cookie::forget('cart_items'));
    }

    //get all items from cookie
    static public function getCartItemsFromCookie(){
        $cart_items = json_decode(Cookie::get('cart_items'), true);
        if(!$cart_items){
            $cart_items = [];
        }

        return $cart_items;
    }

    //increment item from quantity
    static public function incrementQuantityToCartItem($produit_id){
   $cart_items = self::getCartItemsFromCookie();

   foreach($cart_items as $key => $item){
    if($item['produit_id'] == $produit_id){
    $cart_items[$key]['quantity']++;
    $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] * $cart_items[$key]['unit_amount'];
    }
   }
   self::addCartItemsToCookie($cart_items);
   return $cart_items;
    }

    //decriment item from quantity
    static public function decrementQuantityToCartItem($produit_id){
        $cart_items = self::getCartItemsFromCookie();

        foreach($cart_items as $key => $item){
            if($item['produit_id'] == $produit_id){
                if($cart_items[$key]['quantity'] > 1){
                    $cart_items[$key]['quantity']--;
                    $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] *
                    $cart_items[$key]['unit_amount'];
                }
            }
        }
        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }

    //calculer grand total
    static public function calculateGrandTotal($items){
        return array_sum(array_column($items, 'total_amount'));
    }
}
