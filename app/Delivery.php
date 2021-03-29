<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Delivery extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'deliveries';

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'status', 'codeUrlPaid', 'statusAvailability', 'addressPosition', 'token_fcm', 'model', 'mark', 'colorName', 'colorHex', 'licensePlate', 'idUrl', 'balance', 'type'
    ];
    
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function documents()
    {
        return $this->hasMany('App\Document');
    }

    public function cashes()
    {
        return $this->belongsTo('App\Cash', 'delivery_id');
    }
}
