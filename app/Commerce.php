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

}
