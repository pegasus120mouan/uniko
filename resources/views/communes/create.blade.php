@extends('layout.main')

@section('title', 'Créer une commune')

@section('content')
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Créer une commune</h4>
      <a href="{{ route('admin.communes.index') }}" class="btn btn-outline-secondary">Retour</a>
    </div>

    <div class="card">
      <div class="card-body">
        <form method="POST" action="{{ route('admin.communes.store') }}">
          @csrf

          <div class="mb-3">
            <label class="form-label" for="nom">Nom</label>
            <input type="text" id="nom" name="nom" value="{{ old('nom') }}" class="form-control @error('nom') is-invalid @enderror" required>
            @error('nom')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="cout_livraison">Coût livraison</label>
            <input type="number" id="cout_livraison" name="cout_livraison" value="{{ old('cout_livraison', 0) }}" class="form-control @error('cout_livraison') is-invalid @enderror" min="0" required>
            @error('cout_livraison')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <button class="btn btn-primary" type="submit">Enregistrer</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
