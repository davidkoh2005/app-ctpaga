<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'id', 'user_id', 'commerce_id', 'codeUrl', 'name', 'type', 'price', 'quantity', 'statusSale', 'expires_at' // statusSale : 0 cancelado , 1 sin pagar, 3 pagado
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
