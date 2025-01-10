<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldConfig extends Model
{
    use HasFactory;

    protected $fillable = ['category', 'fields'];

    protected $casts = [
        'fields' => 'array'
    ];
}
