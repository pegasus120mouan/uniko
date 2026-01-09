@extends('layout.main')

@section('title', 'Rapport inventaire')

@section('content')

<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Rapport inventaire</h4>
      <a class="btn btn-outline-primary" href="{{ route('admin.reports.inventory.csv', request()->query()) }}">Exporter CSV</a>
    </div>

    <div class="card mb-4">
      <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.inventory') }}" class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Recherche</label>
            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Nom ou marque" />
          </div>
          <div class="col-md-3">
            <label class="form-label">Catégorie</label>
            <select name="category_id" class="form-select">
              <option value="">Toutes</option>
              @foreach ($categories as $c)
                <option value="{{ $c->id }}" @selected((string) $categoryId === (string) $c->id)>{{ $c->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2 d-flex align-items-end">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="active" value="1" id="activeOnly" @checked($onlyActive)>
              <label class="form-check-label" for="activeOnly">Actifs</label>
            </div>
          </div>
          <div class="col-md-2 d-flex align-items-end">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="low" value="1" id="lowOnly" @checked($onlyLow)>
              <label class="form-check-label" for="lowOnly">Stock faible</label>
            </div>
          </div>
          <div class="col-md-1 d-flex align-items-end">
            <button class="btn btn-primary w-100" type="submit">OK</button>
          </div>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="table-responsive text-nowrap">
          <table class="table">
            <thead>
              <tr>
                <th>Produit</th>
                <th>Marque</th>
                <th>Catégorie</th>
                <th class="text-end">Prix</th>
                <th class="text-end">Stock</th>
                <th class="text-end">Seuil</th>
                <th class="text-center">Actif</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @forelse ($products as $p)
                <tr>
                  <td>{{ $p->name }}</td>
                  <td>{{ $p->brand }}</td>
                  <td>{{ $p->category?->name ?? '—' }}</td>
                  <td class="text-end">{{ number_format((float) $p->price, 2, ',', ' ') }}</td>
                  <td class="text-end @if($p->quantity <= $p->low_stock_threshold) text-danger fw-bold @endif">{{ $p->quantity }}</td>
                  <td class="text-end">{{ $p->low_stock_threshold }}</td>
                  <td class="text-center">{{ $p->is_active ? 'Oui' : 'Non' }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center text-body-secondary">Aucun produit.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="mt-3">
          {{ $products->links() }}
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
