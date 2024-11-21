<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasVersion7Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Guest extends Model
{
    use HasVersion7Uuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'event_id',
        'name',
        'sex',
        'guest_cap',
        'note',
    ];

    
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
