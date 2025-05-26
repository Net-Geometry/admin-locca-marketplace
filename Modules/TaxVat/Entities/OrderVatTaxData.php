<?php

namespace Modules\TaxVat\Entities;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderVatTaxData extends Model
{
    use HasFactory;

     protected $guarded = ['id'];

    protected $casts = [
            'tax_percentage' => 'float',
            'tax_amount' => 'float',
            'before_tax_amount' => 'float',
            'after_tax_amount' => 'float',
            'order_id' => 'integer',
            'tax_vat_id' => 'integer',
            'system_tax_vat_id' => 'integer',
        ];


    public function Orders(): HasMany
    {
        return $this->hasMany(Order::class, 'order_id');
    }

}
