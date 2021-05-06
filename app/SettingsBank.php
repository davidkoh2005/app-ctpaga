<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingsBank extends Model
{
    protected $fillable = [
        'id', 'type', 'bank', 'idCard', 'accountName', 'accountNumber', 'accountType', 'phone',
    ];
}
