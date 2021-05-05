<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentsBs extends Model
{
    protected $fillable = [
        'id', 'paid_id', 'type', 'bank', 'idCard', 'accountNumber', 'accountType', 'phone',
    ];

}
