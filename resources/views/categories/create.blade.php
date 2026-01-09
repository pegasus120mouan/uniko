@extends('layout.main')

@section('title', 'Créer une catégorie')

@section('content')
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Créer une catégorie</h4>
      <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Retour</a>
    </div>

    <div class="card">
      <div class="card-body">
        <form method="POST" action="{{ route('admin.categories.store') }}">
          @csrf

          <div class="mb-3">
            <label class="form-label" for="name">Nom</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="description">Description</label>
            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
            @error('description')
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
