@extends('layout.main')

@section('title', 'Parfum - ' . $parfum->nom)

@section('content')
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="mb-1">{{ $parfum->nom }}</h4>
        <div class="d-flex align-items-center gap-2">
          <span class="badge bg-label-primary">{{ $parfum->code }}</span>
          @if ($parfum->type === 'luxe')
            <span class="badge bg-label-warning">Luxe</span>
          @else
            <span class="badge bg-label-info">Classics</span>
          @endif
        </div>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('admin.parfums.edit', $parfum) }}" class="btn btn-outline-primary">Modifier</a>
        <a href="{{ route('admin.parfums.index') }}" class="btn btn-outline-secondary">Retour</a>
      </div>
    </div>

    @if (session('status'))
      <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="row">
      <div class="col-lg-8">
        <div class="card mb-4">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Prix par contenant</h5>
          </div>
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Contenant</th>
                  <th>ML</th>
                  <th class="text-end">Prix</th>
                  <th class="text-end">Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($parfum->prices->sortBy('contenant.ml') as $price)
                  <tr>
                    <td class="fw-medium">{{ $price->contenant->type_contenant }}</td>
                    <td><span class="badge bg-label-primary">{{ $price->contenant->ml }} ml</span></td>
                    <td class="text-end fw-bold">{{ number_format($price->prix, 0, ',', ' ') }} FCFA</td>
                    <td class="text-end">
                      <form method="POST" action="{{ route('admin.parfums.prices.destroy', [$parfum, $price]) }}" class="d-inline">
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
                    <td colspan="4" class="text-center text-body-secondary py-4">
                      Aucun prix défini pour ce parfum.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card">
          <div class="card-header">
            <h5 class="mb-0">Ajouter un prix</h5>
          </div>
          <div class="card-body">
            @if ($availableContenants->isEmpty())
              <div class="alert alert-info mb-0">
                <i class="bx bx-info-circle me-1"></i>
                Tous les contenants {{ $parfum->type === 'luxe' ? 'Luxe' : 'Classics' }} ont déjà un prix défini.
              </div>
            @else
              <form method="POST" action="{{ route('admin.parfums.prices.store', $parfum) }}">
                @csrf

                <div class="mb-3">
                  <label class="form-label" for="contenant_id">Contenant</label>
                  <select id="contenant_id" name="contenant_id" class="form-select @error('contenant_id') is-invalid @enderror" required>
                    <option value="">Sélectionner...</option>
                    @foreach ($availableContenants as $contenant)
                      <option value="{{ $contenant->id }}" data-prix="{{ $contenant->prix }}" {{ (string) old('contenant_id') === (string) $contenant->id ? 'selected' : '' }}>
                        {{ $contenant->type_contenant }} · {{ $contenant->ml }}ml ({{ number_format($contenant->prix, 0, ',', ' ') }} FCFA)
                      </option>
                    @endforeach
                  </select>
                  @error('contenant_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label class="form-label" for="prix">Prix (FCFA)</label>
                  <input type="number" id="prix" name="prix" value="{{ old('prix') }}" class="form-control @error('prix') is-invalid @enderror" min="0" required>
                  @error('prix')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                  <small class="text-muted">Le prix standard sera pré-rempli automatiquement.</small>
                </div>

                <button class="btn btn-primary w-100" type="submit">
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
