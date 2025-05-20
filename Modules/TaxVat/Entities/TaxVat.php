<?php

namespace Modules\TaxVat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaxVat extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_default' => 'integer',
        'is_active' => 'integer',
        'tax_rate' => 'float',

    ];


}
