@extends('layouts.admin')
@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')

<div class="grid-2" style="align-items: start;">

    <!-- Add Category -->
    <div class="card">
        <div class="card-header"><h2><i class="fas fa-plus-circle" style="color:#1565c0;"></i> Add Category</h2></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Category Name <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           placeholder="e.g. Admit Card, Result, Sarkari Job..."
                           value="{{ old('name') }}" required autofocus>
                    @error('name')<p class="invalid-feedback">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Category
                </button>
            </form>
        </div>
    </div>

    <!-- Categories List -->
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-th-large" style="color:#1565c0;"></i> All Categories</h2>
            <span style="font-size: 0.8rem; color: #94a3b8;">{{ $categories->count() }} total</span>
        </div>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Blogs</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr>
                        <td style="font-weight: 500;">{{ $cat->name }}</td>
                        <td style="font-size: 0.8rem; color: #94a3b8; font-family: monospace;">{{ $cat->slug }}</td>
                        <td><span class="badge badge-blue">{{ $cat->blogs_count }}</span></td>
                        <td>
                            <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}"
                                  onsubmit="return confirm('Delete category \'{{ $cat->name }}\'? Blogs in this category may be affected.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center; color:#94a3b8; padding: 2rem;">
                            No categories yet. Add one!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
