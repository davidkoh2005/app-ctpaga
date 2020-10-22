<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    //protected $table = 'banks';

    protected $fillable = [
        'user_id', 'coin', 'country', 'accountName', 'accountNumber', 'idCard', 'route', 'swift', 'address', 'bankName', 'accountType',
    ]; 

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
