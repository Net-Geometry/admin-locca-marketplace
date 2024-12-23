<?php

namespace Modules\Rental\Entities;

use Illuminate\Database\Eloquent\Model;
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

}
