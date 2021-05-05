<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingsBank extends Model
{
    protected $fillable = [
        'id', 'type', 'bank', 'idCard', 'accountNumber', 'accountType', 'phone',
    ];
}
