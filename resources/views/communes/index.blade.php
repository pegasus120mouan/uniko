@extends('layout.main')

@section('title', 'Communes')

@section('content')
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Communes</h4>
      <a href="{{ route('admin.communes.create') }}" class="btn btn-primary">Nouvelle commune</a>
    </div>

    @if (session('status'))
      <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card">
      <div class="card-body pb-0">
        <form method="GET" action="{{ route('admin.communes.index') }}" class="row g-2 align-items-end">
          <div class="col-12 col-md-6 col-lg-4">
            <label class="form-label">Recherche</label>
            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Nom ou coût...">
          </div>
          <div class="col-12 col-md-6 col-lg-3 d-flex gap-2">
            <button class="btn btn-outline-primary" type="submit">Rechercher</button>
            @if ($q)
              <a class="btn btn-outline-secondary" href="{{ route('admin.communes.index') }}">Réinitialiser</a>
            @endif
          </div>
        </form>
      </div>

      <div class="table-responsive text-nowrap">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Nom</th>
              <th class="text-end">Coût livraison</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @forelse ($communes as $commune)
              <tr>
                <td class="fw-medium">{{ $commune->nom }}</td>
                <td class="text-end">{{ number_format((int) $commune->cout_livraison, 0, ',', ' ') }}</td>
                <td class="text-end">
                  <div class="btn-group" role="group" aria-label="Actions">
                    <a href="{{ route('admin.communes.edit', $commune) }}" class="btn btn-sm btn-icon btn-outline-primary" title="Modifier">
                      <i class="bx bx-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.communes.destroy', $commune) }}" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-icon btn-outline-danger" title="Supprimer" onclick="return confirm('Supprimer cette commune ?')">
                        <i class="bx bx-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="text-center text-body-secondary">Aucune commune.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="mt-4">
      {{ $communes->links() }}
    </div>
  </div>
</div>
@endsection
