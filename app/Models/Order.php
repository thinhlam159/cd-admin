<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = ['id' => 'string'];
    public $incrementing = false;
    protected $dates = ['deleted_at'];

    public function orderProducts()
    {
        return $this->hasMany('App\Models\OrderProduct');
    }
}
