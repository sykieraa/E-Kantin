<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    use Concerns;

    public function index(Request $request): View
    {
        $canteen = $this->canteen($request);

        $products = $canteen->products()
            ->with('category')
            ->when($request->query('q'), fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('seller.products.index', compact('canteen', 'products'));
    }

    public function create(Request $request): View
    {
        $this->canteen($request);

        return view('seller.products.create', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $canteen = $this->canteen($request);

        $data = $request->validated();
        $data['canteen_id'] = $canteen->id;
        $data['is_available'] = $request->boolean('is_available');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('seller.products.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Request $request, Product $product): View
    {
        $this->authorizeProduct($request, $product);

        return view('seller.products.edit', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $this->authorizeProduct($request, $product);

        $data = $request->validated();
        $data['is_available'] = $request->boolean('is_available');

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('seller.products.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $this->authorizeProduct($request, $product);

        // Soft delete: riwayat transaksi lama tetap aman.
        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'Menu berhasil dihapus.');
    }

    /**
     * Ubah cepat stok dari halaman daftar menu.
     */
    public function updateStock(Request $request, Product $product): RedirectResponse
    {
        $this->authorizeProduct($request, $product);

        $data = $request->validate([
            'stock' => ['required', 'integer', 'min:0', 'max:100000'],
        ]);

        $product->update(['stock' => $data['stock']]);

        return back()->with('success', 'Stok '.$product->name.' diperbarui.');
    }

    public function toggleAvailability(Request $request, Product $product): RedirectResponse
    {
        $this->authorizeProduct($request, $product);

        $product->update(['is_available' => ! $product->is_available]);

        return back()->with('success', $product->name.' ditandai '.($product->is_available ? 'tersedia' : 'tidak tersedia').'.');
    }

    private function authorizeProduct(Request $request, Product $product): void
    {
        abort_unless($product->canteen_id === $this->canteen($request)->id, 403, 'Menu ini bukan milik kantin kamu.');
    }
}
