<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class ContainerOrder extends Model
{
    use HasFactory;
    use Notifiable, SoftDeletes;

    protected $guarded = [];
    protected $casts = ['id' => 'string'];
    public $incrementing = false;
    protected $dates = ['deleted_at'];
}
