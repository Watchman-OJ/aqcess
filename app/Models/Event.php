<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'fields',
    ];

    protected $casts = [
        'fields' => 'array',
    ];

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }
}
