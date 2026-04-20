<?php

namespace App;

use BaconQrCode\Renderer\GDLibRenderer;
use BaconQrCode\Writer;

class QrGenerator
{
    public static function generate(string $data): string
    {
        $renderer = new GDLibRenderer(190, 0);
        $writer = new Writer($renderer);

        // Set header so browser knows it's an image
        header('Content-Type: image/png');

        return base64_encode($writer->writeString($data));
    }

}