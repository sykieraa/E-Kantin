<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Canteen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CanteenStatusController extends Controller
{
    use Concerns;

    public function edit(Request $request): View
    {
        return view('seller.canteen.status', ['canteen' => $this->canteen($request)]);
    }

    public function update(Request $request): RedirectResponse
    {
        $canteen = $this->canteen($request);

        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(Canteen::statusOptions()))],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $canteen->update($data);

        return back()->with('success', 'Status kantin berhasil diperbarui menjadi '.$canteen->statusLabel().'.');
    }
}
