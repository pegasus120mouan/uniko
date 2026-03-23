<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Contenant;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $q = (string) $request->query('q', '');

        $products = Product::query()
            ->with('category')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($q2) use ($q) {
                    $q2->where('name', 'like', "%{$q}%")
                        ->orWhere('brand', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('products.index', [
            'products' => $products,
            'q' => $q,
        ]);
    }

    public function create(): View
    {
        $categories = Category::query()->orderBy('name')->get();
        $contenants = Contenant::query()->orderBy('type_contenant')->orderBy('ml')->get();

        return view('products.create', [
            'categories' => $categories,
            'contenants' => $contenants,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'contenant_id' => ['nullable', 'integer', 'exists:contenants,id'],
            'image' => ['nullable', 'image', 'max:4096'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = (bool) ($validated['is_active'] ?? false);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }

        if (!empty($validated['contenant_id'])) {
            $contenant = Contenant::query()->find($validated['contenant_id']);
            if ($contenant) {
                $validated['price'] = $contenant->prix;
            }
        }

        Product::create($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Produit créé avec succès.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::query()->orderBy('name')->get();
        $contenants = Contenant::query()->orderBy('type_contenant')->orderBy('ml')->get();

        return view('products.edit', [
            'product' => $product,
            'categories' => $categories,
            'contenants' => $contenants,
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'contenant_id' => ['nullable', 'integer', 'exists:contenants,id'],
            'image' => ['nullable', 'image', 'max:4096'],
            'remove_image' => ['nullable', 'boolean'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = (bool) ($validated['is_active'] ?? false);

        $removeImage = (bool) ($validated['remove_image'] ?? false);
        unset($validated['remove_image']);

        if ($removeImage && $product->image_path) {
            Storage::disk('public')->delete($product->image_path);
            $validated['image_path'] = null;
        }

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }

            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }

        if (!empty($validated['contenant_id'])) {
            $contenant = Contenant::query()->find($validated['contenant_id']);
            if ($contenant) {
                $validated['price'] = $contenant->prix;
            }
        }

        $product->update($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Produit modifié avec succès.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Produit supprimé avec succès.');
    }
}
