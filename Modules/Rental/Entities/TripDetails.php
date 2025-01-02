<?php

namespace Modules\Rental\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TripDetails extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'trip_id' => 'integer',
        'vehicle_id' => 'integer',
        'quantity' => 'integer',
        'scheduled' => 'integer',
        'price' => 'float',
        'discount_on_trip' => 'float',
        'tax_amount' => 'float',
        'estimated_hours' => 'float',
        'distance' => 'float',
    ];

    public function getVehicleDetailsAttribute($value)
    {
        if ($value) {
            return json_decode($value, true);
        }
        return $value;
    }

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trips::class, 'trip_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function tripVehicleDetails(): HasMany
    {
        return $this->hasMany(TripVehicleDetails::class);
    }

//    public function matchingTripVehicles()
//    {
//        return $this->hasManyThrough(Trips::class, TripVehicleDetails::class, 'trip_id', 'id', 'vehicle_id',);
//    }


}
