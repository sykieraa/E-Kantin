<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Canteen;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Admin hanya memantau produk, pengelolaan tetap di sisi penjual.
     */
    public function index(Request $request): View
    {
        $products = Product::with('canteen', 'category')
            ->when($request->query('q'), fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->when($request->query('canteen'), fn ($q, $c) => $q->where('canteen_id', $c))
            ->orderBy('name')
            ->paginate(20)->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'canteens' => Canteen::orderBy('name')->get(),
        ]);
    }
}
