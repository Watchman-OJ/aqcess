<?php

namespace App\Services;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QRGdImage;
use chillerlan\QRCode\Output\QRGdImagePNG; 
use Illuminate\Support\Facades\Log;

class QrCodeService
{
    public function generateEventQrCode($data)
    {
        $options = new QROptions([
            'version'             => 10,
            'outputInterface'     => QRGdImagePNG::class,      
            'scale'               => 10,
            'outputBase64'         => true,
        ]);

        $qrcode = new QRCode($options);
        $imageData = $qrcode->render($data);

        Log::info('Generated Base64 Event QR Code Image', ['base64ImageData' => $imageData]);

        return $imageData;
    }
}