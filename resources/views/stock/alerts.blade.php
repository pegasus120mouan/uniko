@extends('layout.main')

@section('title', 'Alertes stock faible')

@section('content')
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Alertes stock faible</h4>
      <a href="{{ route('admin.stock.adjust.form') }}" class="btn btn-outline-primary">Ajuster le stock</a>
    </div>

    <form method="GET" action="{{ route('admin.stock.alerts') }}" class="mb-4">
      <div class="input-group">
        <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Rechercher par nom ou marque...">
        <button class="btn btn-outline-primary" type="submit">Rechercher</button>
        @if ($q)
          <a class="btn btn-outline-secondary" href="{{ route('admin.stock.alerts') }}">Réinitialiser</a>
        @endif
      </div>
    </form>

    <div class="card">
      <div class="table-responsive text-nowrap">
        <table class="table">
          <thead>
            <tr>
              <th>Produit</th>
              <th>Marque</th>
              <th>Catégorie</th>
              <th>Stock</th>
              <th>Seuil</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @forelse ($products as $product)
              <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->brand }}</td>
                <td>{{ $product->category?->name ?? '—' }}</td>
                <td><span class="text-danger fw-bold">{{ $product->quantity }}</span></td>
                <td>{{ $product->low_stock_threshold }}</td>
                <td class="text-end">
                  <a href="{{ route('admin.stock.adjust.form', ['product_id' => $product->id]) }}" class="btn btn-sm btn-outline-primary">Ajuster</a>
                  <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-secondary">Modifier</a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-body-secondary">Aucune alerte.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="mt-4">
      {{ $products->links() }}
    </div>
  </div>
</div>
@endsection
