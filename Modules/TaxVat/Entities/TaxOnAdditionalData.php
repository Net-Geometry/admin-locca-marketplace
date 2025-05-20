<?php

namespace Modules\TaxVat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PHPUnit\Event\Telemetry\System;

class TaxOnAdditionalData extends Model
{
    use HasFactory;


    protected $guarded = ['id'];
    protected $casts = [
        'is_default' => 'integer',
        'is_active' => 'integer',
        'is_included' => 'integer',
        'system_tax_vat_id' => 'integer',
        'tax_vat_ids' => 'array',
    ];

//  public function getTaxVatIdsAttribute($value)
//     {
//         if ($value) {
//             return json_decode($value, true);
//         }
//         return $value;
//     }

  public function systemTaxVat():BelongsTo
    {
        return $this->belongsTo(SystemTaxVat::class);
    }

}
