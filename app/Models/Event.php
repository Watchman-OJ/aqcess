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
        'date',
        'time',
        'location',
        'description',
        'check_in_option',
    ];

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }
}
