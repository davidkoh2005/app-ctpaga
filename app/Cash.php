<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    protected $fillable = [
        'id', 'delivery_id', 'paid_id', 'status',
    ]; 

    public function deliveries()
    {
        return $this->hasOne('App\Delivery');
    }

    public function paids()
    {
        return $this->hasOne('App\Paid');
    }
}
