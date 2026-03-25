@extends('layout.main')

@section('title', 'Parfums')

@section('content')
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Parfums</h4>
      <a href="{{ route('admin.parfums.create') }}" class="btn btn-primary">Nouveau parfum</a>
    </div>

    @if (session('status'))
      <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card">
      <div class="card-body pb-0">
        <form method="GET" action="{{ route('admin.parfums.index') }}" class="row g-2 align-items-end">
          <div class="col-12 col-md-6 col-lg-4">
            <label class="form-label">Recherche</label>
            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Code ou nom...">
          </div>
          <div class="col-12 col-md-6 col-lg-3 d-flex gap-2">
            <button class="btn btn-outline-primary" type="submit">Rechercher</button>
            @if ($q)
              <a class="btn btn-outline-secondary" href="{{ route('admin.parfums.index') }}">Réinitialiser</a>
            @endif
          </div>
        </form>
      </div>

      <div class="table-responsive text-nowrap">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Code</th>
              <th>Nom</th>
              <th>Type</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @forelse ($parfums as $parfum)
              <tr>
                <td><span class="badge bg-label-primary">{{ $parfum->code }}</span></td>
                <td class="fw-medium">
                  <a href="{{ route('admin.parfums.show', $parfum) }}" class="text-body">{{ $parfum->nom }}</a>
                </td>
                <td>
                  @if ($parfum->type === 'luxe')
                    <span class="badge bg-label-warning">Luxe</span>
                  @else
                    <span class="badge bg-label-info">Classics</span>
                  @endif
                </td>
                <td class="text-end">
                  <div class="btn-group" role="group" aria-label="Actions">
                    <a href="{{ route('admin.parfums.show', $parfum) }}" class="btn btn-sm btn-icon btn-outline-info" title="Voir les prix">
                      <i class="bx bx-show"></i>
                    </a>
                    <a href="{{ route('admin.parfums.edit', $parfum) }}" class="btn btn-sm btn-icon btn-outline-primary" title="Modifier">
                      <i class="bx bx-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.parfums.destroy', $parfum) }}" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-icon btn-outline-danger" title="Supprimer" onclick="return confirm('Supprimer ce parfum ?')">
                        <i class="bx bx-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center text-body-secondary">Aucun parfum.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="mt-4">
      {{ $parfums->links() }}
    </div>
  </div>
</div>
@endsection
