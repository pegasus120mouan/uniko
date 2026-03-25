@extends('layout.main')

@section('title', 'Modifier un parfum')

@section('content')
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Modifier un parfum</h4>
      <a href="{{ route('admin.parfums.index') }}" class="btn btn-outline-secondary">Retour</a>
    </div>

    <div class="card">
      <div class="card-body">
        <form method="POST" action="{{ route('admin.parfums.update', $parfum) }}">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label class="form-label" for="code">Code</label>
            <input type="text" id="code" name="code" value="{{ old('code', $parfum->code) }}" class="form-control @error('code') is-invalid @enderror" maxlength="10" required>
            @error('code')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="nom">Nom</label>
            <input type="text" id="nom" name="nom" value="{{ old('nom', $parfum->nom) }}" class="form-control @error('nom') is-invalid @enderror" required>
            @error('nom')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="type">Type</label>
            <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
              <option value="classics" {{ old('type', $parfum->type) === 'classics' ? 'selected' : '' }}>Classics</option>
              <option value="luxe" {{ old('type', $parfum->type) === 'luxe' ? 'selected' : '' }}>Luxe</option>
            </select>
            @error('type')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <button class="btn btn-primary" type="submit">Mettre à jour</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
