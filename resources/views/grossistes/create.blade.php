@extends('layout.main')

@section('title', 'Créer un grossiste')

@section('content')
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Créer un grossiste</h4>
      <a href="{{ route('admin.grossistes.index') }}" class="btn btn-outline-secondary">Retour</a>
    </div>

    <div class="card">
      <div class="card-body">
        <form method="POST" action="{{ route('admin.grossistes.store') }}">
          @csrf

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label" for="nom">Nom <span class="text-danger">*</span></label>
              <input type="text" id="nom" name="nom" value="{{ old('nom') }}" class="form-control @error('nom') is-invalid @enderror" required>
              @error('nom')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="entreprise">Entreprise</label>
              <input type="text" id="entreprise" name="entreprise" value="{{ old('entreprise') }}" class="form-control @error('entreprise') is-invalid @enderror">
              @error('entreprise')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label" for="telephone">Téléphone <span class="text-danger">*</span></label>
              <input type="text" id="telephone" name="telephone" value="{{ old('telephone') }}" class="form-control @error('telephone') is-invalid @enderror" required>
              @error('telephone')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="email">Email</label>
              <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="adresse">Adresse</label>
            <textarea id="adresse" name="adresse" class="form-control @error('adresse') is-invalid @enderror" rows="2">{{ old('adresse') }}</textarea>
            @error('adresse')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
              <label class="form-check-label" for="is_active">Grossiste actif</label>
            </div>
          </div>

          <button class="btn btn-primary" type="submit">Enregistrer</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
