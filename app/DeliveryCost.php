<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryCost extends Model
{
    protected $fillable = [
        'id', 'state', 'municipalities', 'cost'
    ]; 
}
