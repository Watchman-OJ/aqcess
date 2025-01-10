<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'label', 'type', 'placeholder', 'option'];

    protected $casts = [
        'options' => 'array'
    ];
}
