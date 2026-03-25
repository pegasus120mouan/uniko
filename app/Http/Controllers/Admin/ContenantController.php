<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContenantController extends Controller
{
    public function index(Request $request): View
    {
        $q = (string) $request->query('q', '');
        $type = $request->query('type');

        $contenants = Contenant::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('type_contenant', 'like', "%{$q}%")
                    ->orWhere('ml', 'like', "%{$q}%")
                    ->orWhere('prix', 'like', "%{$q}%");
            })
            ->when($type !== null && in_array($type, ['classics', 'luxe']), function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->orderBy('type_contenant')
            ->orderBy('ml')
            ->paginate(10)
            ->withQueryString();

        return view('contenants.index', [
            'contenants' => $contenants,
            'q' => $q,
            'type' => $type,
        ]);
    }

    public function create(): View
    {
        return view('contenants.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ml' => ['required', 'integer', 'min:1'],
            'type_contenant' => ['required', 'string', 'max:100'],
            'type' => ['required', 'string', 'in:classics,luxe'],
            'prix' => ['required', 'integer', 'min:0'],
        ]);

        Contenant::create($validated);

        return redirect()
            ->route('admin.contenants.index')
            ->with('status', 'Prix standard créé avec succès.');
    }

    public function edit(Contenant $contenant): View
    {
        return view('contenants.edit', [
            'contenant' => $contenant,
        ]);
    }

    public function update(Request $request, Contenant $contenant): RedirectResponse
    {
        $validated = $request->validate([
            'ml' => ['required', 'integer', 'min:1'],
            'type_contenant' => ['required', 'string', 'max:100'],
            'type' => ['required', 'string', 'in:classics,luxe'],
            'prix' => ['required', 'integer', 'min:0'],
        ]);

        $contenant->update($validated);

        return redirect()
            ->route('admin.contenants.index')
            ->with('status', 'Prix standard modifié avec succès.');
    }

    public function destroy(Contenant $contenant): RedirectResponse
    {
        $contenant->delete();

        return redirect()
            ->route('admin.contenants.index')
            ->with('status', 'Prix standard supprimé avec succès.');
    }
}
