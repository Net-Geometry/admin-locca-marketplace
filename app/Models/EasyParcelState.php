<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class EasyParcelState extends Model
{
    use HasFactory;

    public function scopeActive($query): mixed
    {
        return $query->where('status', '=', 1);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(EasyParcelCountry::class, 'easy_parcel_country_id');
    }
    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translationable');
    }
    protected static function booted(): Builder|null
    {
      
        static::addGlobalScope('translate', function (Builder $builder) {
            $builder->with(['translations' => function ($query) {
                return $query->where('locale', app()->getLocale());
            }]);
        });
        return null;
    }
}
