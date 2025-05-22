<?php

namespace Modules\TaxVat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaxOnMultiData extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
  public function data()
    {
        return $this->morphTo();
    }

}
