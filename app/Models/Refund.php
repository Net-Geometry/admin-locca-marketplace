<?php

namespace App\Models;

use App\CentralLogics\Helpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\DB;

class Refund extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    protected $casts = [
        'refund_amount' => 'float',
        'order_id' => 'integer',
        'user_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',

    ];


    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function storage(): MorphOne
    {
        return $this->morphOne(Storage::class, 'data');
    }
    protected static function booted()
    {

        static::saved(function ($model) {
            $value = Helpers::getDisk();

            DB::table('storages')->updateOrInsert([
                'data_type' => get_class($model),
                'data_id' => $model->id,
            ], [
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }
}
