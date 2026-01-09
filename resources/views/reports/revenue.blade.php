@extends('layout.main')

@section('title', 'Statistiques CA')

@section('content')

<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Statistiques CA</h4>
      <div class="text-body-secondary">Jour: {{ $days }} derniers jours | Mois: {{ $months }} derniers mois</div>
    </div>

    <div class="row">
      <div class="col-12 col-lg-6 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title mb-3">CA ({{ $days }} derniers jours)</h5>
            <div id="revenueDailyChart"></div>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-6 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title mb-3">CA ({{ $months }} derniers mois)</h5>
            <div id="revenueMonthlyChart"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12 col-lg-6 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title mb-3">Nombre de ventes ({{ $days }} derniers jours)</h5>
            <div id="salesDailyChart"></div>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-6 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title mb-3">Nombre de ventes ({{ $months }} derniers mois)</h5>
            <div id="salesMonthlyChart"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  (function () {
    const dailyLabels = @json($dailyLabels);
    const dailyAmounts = @json($dailyAmounts);
    const dailyCounts = @json($dailyCounts);

    const monthlyLabels = @json($monthlyLabels);
    const monthlyAmounts = @json($monthlyAmounts);
    const monthlyCounts = @json($monthlyCounts);

    function moneyChart(el, labels, series) {
      const options = {
        chart: { type: 'area', height: 280, toolbar: { show: false } },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth' },
        series: [{ name: 'CA', data: series }],
        xaxis: { categories: labels },
        yaxis: { labels: { formatter: (v) => v.toFixed(0) } },
      };
      new ApexCharts(document.querySelector(el), options).render();
    }

    function countChart(el, labels, series) {
      const options = {
        chart: { type: 'bar', height: 280, toolbar: { show: false } },
        dataLabels: { enabled: false },
        series: [{ name: 'Ventes', data: series }],
        xaxis: { categories: labels },
      };
      new ApexCharts(document.querySelector(el), options).render();
    }

    moneyChart('#revenueDailyChart', dailyLabels, dailyAmounts);
    moneyChart('#revenueMonthlyChart', monthlyLabels, monthlyAmounts);

    countChart('#salesDailyChart', dailyLabels, dailyCounts);
    countChart('#salesMonthlyChart', monthlyLabels, monthlyCounts);
  })();
</script>

@endsection
