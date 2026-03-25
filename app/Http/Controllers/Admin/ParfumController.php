<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contenant;
use App\Models\Parfum;
use App\Models\ParfumPrice;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParfumController extends Controller
{
    public function index(Request $request): View
    {
        $q = (string) $request->query('q', '');
        $type = $request->query('type');

        $parfums = Parfum::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('code', 'like', "%{$q}%")
                    ->orWhere('nom', 'like', "%{$q}%");
            })
            ->when($type !== null && in_array($type, ['classics', 'luxe']), function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->orderBy('nom')
            ->paginate(10)
            ->withQueryString();

        return view('parfums.index', [
            'parfums' => $parfums,
            'q' => $q,
            'type' => $type,
        ]);
    }

    public function create(): View
    {
        return view('parfums.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:10', 'unique:parfums,code'],
            'nom' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:classics,luxe'],
        ]);

        $parfum = Parfum::create($validated);

        Product::query()->create([
            'parfum_id' => $parfum->id,
            'category_id' => null,
            'name' => $parfum->nom,
            'brand' => 'Parfum',
            'price' => 0,
            'quantity' => 0,
            'low_stock_threshold' => 5,
            'description' => null,
            'is_active' => true,
        ]);

        return redirect()
            ->route('admin.parfums.index')
            ->with('status', 'Parfum créé avec succès.');
    }

    public function show(Parfum $parfum): View
    {
        $parfum->load('prices.contenant');
        
        // Get contenants matching the parfum type that don't have a price yet
        $availableContenants = Contenant::query()
            ->where('type', $parfum->type)
            ->whereNotIn('id', $parfum->prices->pluck('contenant_id'))
            ->orderBy('ml')
            ->get();

        return view('parfums.show', [
            'parfum' => $parfum,
            'availableContenants' => $availableContenants,
        ]);
    }

    public function edit(Parfum $parfum): View
    {
        return view('parfums.edit', [
            'parfum' => $parfum,
        ]);
    }

    public function update(Request $request, Parfum $parfum): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:10', 'unique:parfums,code,' . $parfum->id],
            'nom' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:classics,luxe'],
        ]);

        $parfum->update($validated);

        $product = Product::query()->firstWhere('parfum_id', $parfum->id);

        if ($product) {
            $product->update([
                'name' => $parfum->nom,
            ]);
        } else {
            Product::query()->create([
                'parfum_id' => $parfum->id,
                'category_id' => null,
                'name' => $parfum->nom,
                'brand' => 'Parfum',
                'price' => 0,
                'quantity' => 0,
                'low_stock_threshold' => 5,
                'description' => null,
                'is_active' => true,
            ]);
        }

        return redirect()
            ->route('admin.parfums.index')
            ->with('status', 'Parfum modifié avec succès.');
    }

    public function destroy(Parfum $parfum): RedirectResponse
    {
        $product = Product::query()->firstWhere('parfum_id', $parfum->id);

        $parfum->delete();

        if ($product) {
            $hasHistory = $product->saleItems()->exists() || $product->stockMovements()->exists();

            if ($hasHistory) {
                $product->update([
                    'parfum_id' => null,
                    'is_active' => false,
                ]);
            } else {
                $product->delete();
            }
        }

        return redirect()
            ->route('admin.parfums.index')
            ->with('status', 'Parfum supprimé avec succès.');
    }

    public function storePrice(Request $request, Parfum $parfum): RedirectResponse
    {
        $validated = $request->validate([
            'contenant_id' => ['required', 'integer', 'exists:contenants,id'],
            'prix' => ['required', 'integer', 'min:0'],
        ]);

        ParfumPrice::create([
            'parfum_id' => $parfum->id,
            'contenant_id' => $validated['contenant_id'],
            'prix' => $validated['prix'],
        ]);

        return redirect()
            ->route('admin.parfums.show', $parfum)
            ->with('status', 'Prix ajouté avec succès.');
    }

    public function destroyPrice(Parfum $parfum, ParfumPrice $price): RedirectResponse
    {
        if ($price->parfum_id !== $parfum->id) {
            abort(404);
        }

        $price->delete();

        return redirect()
            ->route('admin.parfums.show', $parfum)
            ->with('status', 'Prix supprimé avec succès.');
    }
}
