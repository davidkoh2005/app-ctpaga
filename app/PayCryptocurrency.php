<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayCryptocurrency extends Model
{
    protected $fillable = [
        'id', 'paid_id', 'price_cryptocurrency', 'Hash',
    ]; 
}
