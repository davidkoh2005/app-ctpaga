<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{

    protected $fillable = [
        'user_id', 'description', 'url',
    ]; 

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
