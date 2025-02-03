<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcommerceItemDetails extends Model
{
    use HasFactory;

    protected $casts = [
        'brand_id' => 'integer',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function store()
    {
        return $this->hasOneThrough(
            Store::class,
            Item::class,
            'id', // Foreign key on items table
            'id', // Foreign key on stores table
            'item_id', // Local key on ecommerce_item_details table
            'store_id' // Local key on items table
        );
    }

}
