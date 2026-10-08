<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Canteen;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CanteenController extends Controller
{
    public function index(Request $request): View
    {
        return app(DashboardController::class)->index($request);
    }

    public function show(Request $request, Canteen $canteen): View
    {
        $search = trim((string) $request->query('q'));
        $categoryId = $request->query('category');

        $products = $canteen->products()
            ->with('category')
            ->when($search !== '', fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('student.canteens.show', compact('canteen', 'products', 'categories', 'search', 'categoryId'));
    }
}
