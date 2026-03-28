@extends('layout.main')

@section('title', 'Grossiste - ' . $grossiste->nom)

@section('content')
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="mb-1">{{ $grossiste->nom }}</h4>
        <div class="d-flex align-items-center gap-2">
          @if ($grossiste->entreprise)
            <span class="text-muted">{{ $grossiste->entreprise }}</span>
          @endif
          @if ($grossiste->is_active)
            <span class="badge bg-label-success">Actif</span>
          @else
            <span class="badge bg-label-secondary">Inactif</span>
          @endif
        </div>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('admin.grossistes.edit', $grossiste) }}" class="btn btn-outline-primary">Modifier</a>
        <a href="{{ route('admin.grossistes.index') }}" class="btn btn-outline-secondary">Retour</a>
      </div>
    </div>

    @if (session('status'))
      <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="row">
      <div class="col-lg-4 mb-4">
        <div class="card h-100">
          <div class="card-header">
            <h5 class="mb-0">Informations</h5>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <div class="text-muted small">Téléphone</div>
              <div class="fw-medium">{{ $grossiste->telephone }}</div>
            </div>
            @if ($grossiste->email)
              <div class="mb-3">
                <div class="text-muted small">Email</div>
                <div class="fw-medium">{{ $grossiste->email }}</div>
              </div>
            @endif
            @if ($grossiste->adresse)
              <div class="mb-0">
                <div class="text-muted small">Adresse</div>
                <div class="fw-medium">{{ $grossiste->adresse }}</div>
              </div>
            @endif
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="card mb-4">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Prix négociés par contenant</h5>
          </div>
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Type</th>
                  <th>Contenant</th>
                  <th>ML</th>
                  <th class="text-end">Prix grossiste</th>
                  <th class="text-end">Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($grossiste->prices->sortBy(['contenant.type', 'contenant.ml']) as $price)
                  <tr>
                    <td>
                      @if ($price->contenant->type === 'luxe')
                        <span class="badge bg-label-warning">Luxe</span>
                      @else
                        <span class="badge bg-label-info">Classics</span>
                      @endif
                    </td>
                    <td class="fw-medium">{{ $price->contenant->type_contenant }}</td>
                    <td><span class="badge bg-label-primary">{{ $price->contenant->ml }} ml</span></td>
                    <td class="text-end fw-bold">{{ number_format($price->prix, 0, ',', ' ') }} FCFA</td>
                    <td class="text-end">
                      <form method="POST" action="{{ route('admin.grossistes.prices.destroy', [$grossiste, $price]) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-icon btn-outline-danger" title="Supprimer" onclick="return confirm('Supprimer ce prix ?')">
                          <i class="bx bx-trash"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center text-body-secondary py-4">
                      Aucun prix négocié pour ce grossiste.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <h5 class="mb-0">Ajouter un prix</h5>
          </div>
          <div class="card-body">
            @if ($availableContenants->isEmpty())
              <div class="alert alert-info mb-0">
                <i class="bx bx-info-circle me-1"></i>
                Tous les contenants ont déjà un prix défini pour ce grossiste.
              </div>
            @else
              <form method="POST" action="{{ route('admin.grossistes.prices.store', $grossiste) }}">
                @csrf

                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label" for="contenant_id">Contenant</label>
                    <select id="contenant_id" name="contenant_id" class="form-select @error('contenant_id') is-invalid @enderror" required>
                      <option value="">Sélectionner...</option>
                      @foreach ($availableContenants as $contenant)
                        <option value="{{ $contenant->id }}" data-prix="{{ $contenant->prix }}" {{ (string) old('contenant_id') === (string) $contenant->id ? 'selected' : '' }}>
                          [{{ $contenant->type === 'luxe' ? 'Luxe' : 'Classics' }}] {{ $contenant->type_contenant }} · {{ $contenant->ml }}ml (Prix standard: {{ number_format($contenant->prix, 0, ',', ' ') }} FCFA)
                        </option>
                      @endforeach
                    </select>
                    @error('contenant_id')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="col-md-6 mb-3">
                    <label class="form-label" for="prix">Prix grossiste (FCFA)</label>
                    <input type="number" id="prix" name="prix" value="{{ old('prix') }}" class="form-control @error('prix') is-invalid @enderror" min="0" required>
                    @error('prix')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <button class="btn btn-primary" type="submit">
                  <i class="bx bx-plus me-1"></i> Ajouter le prix
                </button>
              </form>

              <script>
                (function () {
                  var contenant = document.getElementById('contenant_id');
                  var prix = document.getElementById('prix');
                  if (!contenant || !prix) return;

                  contenant.addEventListener('change', function () {
                    var opt = contenant.options[contenant.selectedIndex];
                    if (!opt) return;
                    var p = opt.getAttribute('data-prix');
                    if (p !== null && p !== '') {
                      prix.value = p;
                    }
                  });
                })();
              </script>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
