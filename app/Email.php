<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable = [
        'id','user_id', 'commerce_id', 'date',
    ]; 

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
