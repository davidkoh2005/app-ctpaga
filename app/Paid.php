<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paid extends Model
{
    protected $fillable = [
        'id', 'user_id', 'commerce_id', 'codeUrl', 'nameClient', 'total', 'coin', 'email', 'nameShipping', 'numberShipping', 'addressShipping', 'detailsShipping', 'selectShipping', 'priceShipping', 'totalShipping', 'percentage', 'nameCompanyPayments', 'date',
    ]; 

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function commerce()
    {
        return $this->belongsTo('App\Commerce', 'commerce_id');
    }

    public function shipping()
    {
        return $this->belongsTo('App\Shipping', 'shipping_id');
    }
}
