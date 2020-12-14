<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    protected $fillable = [
        'id', 'user_id', 'commerce_id', 'coin', 'total',
    ]; 

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function commerce()
    {
        return $this->belongsTo('App\Commerce', 'commerce_id');
    }
}
