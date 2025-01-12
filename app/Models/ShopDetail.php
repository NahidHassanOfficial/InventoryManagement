<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopDetail extends Model
{
    protected $fillable = [
        'title',
        'logo',
    ];

    use HasFactory;
}
