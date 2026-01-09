@extends('layout.main')

@section('title', 'Détail vente')

@section('content')
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="mb-1">Vente {{ $sale->invoice_number }}</h4>
        <div class="text-body-secondary">{{ $sale->sold_at?->format('Y-m-d H:i') }} — {{ $sale->user?->name ?? '—' }}</div>
      </div>
      <a href="{{ route('admin.sales.index') }}" class="btn btn-outline-secondary">Retour</a>
    </div>

    @if (session('status'))
      <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card mb-4">
      <div class="table-responsive text-nowrap">
        <table class="table">
          <thead>
            <tr>
              <th>Produit</th>
              <th>Qté</th>
              <th>Prix</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @foreach ($sale->items as $item)
              <tr>
                <td>{{ $item->product?->name ?? '—' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format((float) $item->unit_price, 2, ',', ' ') }}</td>
                <td>{{ number_format((float) $item->line_total, 2, ',', ' ') }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <span class="text-body-secondary">Sous-total</span>
              <strong>{{ number_format((float) $sale->subtotal, 2, ',', ' ') }}</strong>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-body-secondary">Remise</span>
              <strong>{{ number_format((float) $sale->discount, 2, ',', ' ') }}</strong>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-body-secondary">Taxe</span>
              <strong>{{ number_format((float) $sale->tax, 2, ',', ' ') }}</strong>
            </div>
            <hr>
            <div class="d-flex justify-content-between">
              <span>Total</span>
              <strong>{{ number_format((float) $sale->total, 2, ',', ' ') }}</strong>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
