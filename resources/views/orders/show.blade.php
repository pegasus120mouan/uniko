@extends('layout.main')

@section('title', 'Détail commande')

@section('content')
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="mb-0">Commande {{ $order->order_number }}</h4>
        <div class="text-body-secondary">{{ $order->created_at?->format('Y-m-d H:i') }}</div>
      </div>
      <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Retour</a>
    </div>

    @if (session('status'))
      <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="row g-4">
      <div class="col-lg-4">
        <div class="card">
          <div class="card-body">
            <div class="fw-semibold">Client</div>
            <div class="mt-2">{{ $order->full_name }}</div>
            <div class="text-body-secondary">{{ $order->phone }}</div>

            <div class="mt-3">
              <div class="fw-semibold">Mode</div>
              <div class="mt-1">{{ $order->delivery_mode === 'delivery' ? 'Livraison' : 'Retrait boutique' }}</div>
              @if ($order->delivery_mode === 'delivery')
                @if ($order->commune_nom)
                  <div class="text-body-secondary">Commune: {{ $order->commune_nom }}</div>
                @endif
                @if ($order->address)
                  <div class="text-body-secondary">Adresse: {{ $order->address }}</div>
                @endif
              @endif
            </div>

            @if ($order->note)
              <div class="mt-3">
                <div class="fw-semibold">Note</div>
                <div class="text-body-secondary mt-1">{{ $order->note }}</div>
              </div>
            @endif

            <div class="mt-4">
              <div class="fw-semibold">Montants</div>
              <div class="mt-2 d-flex justify-content-between">
                <span class="text-body-secondary">Total produits</span>
                <span>{{ number_format((int) $order->subtotal, 0, ',', ' ') }} FCFA</span>
              </div>
              <div class="mt-1 d-flex justify-content-between">
                <span class="text-body-secondary">Coût livraison</span>
                <span>{{ number_format((int) $order->cout_livraison, 0, ',', ' ') }} FCFA</span>
              </div>
              <div class="mt-1 d-flex justify-content-between">
                <span class="text-body-secondary">Montant à payer</span>
                <span class="fw-semibold">{{ number_format((int) $order->montant_a_payer, 0, ',', ' ') }} FCFA</span>
              </div>
            </div>

            <div class="mt-4">
              <div class="fw-semibold">Statut</div>
              <form class="mt-2" method="POST" action="{{ route('admin.orders.update', $order) }}">
                @csrf
                @method('PUT')

                <select name="status" class="form-select @error('status') is-invalid @enderror">
                  <option value="pending_confirmation" {{ $order->status === 'pending_confirmation' ? 'selected' : '' }}>En attente de confirmation</option>
                  <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                  <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Livrée</option>
                  <option value="canceled" {{ $order->status === 'canceled' ? 'selected' : '' }}>Annulée</option>
                </select>
                @error('status')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <button type="submit" class="btn btn-primary mt-3">Mettre à jour</button>
              </form>

              @if ($order->confirmed_at)
                <div class="text-body-secondary mt-2">Confirmée le {{ $order->confirmed_at->format('Y-m-d H:i') }}</div>
              @endif
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <div class="fw-semibold">Articles</div>
            <div class="table-responsive text-nowrap mt-3">
              <table class="table">
                <thead>
                  <tr>
                    <th>Produit</th>
                    <th class="text-end">Prix</th>
                    <th class="text-end">Qté</th>
                    <th class="text-end">Total</th>
                  </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                  @forelse ($order->items as $item)
                    <tr>
                      <td>
                        <div class="fw-medium">{{ $item->product_name }}</div>
                        @if (!empty($item->product_brand))
                          <div class="text-body-secondary">{{ $item->product_brand }}</div>
                        @endif
                      </td>
                      <td class="text-end">{{ number_format((int) $item->unit_price, 0, ',', ' ') }} FCFA</td>
                      <td class="text-end">{{ (int) $item->quantity }}</td>
                      <td class="text-end">{{ number_format((int) $item->line_total, 0, ',', ' ') }} FCFA</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="4" class="text-center text-body-secondary">Aucun article.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
