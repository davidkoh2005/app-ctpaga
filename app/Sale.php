<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'id', 'user_id', 'commerce_id', 'codeUrl', 'name', 'type', 'price', 'quantity', 'statusSale', 'expires_at', 'rate', 'coin', 'coinClient', 'nameClient', 'statusShipping', 'descriptionShipping' // statusSale : 0 sin pagar, 1 pagado
    ]; 

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function commerce()
    {
        return $this->belongsTo('App\Commerce', 'commerce_id');
    }
}
