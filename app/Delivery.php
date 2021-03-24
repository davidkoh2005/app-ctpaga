<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Delivery extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'status', 'codeUrlPaid', 'statusAvailability', 'addressPosition', 'token_fcm', 'model', 'mark', 'colorName', 'colorHex', 'licensePlate',
    ];
    
    protected $hidden = [
        'password', 'remember_token',
    ];
}
