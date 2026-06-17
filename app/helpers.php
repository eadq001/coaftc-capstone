<?php

function format_qty(float|int|null $value): string
{
    if ($value === null) {
        return '0';
    }

    $formatted = number_format((float) $value, 2);

    return rtrim(rtrim($formatted, '0'), '.');
}
