<?php

namespace App\Http\Controllers\Seller;

use App\Models\Canteen;
use Illuminate\Http\Request;

/**
 * Helper bersama untuk semua controller penjual.
 * Memastikan penjual selalu bekerja pada kantinnya sendiri.
 */
trait Concerns
{
    protected function canteen(Request $request): Canteen
    {
        $canteen = $request->user()->canteen;

        abort_if(! $canteen, 403, 'Akun penjual ini belum dihubungkan ke kantin mana pun. Hubungi admin.');

        return $canteen;
    }
}
