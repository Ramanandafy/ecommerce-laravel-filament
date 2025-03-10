<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adresse extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id',
        'first_name',
        'last_name',
        'phone',
        'street_adress',
        'city',
        'state',
        'zip_code',
       ];
       public function commande()
       {
           return $this->belongsTo(Commande::class);
       }
       public function getFullNameAttribute()
       {
          return "{$this->first_name} {$this->last_name}";
       }
}
