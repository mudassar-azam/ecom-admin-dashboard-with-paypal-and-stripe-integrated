@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">All Brands</h4>
        <a href="{{ route('brands.create') }}" class="btn btn-primary">+ Add Brand</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Logo</th>
                        <th>Meta Title</th>
                        <th>Meta Description</th>
                        <th>Meta Keywords</th>
                        <th>Created At</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($brands as $index => $brand)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $brand->name }}</td>
                            <td>{{ $brand->slug }}</td>
                            <td>
                                @if ($brand->logo)
                                    <img src="{{ asset($brand->logo) }}" alt="Logo" height="40">
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $brand->meta_title }}</td>
                            <td>{{ $brand->meta_description }}</td>
                            <td>{{ $brand->meta_keywords }}</td>
                            <td>{{ $brand->created_at->format('d M Y h:i A') }}</td>
                            <td>
                                <a href="{{ route('brands.edit', $brand->id) }}" class="btn btn-sm btn-warning mb-1">
                                    Edit
                                </a>

                                <form action="{{ route('brands.destroy', $brand->id) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this brand?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No brands found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
