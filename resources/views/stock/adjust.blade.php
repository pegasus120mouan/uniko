@extends('layout.main')

@section('title', 'Ajustement de stock')

@section('content')
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Ajustement de stock</h4>
      <a href="{{ route('admin.stock.movements') }}" class="btn btn-outline-secondary">Historique</a>
    </div>

    <div class="card">
      <div class="card-body">
        @if ($products->isEmpty())
          <div class="alert alert-warning">
            Aucun parfum à afficher. Crée d'abord des produits (parfums) dans la section Produits.
          </div>
        @endif
        <form method="POST" action="{{ route('admin.stock.adjust') }}">
          @csrf

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label" for="product_id">Parfum</label>
              <select id="product_id" name="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                <option value="">—</option>
                @foreach ($products as $p)
                  <option value="{{ $p->id }}" {{ (string) old('product_id', $productId) === (string) $p->id ? 'selected' : '' }}>
                    {{ $p->name }} ({{ $p->brand }}) — Stock: {{ $p->quantity }}
                  </option>
                @endforeach
              </select>
              @error('product_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-3 mb-3">
              <label class="form-label" for="direction">Type</label>
              <select id="direction" name="direction" class="form-select @error('direction') is-invalid @enderror" required>
                <option value="in" {{ old('direction', $direction ?? 'in') === 'in' ? 'selected' : '' }}>Entrée</option>
                <option value="out" {{ old('direction', $direction ?? 'in') === 'out' ? 'selected' : '' }}>Sortie</option>
              </select>
              @error('direction')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-3 mb-3">
              <label class="form-label" for="quantity">Quantité</label>
              <input type="number" min="1" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" class="form-control @error('quantity') is-invalid @enderror" required>
              @error('quantity')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12 mb-3">
              <label class="form-label" for="comment">Commentaire</label>
              <textarea id="comment" name="comment" class="form-control @error('comment') is-invalid @enderror" rows="3">{{ old('comment') }}</textarea>
              @error('comment')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <button type="submit" class="btn btn-primary">Valider</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
