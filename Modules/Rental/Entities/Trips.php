<?php

namespace Modules\Rental\Entities;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Guest;
use App\Models\Store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trips extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'user_id' => 'integer',
        'provider_id' => 'integer',
        'zone_id' => 'integer',
        'module_id' => 'integer',
        'cash_back_id' => 'integer',
        'trip_details_id' => 'integer',
        'trip_amount' => 'float',
        'discount_on_trip' => 'float',
        'coupon_discount_amount' => 'float',
        'tax_amount' => 'float',
        'tax_percentage' => 'float',
        'additional_charge' => 'float',
        'partially_paid_amount' => 'float',
        'distance' => 'float',
        'estimated_hours' => 'float',
        'ref_bonus_amount' => 'float',
        'is_guest' => 'integer',
        'edited' => 'integer',
        'checked' => 'integer',
        'scheduled' => 'integer',
        'quantity' => 'integer',
    ];

    public function getPickupLocationAttribute($value)
    {
        if ($value) {
            return json_decode($value, true);
        }
        return $value;
    }
    public function getDestinationLocationAttribute($value)
    {
        if ($value) {
            return json_decode($value, true);
        }
        return $value;
    }
    public function getUserInfoAttribute($value)
    {
        if ($value) {
            return json_decode($value, true);
        }
        return $value;
    }

    public function provider()
    {
        return $this->belongsTo(Store::class,'provider_id');
    }
    public function trip_transaction()
    {
        return $this->hasOne(TripTransaction::class,'trip_id');
    }

    public function trip_details()
    {
        return $this->hasMany(TripDetails::class,'trip_id');
    }

    public function vehicle_identity()
    {
        return $this->hasMany(TripVehicleDetails::class,'trip_id');
    }
    public function guest()
    {
        return $this->belongsTo(Guest::class, 'user_id','id');
    }
    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return HasMany
     */
    public function assignedVehicle(): HasMany
    {
        return $this->hasMany(TripVehicleDetails::class, 'trip_id')->whereNotNull('vehicle_identity_id');
    }

    /**
     * @return HasMany
     */
    public function assignedDriver(): HasMany
    {
        return $this->hasMany(TripVehicleDetails::class, 'trip_id')->whereNotNull('vehicle_driver_id');
    }

    public function scopeProviderTrip($query)
    {
        return $query->where(function ($q) {
            $q->where('trip_type', 'completed');
        });
    }

    /**
     * @return string
     */
    public function getBookingDateAttribute(): string
    {
        return Carbon::parse($this->created_at)->format('d F Y');
    }

    /**
     * @return string
     */
    public function getBookingTimeAttribute(): string
    {
        return Carbon::parse($this->created_at)->format('h:i A');
    }

    /**
     * @return string
     */
    public function getScheduleDateAttribute(): string
    {
        return Carbon::parse($this->schedule_at)->format('d F Y');
    }

    /**
     * @return string
     */
    public function getScheduleTimeAttribute(): string
    {
        return Carbon::parse($this->schedule_at)->format('h:i A');
    }

    public function scopeScheduled($query)
    {
        return $query->whereRaw('created_at <> schedule_at');
    }

    public function scopePending($query)
    {
        return $query->where('trip_status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('trip_status', 'confirmed');
    }

    public function scopeOngoing($query)
    {
        return $query->where('trip_status', 'ongoing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('trip_status', 'completed');
    }

    public function scopeCanceled($query)
    {
        return $query->where('trip_status', 'canceled');
    }

    public function scopePaymentFailed($query)
    {
        return $query->where('trip_status', 'payment_failed');
    }
    public function vehicles()
    {
        return $this->hasManyThrough(Vehicle::class , TripDetails::class,'trip_id','id','id','vehicle_id');
    }


}
