<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable; 
use Illuminate\Contracts\Queue\ShouldQueue; 
use Illuminate\Foundation\Bus\Dispatchable; 
use Illuminate\Queue\InteractsWithQueue; 
use Illuminate\Queue\SerializesModels; 
use App\Services\QrCodeService; 
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class GenerateGuestQrCode implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    

    /**
     * Create a new job instance.
     */
    
}
