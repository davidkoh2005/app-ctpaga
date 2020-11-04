<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commerce extends Model
{
    //

    protected $fillable = [
        'id', 'user_id', 'rif', 'name', 'address', 'phone',
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
    
}
