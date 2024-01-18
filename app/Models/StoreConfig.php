<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class StoreConfig extends Model
{
    use HasFactory;
    protected $table;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = $this->isTableNamedStoreConfigs() ? 'storeConfigs' : 'store_configs';
    }
    protected function isTableNamedStoreConfigs()
    {
        return $this->getConnection()->getDoctrineSchemaManager()->listTableNames() === ['storeConfigs'];
    }
    protected $guarded = ['id'];

    protected $casts = [
        'store_id' => 'integer',
        'is_recommended' => 'boolean',
        'is_recommended_deleted' => 'boolean',
    ];

    public function Store()
    {
        return $this->belongsTo(Store::class);
    }
}
