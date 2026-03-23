<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commune;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommuneController extends Controller
{
    public function index(Request $request): View
    {
        $q = (string) $request->query('q', '');

        $communes = Commune::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('nom', 'like', "%{$q}%")
                    ->orWhere('cout_livraison', 'like', "%{$q}%");
            })
            ->orderBy('nom')
            ->paginate(10)
            ->withQueryString();

        return view('communes.index', [
            'communes' => $communes,
            'q' => $q,
        ]);
    }

    public function create(): View
    {
        return view('communes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:communes,nom'],
            'cout_livraison' => ['required', 'integer', 'min:0'],
        ]);

        Commune::create($validated);

        return redirect()
            ->route('admin.communes.index')
            ->with('status', 'Commune créée avec succès.');
    }

    public function edit(Commune $commune): View
    {
        return view('communes.edit', [
            'commune' => $commune,
        ]);
    }

    public function update(Request $request, Commune $commune): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:communes,nom,' . $commune->id],
            'cout_livraison' => ['required', 'integer', 'min:0'],
        ]);

        $commune->update($validated);

        return redirect()
            ->route('admin.communes.index')
            ->with('status', 'Commune modifiée avec succès.');
    }

    public function destroy(Commune $commune): RedirectResponse
    {
        $commune->delete();

        return redirect()
            ->route('admin.communes.index')
            ->with('status', 'Commune supprimée avec succès.');
    }
}
