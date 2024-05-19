<?php

namespace App\Models;

use App\CentralLogics\Helpers;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class VendorEmployee extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['remember_token'];

    protected $casts = [
        'employee_role_id' => 'integer',
        'vendor_id' => 'integer',
        'store_id' => 'integer',
        // 'status' => 'integer',
        'is_logged_in' => 'boolean',
    ];

    protected $hidden = [
        'password',
        'auth_token',
        'remember_token',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function role(){
        return $this->belongsTo(EmployeeRole::class,'employee_role_id');
    }

    public function storage(): MorphOne
    {
        return $this->morphOne(Storage::class, 'data');
    }

    protected static function booted()
    {
        static::addGlobalScope('storage', function ($builder) {
            $builder->with('storage');
        });
    }

    protected static function boot()
    {
        parent::boot();
        static::saved(function ($model) {
            if($model->isDirty('image')){
                $value = Helpers::getDisk();

                DB::table('storages')->updateOrInsert([
                    'data_type' => get_class($model),
                    'data_id' => $model->id,
                ], [
                    'value' => $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
