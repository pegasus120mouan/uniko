@extends('layout.main')

@section('title', 'Mouvements de stock')

@section('content')
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Mouvements de stock</h4>
      <div class="d-flex gap-2">
        <a href="{{ route('admin.stock.alerts') }}" class="btn btn-outline-danger">Voir alertes</a>
        <a href="{{ route('admin.stock.adjust.form') }}" class="btn btn-primary">Ajuster le stock</a>
      </div>
    </div>

    @if (session('status'))
      <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.stock.movements') }}" class="mb-4">
      <div class="row g-2">
        <div class="col-md-6">
          <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Rechercher produit (nom/marque)...">
        </div>
        <div class="col-md-3">
          <select class="form-select" name="type">
            <option value="">Tous types</option>
            <option value="entree" {{ $type === 'entree' ? 'selected' : '' }}>Entrée</option>
            <option value="sortie" {{ $type === 'sortie' ? 'selected' : '' }}>Sortie</option>
            <option value="sale" {{ $type === 'sale' ? 'selected' : '' }}>Vente</option>
          </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
          <button class="btn btn-outline-primary flex-grow-1" type="submit">Filtrer</button>
          @if ($q || $type)
            <a class="btn btn-outline-secondary" href="{{ route('admin.stock.movements') }}">Reset</a>
          @endif
        </div>
      </div>
    </form>

    <div class="card">
      <div class="table-responsive text-nowrap">
        <table class="table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Produit</th>
              <th>Type</th>
              <th>Qté</th>
              <th>Utilisateur</th>
              <th>Commentaire</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @forelse ($movements as $m)
              <tr>
                <td>{{ $m->moved_at?->format('Y-m-d H:i') }}</td>
                <td>{{ $m->product?->name ?? '—' }}</td>
                <td>{{ $m->type_label }}</td>
                <td class="{{ $m->quantity_change < 0 ? 'text-danger fw-bold' : 'text-success fw-bold' }}">{{ $m->quantity_change }}</td>
                <td>{{ $m->user?->name ?? '—' }}</td>
                <td class="text-truncate" style="max-width: 380px;">{{ $m->comment }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-body-secondary">Aucun mouvement.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="mt-4">
      {{ $movements->links() }}
    </div>
  </div>
</div>
@endsection
