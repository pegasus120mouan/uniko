<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\StockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockController extends Controller
{
    public function alerts(Request $request): View
    {
        $q = (string) $request->query('q', '');

        $products = Product::query()
            ->with('category')
            ->whereColumn('quantity', '<=', 'low_stock_threshold')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($q2) use ($q) {
                    $q2->where('name', 'like', "%{$q}%")
                        ->orWhere('brand', 'like', "%{$q}%");
                });
            })
            ->orderBy('quantity')
            ->paginate(10)
            ->withQueryString();

        return view('stock.alerts', [
            'products' => $products,
            'q' => $q,
        ]);
    }

    public function movements(Request $request): View
    {
        $q = (string) $request->query('q', '');
        $type = (string) $request->query('type', '');

        $types = match ($type) {
            'entree' => ['entree', 'adjustment_in'],
            'sortie' => ['sortie', 'adjustment_out'],
            default => $type !== '' ? [$type] : [],
        };

        $movements = StockMovement::query()
            ->with(['product', 'user'])
            ->when($q !== '', function ($query) use ($q) {
                $query->whereHas('product', function ($p) use ($q) {
                    $p->where('name', 'like', "%{$q}%")
                        ->orWhere('brand', 'like', "%{$q}%");
                });
            })
            ->when($types !== [], fn ($query) => $query->whereIn('type', $types))
            ->orderByDesc('moved_at')
            ->paginate(15)
            ->withQueryString();

        return view('stock.movements', [
            'movements' => $movements,
            'q' => $q,
            'type' => $type,
        ]);
    }

    public function adjustForm(Request $request): View
    {
        $productId = $request->query('product_id');

        $products = Product::query()
            ->orderBy('name')
            ->get();

        return view('stock.adjust', [
            'products' => $products,
            'productId' => $productId,
        ]);
    }

    public function adjust(Request $request, StockService $stockService): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'direction' => ['required', 'in:in,out'],
            'quantity' => ['required', 'integer', 'min:1'],
            'comment' => ['nullable', 'string'],
        ]);

        $change = (int) $validated['quantity'] * ($validated['direction'] === 'out' ? -1 : 1);
        $type = $validated['direction'] === 'out' ? 'sortie' : 'entree';

        $product = Product::findOrFail($validated['product_id']);

        $stockService->adjust(
            product: $product,
            quantityChange: $change,
            type: $type,
            user: $request->user(),
            reference: null,
            comment: $validated['comment'] ?? null,
            movedAt: now(),
        );

        return redirect()
            ->route('admin.stock.movements')
            ->with('status', 'Stock mis à jour avec succès.');
    }
}
