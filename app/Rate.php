<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = [
        'id', 'user_id', 'rate', 'date','roleRate',
    ]; 

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
