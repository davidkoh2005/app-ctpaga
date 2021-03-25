<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'delivery_id', 'description', 'url',
    ]; 

    public function deliveries()
    {
        return $this->belongsTo('App\Delivery', 'delivery_id');
    }
}
