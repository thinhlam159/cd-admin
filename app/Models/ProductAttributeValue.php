<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    use HasFactory;

    protected $casts = ['id' => 'string'];
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
    public function measureUnit() {
        return $this->belongsTo('App\Models\MeasureUnit', 'measure_unit_id');
    }

    public function productAttribute() {
        return $this->belongsTo('App\Models\ProductAttribute', 'product_attribute_id');
    }

    public function latestProductInventory() {
        return $this->hasOne('App\Models\ProductInventory')->where('is_current', '=', 1);
    }

    public function productAttributePrices() {
        return $this->hasMany('App\Models\ProductAttributePrice');
    }
}
