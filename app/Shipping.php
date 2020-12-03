<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $fillable = [
        'id', 'user_id', 'description', 'price', 'coin',
    ]; 

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function paid()
    {
        return $this->hasMany('App\Paid');
    }
}
