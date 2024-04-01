<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CashBack extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'same_user_limit' => 'integer',
        'total_used' => 'integer',
        'cashback_amount' => 'float',
        'min_purchase' => 'float',
        'max_discount' => 'float',
        'status' => 'boolean',
        'customer_id' => 'array',
    ];


    public function translations()
    {
        return $this->morphMany(Translation::class, 'translationable');
    }


    public function getTitleAttribute($value): string
    {
        if (count($this->translations) > 0) {
            foreach ($this->translations as $translation) {
                if ($translation['key'] == 'title') {
                    return $translation['value'];
                }
            }
        }

        return $value;
    }

    protected static function booted()
    {
        static::addGlobalScope('translate', function (Builder $builder) {
            $builder->with(['translations' => function ($query) {
                return $query->where('locale', app()->getLocale());
            }]);
        });
    }
}
