@extends('layout.main')

@section('title', 'Grossistes')

@section('content')
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Grossistes</h4>
      <a href="{{ route('admin.grossistes.create') }}" class="btn btn-primary">Nouveau grossiste</a>
    </div>

    @if (session('status'))
      <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card">
      <div class="card-body pb-0">
        <form method="GET" action="{{ route('admin.grossistes.index') }}" class="row g-2 align-items-end">
          <div class="col-12 col-md-6 col-lg-4">
            <label class="form-label">Recherche</label>
            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Nom, entreprise, téléphone...">
          </div>
          <div class="col-12 col-md-6 col-lg-3 d-flex gap-2">
            <button class="btn btn-outline-primary" type="submit">Rechercher</button>
            @if ($q)
              <a class="btn btn-outline-secondary" href="{{ route('admin.grossistes.index') }}">Réinitialiser</a>
            @endif
          </div>
        </form>
      </div>

      <div class="table-responsive text-nowrap">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Nom</th>
              <th>Entreprise</th>
              <th>Téléphone</th>
              <th>Statut</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @forelse ($grossistes as $grossiste)
              <tr>
                <td class="fw-medium">
                  <a href="{{ route('admin.grossistes.show', $grossiste) }}" class="text-body">{{ $grossiste->nom }}</a>
                </td>
                <td>{{ $grossiste->entreprise ?? '-' }}</td>
                <td>{{ $grossiste->telephone }}</td>
                <td>
                  @if ($grossiste->is_active)
                    <span class="badge bg-label-success">Actif</span>
                  @else
                    <span class="badge bg-label-secondary">Inactif</span>
                  @endif
                </td>
                <td class="text-end">
                  <div class="btn-group" role="group" aria-label="Actions">
                    <a href="{{ route('admin.grossistes.show', $grossiste) }}" class="btn btn-sm btn-icon btn-outline-info" title="Voir les prix">
                      <i class="bx bx-show"></i>
                    </a>
                    <a href="{{ route('admin.grossistes.edit', $grossiste) }}" class="btn btn-sm btn-icon btn-outline-primary" title="Modifier">
                      <i class="bx bx-edit"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger" title="Supprimer" 
                      data-bs-toggle="modal" 
                      data-bs-target="#deleteModal" 
                      data-grossiste-id="{{ $grossiste->id }}"
                      data-grossiste-nom="{{ $grossiste->nom }}">
                      <i class="bx bx-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-body-secondary">Aucun grossiste.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="mt-4">
      {{ $grossistes->links() }}
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body text-center px-4 pb-4">
        <div class="mb-3">
          <span class="avatar avatar-lg bg-label-danger rounded-circle p-3">
            <i class="bx bx-trash fs-2"></i>
          </span>
        </div>
        <h4 class="mb-2">Confirmer la suppression</h4>
        <p class="text-muted mb-0">Êtes-vous sûr de vouloir supprimer le grossiste</p>
        <p class="fw-semibold text-dark" id="deleteGrossisteName"></p>
        <p class="text-muted small">Cette action supprimera également tous ses prix négociés.</p>
      </div>
      <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Annuler</button>
        <form id="deleteForm" method="POST" class="d-inline">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger px-4">
            <i class="bx bx-trash me-1"></i> Supprimer
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
      deleteModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var grossisteId = button.getAttribute('data-grossiste-id');
        var grossisteNom = button.getAttribute('data-grossiste-nom');
        
        document.getElementById('deleteGrossisteName').textContent = '"' + grossisteNom + '" ?';
        document.getElementById('deleteForm').action = '{{ url("admin/grossistes") }}/' + grossisteId;
      });
    }
  });
</script>
@endsection
