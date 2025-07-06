@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Product List</h4>
            <a href="{{ route('products.create') }}" class="btn btn-success">Add Product</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Brand</th>
                        <th>Category</th>
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Sale Price</th>
                        <th>Currency</th>
                        <th>Qty</th>
                        <th>Status</th>
                        <th>Main Image</th>
                        <th>Gallery Images</th>
                        <th>Rating</th>
                        <th>Meta Title</th>
                        <th>Meta Desc</th>
                        <th>Meta Keywords</th>
                        <th>Tags</th>
                        <th>Cost Price</th>
                        <th>Published At</th>
                        <th>Variations</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->slug }}</td>
                            <td>{{ $product->brand->name ?? 'N/A' }}</td>
                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->sale_price }}</td>
                            <td>{{ $product->currency }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ ucfirst($product->status) }}</td>
                            <td>
                                @if ($product->main_image)
                                    <img src="{{ asset($product->main_image) }}" width="60">
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if ($product->gallery_images)
                                    @foreach (json_decode($product->gallery_images, true) as $image)
                                        <img src="{{ asset($image) }}" width="60" class="me-1 mb-1">
                                    @endforeach
                                @else
                                    N/A
                                @endif

                            </td>
                            <td>
                                @for ($i = 1; $i <= $product->average_rating; $i++)
                                    <span>⭐</span>
                                @endfor
                            </td>
                            <td>{{ $product->meta_title }}</td>
                            <td>{{ $product->meta_description }}</td>
                            <td>{{ $product->meta_keywords }}</td>
                            <td>{{ $product->tags }}</td>
                            <td>{{ $product->cost_price }}</td>
                            <td>{{ $product->published_at ? \Carbon\Carbon::parse($product->published_at)->format('d M Y h:i A') : 'N/A' }}
                            </td>
                            <td><a  href="@if($product->variations->count() > 0) {{route('product.variations' , $product->id)}} @else # @endif"
                                    class="btn btn-primary">
                                    Variations
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('products.edit', $product->id) }}"
                                    class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                    class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
