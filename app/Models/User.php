<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ['firstName', 'lastName', 'email', 'phone', 'password', 'otp'];
    protected $attributes = [
        'otp' => '0',
    ];
    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $user->shopDetails()->create();

        });
    }
    public function shopDetails()
    {
        return $this->hasOne(ShopDetail::class);
    }
}
