<?php

if (! function_exists('rupiah')) {
    /**
     * Format angka menjadi format rupiah. Contoh: 15000 -> Rp15.000
     */
    function rupiah(int|float|null $value): string
    {
        return 'Rp'.number_format((int) $value, 0, ',', '.');
    }
}
