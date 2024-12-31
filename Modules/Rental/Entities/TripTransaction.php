<?php

namespace Modules\Rental\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TripTransaction extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Rental\Database\factories\TripTransactionFactory::new();
    }
}
