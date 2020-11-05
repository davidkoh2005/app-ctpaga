<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $fillable = [
        'id', 'commerce_id', 'name', 'type',
    ]; 

    public function commerce()
    {
        return $this->belongsTo('App\Commerce', 'commerce_id');
    }
}
