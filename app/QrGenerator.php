<?php

namespace App;

use BaconQrCode\Renderer\GDLibRenderer;
use BaconQrCode\Writer;

class QrGenerator
{
    public static function generate(string $data, int $size): string
    {
        $renderer = new GDLibRenderer($size, 0);
        $writer = new Writer($renderer);

        return base64_encode($writer->writeString($data));
    }
}
