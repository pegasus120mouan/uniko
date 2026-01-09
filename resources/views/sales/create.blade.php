@extends('layout.main')

@section('title', 'Nouvelle vente')

@section('content')
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Nouvelle vente</h4>
      <a href="{{ route('admin.sales.index') }}" class="btn btn-outline-secondary">Retour</a>
    </div>

    <div class="card">
      <div class="card-body">
        <form method="POST" action="{{ route('admin.sales.store') }}">
          @csrf

          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label" for="sold_at">Date</label>
              <input type="datetime-local" id="sold_at" name="sold_at" value="{{ old('sold_at', now()->format('Y-m-d\TH:i')) }}" class="form-control @error('sold_at') is-invalid @enderror" required>
              @error('sold_at')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label" for="discount">Remise</label>
              <input type="number" step="0.01" min="0" id="discount" name="discount" value="{{ old('discount', 0) }}" class="form-control @error('discount') is-invalid @enderror">
              @error('discount')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label" for="tax">Taxe</label>
              <input type="number" step="0.01" min="0" id="tax" name="tax" value="{{ old('tax', 0) }}" class="form-control @error('tax') is-invalid @enderror">
              @error('tax')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
          @endif

          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0">Produits</h5>
            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRow()">Ajouter une ligne</button>
          </div>

          <div class="table-responsive">
            <table class="table" id="itemsTable">
              <thead>
                <tr>
                  <th>Produit</th>
                  <th style="width: 160px;">Quantité</th>
                  <th style="width: 60px;"></th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>

          <button class="btn btn-primary" type="submit">Enregistrer la vente</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  let rowIndex = 0;
  const products = @json($products->map(fn($p) => ['id' => $p->id, 'label' => $p->name.' ('.$p->brand.') — Stock: '.$p->quantity]));

  function addRow() {
    const tbody = document.querySelector('#itemsTable tbody');
    const tr = document.createElement('tr');

    const tdProduct = document.createElement('td');
    const select = document.createElement('select');
    select.className = 'form-select';
    select.name = `items[${rowIndex}][product_id]`;

    const opt0 = document.createElement('option');
    opt0.value = '';
    opt0.textContent = '—';
    select.appendChild(opt0);

    products.forEach(p => {
      const opt = document.createElement('option');
      opt.value = p.id;
      opt.textContent = p.label;
      select.appendChild(opt);
    });

    tdProduct.appendChild(select);

    const tdQty = document.createElement('td');
    const qty = document.createElement('input');
    qty.type = 'number';
    qty.min = '1';
    qty.value = '1';
    qty.className = 'form-control';
    qty.name = `items[${rowIndex}][quantity]`;
    tdQty.appendChild(qty);

    const tdRemove = document.createElement('td');
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'btn btn-sm btn-outline-danger';
    btn.textContent = 'X';
    btn.onclick = () => tr.remove();
    tdRemove.appendChild(btn);

    tr.appendChild(tdProduct);
    tr.appendChild(tdQty);
    tr.appendChild(tdRemove);

    tbody.appendChild(tr);
    rowIndex++;
  }

  document.addEventListener('DOMContentLoaded', () => {
    addRow();
  });
</script>
@endsection
