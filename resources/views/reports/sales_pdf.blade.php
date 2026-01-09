<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Rapport ventes</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    h1 { font-size: 16px; margin: 0 0 8px; }
    .meta { margin-bottom: 12px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 6px; }
    th { background: #f5f5f5; text-align: left; }
    .right { text-align: right; }
  </style>
</head>
<body>
  <h1>Rapport des ventes</h1>
  <div class="meta">
    <div><strong>Période:</strong> {{ $from !== '' ? $from : '—' }} au {{ $to !== '' ? $to : '—' }}</div>
    <div><strong>Filtre facture:</strong> {{ $q !== '' ? $q : '—' }}</div>
    <div><strong>Nombre de ventes:</strong> {{ $totals['count'] }}</div>
    <div><strong>Chiffre d'affaires:</strong> {{ number_format((float) $totals['amount'], 2, ',', ' ') }}</div>
  </div>

  <table>
    <thead>
      <tr>
        <th>Date</th>
        <th>Facture</th>
        <th>Utilisateur</th>
        <th class="right">Total</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($sales as $s)
        <tr>
          <td>{{ optional($s->sold_at)->format('d/m/Y H:i') }}</td>
          <td>{{ $s->invoice_number }}</td>
          <td>{{ $s->user?->name ?? '—' }}</td>
          <td class="right">{{ number_format((float) $s->total, 2, ',', ' ') }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
