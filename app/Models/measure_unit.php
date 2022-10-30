<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class measure_unit extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = ['id' => 'string'];
    public $incrementing = false;
}
