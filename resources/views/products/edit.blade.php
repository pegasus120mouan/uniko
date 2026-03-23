@extends('layout.main')

@section('title', 'Modifier un produit')

@section('content')
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Modifier un produit</h4>
      <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Retour</a>
    </div>

    <div class="card">
      <div class="card-body">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label" for="name">Nom</label>
              <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" class="form-control @error('name') is-invalid @enderror" required>
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="brand">Marque</label>
              <input type="text" id="brand" name="brand" value="{{ old('brand', $product->brand) }}" class="form-control @error('brand') is-invalid @enderror" required>
              @error('brand')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="category_id">Catégorie</label>
              <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                <option value="">—</option>
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}" {{ (string) old('category_id', $product->category_id) === (string) $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                  </option>
                @endforeach
              </select>
              @error('category_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="contenant_id">Contenant (ml)</label>
              <select id="contenant_id" name="contenant_id" class="form-select @error('contenant_id') is-invalid @enderror">
                <option value="">—</option>
                @foreach ($contenants as $c)
                  <option value="{{ $c->id }}" data-prix="{{ $c->prix }}" {{ (string) old('contenant_id', $product->contenant_id) === (string) $c->id ? 'selected' : '' }}>
                    {{ $c->type_contenant }} · {{ $c->ml }}ml
                  </option>
                @endforeach
              </select>
              @error('contenant_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="price">Prix</label>
              <input type="number" step="0.01" min="0" id="price" name="price" value="{{ old('price', $product->price) }}" class="form-control @error('price') is-invalid @enderror" required>
              @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="quantity">Quantité</label>
              <input type="number" min="0" id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}" class="form-control @error('quantity') is-invalid @enderror" required>
              @error('quantity')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="low_stock_threshold">Seuil stock min</label>
              <input type="number" min="0" id="low_stock_threshold" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" class="form-control @error('low_stock_threshold') is-invalid @enderror" required>
              @error('low_stock_threshold')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="image">Image</label>

              @if ($product->image_path)
                <div class="mb-2">
                  <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" style="max-width: 160px; height: auto;" class="rounded border">
                </div>
                <div class="form-check mb-2">
                  <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1" {{ old('remove_image') ? 'checked' : '' }}>
                  <label class="form-check-label" for="remove_image">Supprimer l'image</label>
                </div>
              @endif

              <input type="file" id="image" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
              @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12 mb-3">
              <label class="form-label" for="description">Description</label>
              <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $product->description) }}</textarea>
              @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12 mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Actif</label>
              </div>
              @error('is_active')
                <div class="text-danger small">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <button class="btn btn-primary" type="submit">Mettre à jour</button>
        </form>

        <script>
          (function () {
            var contenant = document.getElementById('contenant_id');
            var price = document.getElementById('price');
            if (!contenant || !price) return;

            function applyPrice() {
              var opt = contenant.options[contenant.selectedIndex];
              if (!opt) return;
              var p = opt.getAttribute('data-prix');
              if (p === null || p === '') return;
              price.value = p;
            }

            contenant.addEventListener('change', applyPrice);
          })();
        </script>
      </div>
    </div>
  </div>
</div>
@endsection
