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
                            <th>Image</th>
                            <th>Color</th>
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
                                    @foreach ($attribute->values as $value)
                                        @if ($value->image)
                                            <img src="{{ asset($value->image) }}" width="40" height="40" style="object-fit:cover;" class="me-1 mb-1">
                                        @else
                                            N/A
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($attribute->values as $value)
                                        @if ($value->color)
                                            <span style="display:inline-block;width:24px;height:24px;background:{{ $value->color }};border-radius:50%;border:1px solid #ccc;vertical-align:middle;margin-right:4px;"></span>
                                        @else
                                            N/A
                                        @endif
                                    @endforeach
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
