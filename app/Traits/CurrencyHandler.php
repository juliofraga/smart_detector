<?php

namespace App\Traits;

trait CurrencyHandler
{
    public static function removeCurrencySymbol(string $value, string $currency): string
    {
        return str_replace("$currency ", "", $value);
    }

    public static function handleDecimalValues(string $value): string
    {
        $value = str_replace(".", "", $value);
        $value = str_replace(",", ".", $value);
        return $value;
    }
}