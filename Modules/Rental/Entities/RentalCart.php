<?php

namespace Modules\Rental\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RentalCart extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'provider_id' => 'integer',
        'user_id' => 'integer',
        'module_id' => 'integer',
        'vehicle_id' => 'integer',
        'quantity' => 'integer',
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class,'id','vehicle_id');
    }
}
