@extends('layout.main')

@section('title', 'Commandes')

@section('content')
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Commandes</h4>
    </div>

    @if (session('status'))
      <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4">
      <div class="row g-2">
        <div class="col-md-6">
          <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Recherche numéro / nom / téléphone...">
        </div>
        <div class="col-md-3">
          <select name="status" class="form-select">
            <option value="">Tous les statuts</option>
            <option value="pending_confirmation" {{ $status === 'pending_confirmation' ? 'selected' : '' }}>En attente</option>
            <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Confirmée</option>
            <option value="delivered" {{ $status === 'delivered' ? 'selected' : '' }}>Livrée</option>
            <option value="canceled" {{ $status === 'canceled' ? 'selected' : '' }}>Annulée</option>
          </select>
        </div>
        <div class="col-md-3 d-grid">
          <button class="btn btn-outline-primary" type="submit">Filtrer</button>
        </div>
        @if ($q || $status)
          <div class="col-12">
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.orders.index') }}">Réinitialiser</a>
          </div>
        @endif
      </div>
    </form>

    <div class="card">
      <div class="table-responsive text-nowrap">
        <table class="table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Numéro</th>
              <th>Client</th>
              <th>Téléphone</th>
              <th>Mode</th>
              <th>Statut</th>
              <th class="text-end">Montant</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @forelse ($orders as $order)
              <tr>
                <td>{{ $order->created_at?->format('Y-m-d H:i') }}</td>
                <td class="fw-medium">{{ $order->order_number }}</td>
                <td>{{ $order->full_name }}</td>
                <td>{{ $order->phone }}</td>
                <td>{{ $order->delivery_mode === 'delivery' ? 'Livraison' : 'Retrait' }}</td>
                <td>
                  @php($statusLabel = match ($order->status) {
                    'pending_confirmation' => 'En attente',
                    'confirmed' => 'Confirmée',
                    'delivered' => 'Livrée',
                    'canceled' => 'Annulée',
                    default => $order->status,
                  })
                  <span class="badge bg-label-primary">{{ $statusLabel }}</span>
                </td>
                <td class="text-end">{{ number_format((int) $order->montant_a_payer, 0, ',', ' ') }} FCFA</td>
                <td class="text-end">
                  <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">Détail</a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center text-body-secondary">Aucune commande.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="mt-4">
      {{ $orders->links() }}
    </div>
  </div>
</div>
@endsection
