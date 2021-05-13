<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentsZelle extends Model
{
    protected $fillable = [
        'id', 'paid_id', 'nameAccount', 'idConfirm', 'date_created', 
    ];

}
