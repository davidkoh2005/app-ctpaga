<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'id', 'user_id', 'code', 'percentage',
    ]; 

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
