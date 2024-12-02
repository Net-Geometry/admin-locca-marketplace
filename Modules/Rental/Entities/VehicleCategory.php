<?php

namespace Modules\Rental\Entities;

use App\CentralLogics\Helpers;
use App\Models\Storage;
use App\Models\Translation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class VehicleCategory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [];

    protected $appends = ['image_full_url'];

    public function scopeOfStatus($query, $status): void
    {
        $query->where('status', '=', $status);
    }

    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translationable');
    }

    public function storage(): MorphMany
    {
        return $this->morphMany(Storage::class, 'data');
    }

    public function getImageFullUrlAttribute(){
        $value = $this->image;
        if (count($this->storage) > 0) {
            foreach ($this->storage as $storage) {
                if ($storage['key'] == 'image') {
                    return Helpers::get_full_url('category',$value,$storage['value']);
                }
            }
        }

        return Helpers::get_full_url('category',$value,'public');
    }

    protected static function newFactory()
    {
        return \Modules\Rental\Database\factories\VehicleCategoryFactory::new();
    }
}
