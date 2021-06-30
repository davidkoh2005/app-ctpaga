<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CryptocurrenciesDetail extends Model
{
    protected $fillable = [
        'id', 'cryptocurrencies_id', 'key', 'value',
    ]; 
}
