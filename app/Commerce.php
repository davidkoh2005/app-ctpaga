<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commerce extends Model
{
    //

    protected $fillable = [
        'id', 'user_id', 'rif', 'name', 'address', 'phone', 'userUrl',
    ]; 

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function category()
    {
        return $this->hasMany('App\Category');
    }

    public function product()
    {
        return $this->hasMany('App\Product');
    }

    public function service()
    {
        return $this->hasMany('App\Service');
    }

    public function shipping()
    {
        return $this->hasMany('App\Shipping');
    }
    
}
