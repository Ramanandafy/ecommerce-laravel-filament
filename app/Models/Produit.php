<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'categorie_id',
        'marque_id',
        'name',
        'slug',
        'images',
        'description',
        'price',
        'is_active',
        'is_featured',
        'in_stock',
        'on_sale',
    ];
     protected $casts =[
        'images'=> 'array',
     ];
     public function categorie()
     {
         return $this->belongsTo(Categorie::class);
     }
     public function marque()
     {
         return $this->belongsTo(Marque::class);
     }
     public function commandeItems()
     {
         return $this->hasMany(CommandeItem::class);
     }
}
