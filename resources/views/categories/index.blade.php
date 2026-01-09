@extends('layout.main')

@section('title', 'Catégories')

@section('content')
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Catégories</h4>
      <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Nouvelle catégorie</a>
    </div>

    @if (session('status'))
      <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.categories.index') }}" class="mb-4">
      <div class="input-group">
        <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Rechercher par nom...">
        <button class="btn btn-outline-primary" type="submit">Rechercher</button>
        @if ($q)
          <a class="btn btn-outline-secondary" href="{{ route('admin.categories.index') }}">Réinitialiser</a>
        @endif
      </div>
    </form>

    <div class="card">
      <div class="table-responsive text-nowrap">
        <table class="table">
          <thead>
            <tr>
              <th>Nom</th>
              <th>Description</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @forelse ($categories as $category)
              <tr>
                <td>{{ $category->name }}</td>
                <td class="text-truncate" style="max-width: 420px;">{{ $category->description }}</td>
                <td class="text-end">
                  <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                  <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer cette catégorie ?')">Supprimer</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="text-center text-body-secondary">Aucune catégorie.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="mt-4">
      {{ $categories->links() }}
    </div>
  </div>
</div>
@endsection
