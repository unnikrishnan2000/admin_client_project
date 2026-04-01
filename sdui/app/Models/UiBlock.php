<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UiBlock extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'status',
        'order',
        'config',
    ];

    protected $casts = [
        'status' => 'boolean',
        'config' => 'array',
    ];
}
