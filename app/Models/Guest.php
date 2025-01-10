<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Concerns\HasVersion7Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'form_data'];

    
    public function setFillable(array $fields)
    {
        $this->fillable = $fields;
    }
}
