@extends('layout.main')

@section('title', 'Produits')

@section('content')
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Produits</h4>
      <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Nouveau produit</a>
    </div>

    @if (session('status'))
      <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.products.index') }}" class="mb-4">
      <div class="input-group">
        <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Rechercher par nom ou marque...">
        <button class="btn btn-outline-primary" type="submit">Rechercher</button>
        @if ($q)
          <a class="btn btn-outline-secondary" href="{{ route('admin.products.index') }}">Réinitialiser</a>
        @endif
      </div>
    </form>

    <div class="card">
      <div class="table-responsive text-nowrap">
        <table class="table">
          <thead>
            <tr>
              <th>Nom</th>
              <th>Marque</th>
              <th>Catégorie</th>
              <th>Prix</th>
              <th>Quantité</th>
              <th>Statut</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @forelse ($products as $product)
              <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->brand }}</td>
                <td>{{ $product->category?->name ?? '—' }}</td>
                <td>{{ number_format((float) $product->price, 2, ',', ' ') }}</td>
                <td>
                  <span class="{{ $product->isLowStock() ? 'text-danger fw-bold' : '' }}">{{ $product->quantity }}</span>
                </td>
                <td>
                  @if ($product->is_active)
                    <span class="badge bg-label-success">Actif</span>
                  @else
                    <span class="badge bg-label-secondary">Inactif</span>
                  @endif
                </td>
                <td class="text-end">
                  <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                  <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ce produit ?')">Supprimer</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-body-secondary">Aucun produit.</td>
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
