<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function measureUnit() {
        return $this->belongsTo('App\Models\MeasureUnit');
    }

    public function productAttribute() {
        return $this->belongsTo('App\Models\ProductAttribute');
    }
}
