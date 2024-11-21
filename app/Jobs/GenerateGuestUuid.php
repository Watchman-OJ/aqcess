<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable; 
use Illuminate\Contracts\Queue\ShouldQueue; 
use Illuminate\Foundation\Bus\Dispatchable; 
use Illuminate\Queue\InteractsWithQueue; 
use Illuminate\Queue\SerializesModels; 
use Illuminate\Support\Facades\Redis; 
use Ramsey\Uuid\Uuid;

class GenerateGuestUuid implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    
}
