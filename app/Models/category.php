<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'slug',
        'parent_id',
    ];

    protected $casts = ['id' => 'string'];
    public $incrementing = false;
}
