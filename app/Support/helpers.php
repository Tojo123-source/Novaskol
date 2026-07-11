<?php

use Illuminate\Support\Facades\DB;

if (! function_exists('novaskol_currency')) {
    function novaskol_currency(): string
    {
        static $currency = null;

        if ($currency !== null) {
            return $currency;
        }

        try {
            $symbol = DB::table('parametres')->where('cle', 'devise_symbole')->value('valeur');
            $currency = trim((string) $symbol) !== '' ? trim((string) $symbol) : 'Ar';
        } catch (Throwable) {
            $currency = 'Ar';
        }

        return $currency;
    }
}

if (! function_exists('novaskol_money')) {
    function novaskol_money(float|int|string|null $amount, int $decimals = 0): string
    {
        return number_format((float) $amount, $decimals, ',', ' ').' '.novaskol_currency();
    }
}

if (! function_exists('novaskol_concat')) {
    function novaskol_concat(string ...$parts): string
    {
        return DB::getDriverName() === 'sqlite'
            ? implode(' || ', $parts)
            : 'CONCAT('.implode(', ', $parts).')';
    }
}
