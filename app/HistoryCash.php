<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryCash extends Model
{
    protected $fillable = [
        'id', 'delivery_id', 'total', 'date',
    ]; 

    public function deliveries()
    {
        return $this->hasOne('App\Delivery');
    }
}
