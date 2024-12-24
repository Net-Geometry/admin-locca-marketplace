<?php

namespace Modules\Rental\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TripVehicleDetails extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'trip_id' => 'integer',
        'vehicle_id' => 'integer',
        'vehicle_identity_id' => 'integer',
        'vehicle_driver_id' => 'integer',
    ];

 
}
