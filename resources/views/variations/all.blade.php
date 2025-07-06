@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Product List</h4>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>SKU</th>
                        <th>Description</th>
                        <th>Sale Price</th>
                        <th>Stock Status</th>
                        <th>Gallery Images</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($variations as $variation)
                        <tr>
                            <td>{{ $variation->id }}</td>
                            <td>{{ $variation->name }}</td>
                            <td>{{ $variation->sku }}</td>
                            <td>{{ $variation->description }}</td>
                            <td>{{ $variation->sale_price }}</td>
                            <td>{{ ucfirst($variation->stock_status) }}</td>
                            <td>
                                @if ($variation->gallery_images)
                                    @foreach (json_decode($variation->gallery_images, true) as $img)
                                        <img src="{{ asset($img) }}" width="50" class="me-1 mb-1">
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No variations found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
