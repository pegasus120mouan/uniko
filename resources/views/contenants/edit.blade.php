@extends('layout.main')

@section('title', 'Modifier un prix standard')

@section('content')
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Modifier un prix standard</h4>
      <a href="{{ route('admin.contenants.index') }}" class="btn btn-outline-secondary">Retour</a>
    </div>

    <div class="card">
      <div class="card-body">
        <form method="POST" action="{{ route('admin.contenants.update', $contenant) }}">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label class="form-label" for="ml">ML</label>
            <input type="number" id="ml" name="ml" value="{{ old('ml', $contenant->ml) }}" class="form-control @error('ml') is-invalid @enderror" min="1" required>
            @error('ml')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="type_contenant">Type de contenant</label>
            <input type="text" id="type_contenant" name="type_contenant" value="{{ old('type_contenant', $contenant->type_contenant) }}" class="form-control @error('type_contenant') is-invalid @enderror" maxlength="100" required>
            @error('type_contenant')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="prix">Prix</label>
            <input type="number" id="prix" name="prix" value="{{ old('prix', $contenant->prix) }}" class="form-control @error('prix') is-invalid @enderror" min="0" required>
            @error('prix')
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
