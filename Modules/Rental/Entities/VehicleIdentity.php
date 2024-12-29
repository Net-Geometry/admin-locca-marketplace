<?php

namespace Modules\Rental\Entities;

use App\Models\Store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleIdentity extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * @return BelongsTo
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'provider_id', 'id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'id');
    }
    public function vehicle_trip_details(): BelongsTo
    {
        return $this->belongsTo(TripVehicleDetails::class, 'id', 'vehicle_identity_id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(VehicleDriver::class, 'vehicle_driver_id', 'id');
    }

}
