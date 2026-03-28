<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contenant;
use App\Models\Grossiste;
use App\Models\GrossistePrice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GrossisteController extends Controller
{
    public function index(Request $request): View
    {
        $q = (string) $request->query('q', '');

        $grossistes = Grossiste::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('nom', 'like', "%{$q}%")
                    ->orWhere('entreprise', 'like', "%{$q}%")
                    ->orWhere('telephone', 'like', "%{$q}%");
            })
            ->orderBy('nom')
            ->paginate(10)
            ->withQueryString();

        return view('grossistes.index', [
            'grossistes' => $grossistes,
            'q' => $q,
        ]);
    }

    public function create(): View
    {
        return view('grossistes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'nom' => trim($request->input('nom')),
        ]);

        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'entreprise' => ['nullable', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'adresse' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Grossiste::create($validated);

        return redirect()
            ->route('admin.grossistes.index')
            ->with('status', 'Grossiste créé avec succès.');
    }

    public function show(Grossiste $grossiste): View
    {
        $grossiste->load('prices.contenant');

        $availableContenants = Contenant::query()
            ->whereNotIn('id', $grossiste->prices->pluck('contenant_id'))
            ->orderBy('type')
            ->orderBy('ml')
            ->get();

        return view('grossistes.show', [
            'grossiste' => $grossiste,
            'availableContenants' => $availableContenants,
        ]);
    }

    public function edit(Grossiste $grossiste): View
    {
        return view('grossistes.edit', [
            'grossiste' => $grossiste,
        ]);
    }

    public function update(Request $request, Grossiste $grossiste): RedirectResponse
    {
        $request->merge([
            'nom' => trim($request->input('nom')),
        ]);

        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'entreprise' => ['nullable', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'adresse' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $grossiste->update($validated);

        return redirect()
            ->route('admin.grossistes.index')
            ->with('status', 'Grossiste modifié avec succès.');
    }

    public function destroy(Grossiste $grossiste): RedirectResponse
    {
        $grossiste->delete();

        return redirect()
            ->route('admin.grossistes.index')
            ->with('status', 'Grossiste supprimé avec succès.');
    }

    public function storePrice(Request $request, Grossiste $grossiste): RedirectResponse
    {
        $validated = $request->validate([
            'contenant_id' => ['required', 'integer', 'exists:contenants,id'],
            'prix' => ['required', 'integer', 'min:0'],
        ]);

        GrossistePrice::create([
            'grossiste_id' => $grossiste->id,
            'contenant_id' => $validated['contenant_id'],
            'prix' => $validated['prix'],
        ]);

        return redirect()
            ->route('admin.grossistes.show', $grossiste)
            ->with('status', 'Prix ajouté avec succès.');
    }

    public function destroyPrice(Grossiste $grossiste, GrossistePrice $price): RedirectResponse
    {
        if ($price->grossiste_id !== $grossiste->id) {
            abort(404);
        }

        $price->delete();

        return redirect()
            ->route('admin.grossistes.show', $grossiste)
            ->with('status', 'Prix supprimé avec succès.');
    }
}
