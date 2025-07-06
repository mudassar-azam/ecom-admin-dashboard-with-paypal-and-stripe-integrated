@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Attributes & Values</h4>
            <a href="{{ route('variations.create') }}" class="btn btn-success">Add</a>
        </div>

        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Attribute Name</th>
                            <th>Attribute Value</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attributes as $index => $attribute)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $attribute->name }}</td>
                                <td>
                                    {{ $attribute->values->pluck('name')->join(', ') }}
                                </td>
                                <td>
                                    {{ $attribute->values->first()?->created_at ? $attribute->values->first()->created_at->format('d M Y h:i A') : 'N/A' }}
                                </td>
                                <td>
                                    <a href="{{route('variations.edit', $attribute->id)}}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('variations.destroy', $attribute->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Are you sure?')"
                                            class="btn btn-sm btn-danger">Delete</button>
                                    </form>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No attributes found.</td>
                            </tr>
                        @endforelse
                    </tbody>


                </table>
            </div>
        </div>
    </div>
@endsection
