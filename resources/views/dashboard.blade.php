@extends('layout.main')

@section('title', 'Tableau de bord')

@section('content')

<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Tableau de bord</h4>
      @if (auth()->check() && auth()->user()->role === 'admin')
        <a href="{{ route('admin.sales.create') }}" class="btn btn-primary">Nouvelle vente</a>
      @endif
    </div>

    <div class="row">
      <div class="col-12 col-lg-8 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title mb-3">Vue d'ensemble des ventes</h5>
            <div class="row">
              <div class="col-md-4 mb-3">
                <div class="border rounded p-3 h-100">
                  <div class="text-body-secondary">Aujourd'hui</div>
                  <div class="h4 mb-1">{{ $dailySalesCount }}</div>
                  <div class="text-body-secondary">CA: {{ number_format($dailyRevenue, 2, ',', ' ') }}</div>
                </div>
              </div>
              <div class="col-md-4 mb-3">
                <div class="border rounded p-3 h-100">
                  <div class="text-body-secondary">Cette semaine</div>
                  <div class="h4 mb-1">{{ $weeklySalesCount }}</div>
                  <div class="text-body-secondary">CA: {{ number_format($weeklyRevenue, 2, ',', ' ') }}</div>
                </div>
              </div>
              <div class="col-md-4 mb-3">
                <div class="border rounded p-3 h-100">
                  <div class="text-body-secondary">Ce mois</div>
                  <div class="h4 mb-1">{{ $monthlySalesCount }}</div>
                  <div class="text-body-secondary">CA: {{ number_format($monthlyRevenue, 2, ',', ' ') }}</div>
                </div>
              </div>
            </div>
            <div class="text-body-secondary">Les montants sont calculés à partir des ventes enregistrées.</div>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-4 mb-4">
        <div class="row">
          <div class="col-6 col-lg-12 mb-4">
            <div class="card h-100">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <div class="text-body-secondary">Chiffre d'affaires total</div>
                    <div class="h4 mb-0">{{ number_format($totalRevenue, 2, ',', ' ') }}</div>
                  </div>
                  <div class="avatar">
                    <span class="avatar-initial rounded bg-label-primary"><i class="icon-base bx bx-dollar"></i></span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-6 col-lg-12 mb-4">
            <div class="card h-100">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <div class="text-body-secondary">Alertes stock faible</div>
                    <div class="h4 mb-0">{{ $lowStockCount }}</div>
                  </div>
                  <div class="avatar">
                    <span class="avatar-initial rounded bg-label-danger"><i class="icon-base bx bx-error"></i></span>
                  </div>
                </div>
                @if (auth()->check() && auth()->user()->role === 'admin')
                  <a class="btn btn-sm btn-outline-danger mt-3" href="{{ route('admin.stock.alerts') }}">Voir alertes</a>
                @endif
              </div>
            </div>
          </div>

          <div class="col-12">
            <div class="card h-100">
              <div class="card-body">
                <div class="row">
                  <div class="col-6">
                    <div class="text-body-secondary">Produits</div>
                    <div class="h4 mb-0">{{ $totalProductsCount }}</div>
                  </div>
                  <div class="col-6">
                    <div class="text-body-secondary">Ventes</div>
                    <div class="h4 mb-0">{{ $totalSalesCount }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12 col-lg-7 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="card-title mb-0">Top parfums vendus</h5>
              @if (auth()->check() && auth()->user()->role === 'admin')
                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.sales.index') }}">Historique ventes</a>
              @endif
            </div>
            <div class="table-responsive text-nowrap">
              <table class="table">
                <thead>
                  <tr>
                    <th>Produit</th>
                    <th class="text-end">Qté vendue</th>
                    <th class="text-end">Montant</th>
                  </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                  @forelse ($topProducts as $row)
                    <tr>
                      <td>{{ $row->product?->name ?? '—' }}</td>
                      <td class="text-end">{{ (int) $row->qty_sold }}</td>
                      <td class="text-end">{{ number_format((float) $row->amount, 2, ',', ' ') }}</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="3" class="text-center text-body-secondary">Aucune vente.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-5 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="card-title mb-0">Produits en stock faible</h5>
              @if (auth()->check() && auth()->user()->role === 'admin')
                <a class="btn btn-sm btn-outline-danger" href="{{ route('admin.stock.alerts') }}">Voir tout</a>
              @endif
            </div>
            <div class="table-responsive text-nowrap">
              <table class="table">
                <thead>
                  <tr>
                    <th>Produit</th>
                    <th class="text-end">Stock</th>
                    <th class="text-end">Seuil</th>
                  </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                  @forelse ($lowStockProducts as $p)
                    <tr>
                      <td>{{ $p->name }}</td>
                      <td class="text-end text-danger fw-bold">{{ $p->quantity }}</td>
                      <td class="text-end">{{ $p->low_stock_threshold }}</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="3" class="text-center text-body-secondary">Aucune alerte.</td>
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