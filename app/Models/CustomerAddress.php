<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $casts = [
        'user_id' => 'integer',
        'zone_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    public function easy_parcel_country()
    {
        return $this->belongsTo(EasyParcelCountry::class, 'easy_parcel_country_id');
    }
    public function easy_parcel_state(){
        return $this->belongsTo(EasyParcelState::class, 'easy_parcel_state_id');
    }

    
}
