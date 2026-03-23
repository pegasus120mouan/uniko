@extends('layout.main')

@section('title', 'Prix Standard')

@section('content')
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Prix Standard</h4>
      <a href="{{ route('admin.contenants.create') }}" class="btn btn-primary">Nouveau</a>
    </div>

    @if (session('status'))
      <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card">
      <div class="card-body pb-0">
        <form method="GET" action="{{ route('admin.contenants.index') }}" class="row g-2 align-items-end">
          <div class="col-12 col-md-6 col-lg-4">
            <label class="form-label">Recherche</label>
            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Type, ml ou prix...">
          </div>
          <div class="col-12 col-md-6 col-lg-3 d-flex gap-2">
            <button class="btn btn-outline-primary" type="submit">Rechercher</button>
            @if ($q)
              <a class="btn btn-outline-secondary" href="{{ route('admin.contenants.index') }}">Réinitialiser</a>
            @endif
          </div>
        </form>
      </div>

      <div class="table-responsive text-nowrap">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>ML</th>
              <th>Type de contenant</th>
              <th class="text-end">Prix</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @forelse ($contenants as $c)
              <tr>
                <td><span class="badge bg-label-primary">{{ $c->ml }} ml</span></td>
                <td class="fw-medium">{{ $c->type_contenant }}</td>
                <td class="text-end">{{ number_format((int) $c->prix, 0, ',', ' ') }}</td>
                <td class="text-end">
                  <div class="btn-group" role="group" aria-label="Actions">
                    <a href="{{ route('admin.contenants.edit', $c) }}" class="btn btn-sm btn-icon btn-outline-primary" title="Modifier">
                      <i class="bx bx-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.contenants.destroy', $c) }}" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-icon btn-outline-danger" title="Supprimer" onclick="return confirm('Supprimer ce prix standard ?')">
                        <i class="bx bx-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center text-body-secondary">Aucun prix standard.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="mt-4">
      {{ $contenants->links() }}
    </div>
  </div>
</div>
@endsection
