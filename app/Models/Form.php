<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'title',
        'fields'
    ];

    protected $casts = [
        'fields' => 'array'
    ];
}
