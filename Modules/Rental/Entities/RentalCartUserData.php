<?php

namespace Modules\Rental\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RentalCartUserData extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $guarded = ['id'];
    protected $casts = [
        'user_id' => 'integer',
    ];

}
