<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cryptocurrency extends Model
{
    protected $fillable = [
        'id', 'name', 'symbol', 'baseAsset', 'quoteAsset', 'address', 'publish'
    ]; 
}
