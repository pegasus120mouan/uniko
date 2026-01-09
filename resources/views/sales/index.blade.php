@extends('layout.main')

@section('title', 'Ventes')

@section('content')
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Ventes</h4>
      <a href="{{ route('admin.sales.create') }}" class="btn btn-primary">Nouvelle vente</a>
    </div>

    @if (session('status'))
      <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.sales.index') }}" class="mb-4">
      <div class="row g-2">
        <div class="col-md-5">
          <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Rechercher facture / produit...">
        </div>
        <div class="col-md-3">
          <input type="date" name="from" value="{{ $from }}" class="form-control" placeholder="Du">
        </div>
        <div class="col-md-3">
          <input type="date" name="to" value="{{ $to }}" class="form-control" placeholder="Au">
        </div>
        <div class="col-md-1 d-grid">
          <button class="btn btn-outline-primary" type="submit">OK</button>
        </div>
        @if ($q || $from || $to)
          <div class="col-12">
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.sales.index') }}">Réinitialiser</a>
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
              <th>Facture</th>
              <th>Caissier</th>
              <th>Total</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @forelse ($sales as $sale)
              <tr>
                <td>{{ $sale->sold_at?->format('Y-m-d H:i') }}</td>
                <td>{{ $sale->invoice_number }}</td>
                <td>{{ $sale->user?->name ?? '—' }}</td>
                <td>{{ number_format((float) $sale->total, 2, ',', ' ') }}</td>
                <td class="text-end">
                  <a href="{{ route('admin.sales.show', $sale) }}" class="btn btn-sm btn-outline-primary">Détail</a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-body-secondary">Aucune vente.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="mt-4">
      {{ $sales->links() }}
    </div>
  </div>
</div>
@endsection
