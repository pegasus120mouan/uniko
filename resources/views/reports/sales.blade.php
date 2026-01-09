@extends('layout.main')

@section('title', 'Rapport ventes')

@section('content')

<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Rapport ventes</h4>
      <div class="d-flex gap-2">
        <a class="btn btn-outline-primary" href="{{ route('admin.reports.sales.csv', request()->query()) }}">Exporter CSV</a>
        <a class="btn btn-outline-danger" href="{{ route('admin.reports.sales.pdf', request()->query()) }}">Exporter PDF</a>
      </div>
    </div>

    <div class="card mb-4">
      <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.sales') }}" class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Facture</label>
            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Ex: UNK-20260109-XXXXXX" />
          </div>
          <div class="col-md-3">
            <label class="form-label">Du</label>
            <input type="date" name="from" value="{{ $from }}" class="form-control" />
          </div>
          <div class="col-md-3">
            <label class="form-label">Au</label>
            <input type="date" name="to" value="{{ $to }}" class="form-control" />
          </div>
          <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-primary w-100" type="submit">OK</button>
          </div>
        </form>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <div class="text-body-secondary">Nombre de ventes</div>
            <div class="h4 mb-0">{{ $totalCount }}</div>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <div class="text-body-secondary">Chiffre d'affaires</div>
            <div class="h4 mb-0">{{ number_format($totalAmount, 2, ',', ' ') }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="table-responsive text-nowrap">
          <table class="table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Facture</th>
                <th>Utilisateur</th>
                <th class="text-end">Sous-total</th>
                <th class="text-end">Remise</th>
                <th class="text-end">Taxe</th>
                <th class="text-end">Total</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @forelse ($sales as $s)
                <tr>
                  <td>{{ optional($s->sold_at)->format('d/m/Y H:i') }}</td>
                  <td>{{ $s->invoice_number }}</td>
                  <td>{{ $s->user?->name ?? '—' }}</td>
                  <td class="text-end">{{ number_format((float) $s->subtotal, 2, ',', ' ') }}</td>
                  <td class="text-end">{{ number_format((float) $s->discount, 2, ',', ' ') }}</td>
                  <td class="text-end">{{ number_format((float) $s->tax, 2, ',', ' ') }}</td>
                  <td class="text-end fw-bold">{{ number_format((float) $s->total, 2, ',', ' ') }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center text-body-secondary">Aucune vente.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="mt-3">
          {{ $sales->links() }}
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
