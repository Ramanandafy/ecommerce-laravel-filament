<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;
    protected $fillable = [
     'user_id',
     'grand_total',
     'payment_method',
     'payment_status',
     'status',
     'currency',
     'shipping_amount',
     'shipping_method',
     'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
    {
        return $this->hasMany(CommandeItem::class);
    }
    public function adresse()
    {
        return $this->hasOne(Adresse::class);
    }

}
