<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentsBitcoin extends Model
{
    protected $fillable = [
        'id', 'paid_id', 'price_cryptocurrency', 'hash', 'name', 'baseAsset',
    ]; 
}
