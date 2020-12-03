<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    //protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'email', 'password', 'address', 'phone',
    ]; 

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function banks()
    {
        return $this->hasMany('App\Bank');
    }

    public function pictures()
    {
        return $this->hasMany('App\Picture');
    }

    public function commerces()
    {
        return $this->hasMany('App\Commerce');
    }
    
    public function product()
    {
        return $this->hasMany('App\Product');
    }

    public function service()
    {
        return $this->hasMany('App\Service');
    }

    public function shipping()
    {
        return $this->hasMany('App\Shipping');
    }

    public function discount()
    {
        return $this->hasMany('App\Discount');
    }

    public function rate()
    {
        return $this->hasMany('App\Rate');
    }

    public function sale()
    {
        return $this->hasMany('App\Sale');
    }

    public function paid()
    {
        return $this->hasMany('App\Paid');
    }
}