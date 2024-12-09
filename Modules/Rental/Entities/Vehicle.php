<?php

namespace Modules\Rental\Entities;

use App\CentralLogics\Helpers;
use App\Models\Storage;
use App\Models\Store;
use App\Models\Translation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $fillable = [];
    protected $appends = ['thumbnail_full_url', 'images_full_url', 'identity_image_full_url'];

    /**
     * @param $query
     * @param $status
     * @return void
     */
    public function scopeOfStatus($query, $status): void
    {
        $query->where('status', '=', $status);
    }

    /**
     * @return BelongsTo
     */
    public function provider(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Store::class, 'provider_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(VehicleCategory::class, 'category_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function brand(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(VehicleBrand::class, 'brand_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function vehicleIdentities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(VehicleIdentity::class);
    }


    /**
     * @return MorphMany
     */
    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translationable');
    }

    /**
     * @param $string
     * @return bool
     */
    private function isValidJson($string): bool
    {
        json_decode($string);
        return (json_last_error() === JSON_ERROR_NONE);
    }

    /**
     * @return MorphMany
     */
    public function storage(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Storage::class, 'data');
    }

    /**
     * @return mixed|string|null
     */
    public function getThumbnailFullUrlAttribute(): mixed
    {
        $value = $this->thumbnail;
        if (count($this->storage) > 0) {
            foreach ($this->storage as $storage) {
                if ($storage['key'] == 'image') {
                    return Helpers::get_full_url('vehicle',$value,$storage['value']);
                }
            }
        }

        return Helpers::get_full_url('vehicle',$value,'public');
    }

    /**
     * @return array
     */
    public function getImagesFullUrlAttribute(): array
    {
        $images = [];
        $value = is_array($this->images)
            ? $this->images
            : ($this->images && is_string($this->images) && $this->isValidJson($this->images)
                ? json_decode($this->images, true)
                : []);
        if ($value){
            foreach ($value as $item){
                $item = is_array($item)?$item:(is_object($item) && get_class($item) == 'stdClass' ? json_decode(json_encode($item), true):['img' => $item, 'storage' => 'public']);
                $images[] = Helpers::get_full_url('vehicle',$item['img'],$item['storage']);
            }
        }

        return $images;
    }

    /**
     * @return array
     */
    public function getDocumentsFullUrlAttribute(): array
    {
        $images = [];
        $value = is_array($this->documents)
            ? $this->documents
            : ($this->documents && is_string($this->documents) && $this->isValidJson($this->documents)
                ? json_decode($this->documents, true)
                : []);
        if ($value){
            foreach ($value as $item){
                $item = is_array($item)?$item:(is_object($item) && get_class($item) == 'stdClass' ? json_decode(json_encode($item), true):['img' => $item, 'storage' => 'public']);
                $images[] = Helpers::get_full_url('vehicle',$item['img'],$item['storage']);
            }
        }

        return $images;
    }

    protected static function newFactory()
    {
        return \Modules\Rental\Database\factories\VehicleFactory::new();
    }
}
