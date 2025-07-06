<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $product->name }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <header class="bg-secondary text-white text-center py-3 opacity-50 d-flex justify-content-between px-4">
        <h2 class="m-0">Header</h2>
        <button class="btn btn-light position-relative" data-bs-toggle="modal" data-bs-target="#cartModal">
            🛒 Cart
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ \App\Models\Cart::where('user_id', 1)->count() }}
            </span>
        </button>
    </header>

    <main class="container my-5">
        <div class="row">
            <div class="col-md-5">
                @if (!empty($product->main_image))
                    <img src="{{ asset($product->main_image) }}" class="img-fluid rounded shadow mb-3"
                        alt="{{ $product->name ?? '' }}">
                @endif

                @if (!empty($product->gallery_images))
                    <div class="d-flex flex-wrap gap-2">
                        @foreach (json_decode($product->gallery_images, true) as $image)
                            <img src="{{ asset($image) }}" class="img-thumbnail" width="80" alt="Gallery Image">
                        @endforeach
                    </div>
                @endif

                @if (!empty($product->video_url))
                    <div class="mt-3">
                        <li class="list-group-item">
                            <strong>Product Video:</strong><br>
                            <div class="ratio ratio-16x9">
                                <iframe src="{{ $product->video_url }}" title="Product video" allowfullscreen></iframe>
                            </div>
                        </li>
                    </div>
                @endif
            </div>

            <div class="col-md-7">
                @if (!empty($product->name))
                    <h2 class="mb-3">{{ $product->name }}</h2>
                @endif
                <ul class="list-group list-group-flush">
                    @if (!empty($product->brand?->name))
                        <li class="list-group-item"><strong>Brand:</strong> {{ $product->brand->name }}</li>
                    @endif

                    @if (!empty($product->category?->name))
                        <li class="list-group-item"><strong>Category:</strong> {{ $product->category->name }}</li>
                    @endif

                    @if (!empty($product->slug))
                        <li class="list-group-item"><strong>Slug:</strong> {{ $product->slug }}</li>
                    @endif

                    @if (!empty($product->sku))
                        <li class="list-group-item"><strong>SKU:</strong> {{ $product->sku }}</li>
                    @endif

                    @if (!empty($product->description))
                        <li class="list-group-item"><strong>Description:</strong><br>{!! $product->description !!}</li>
                    @endif

                    @if (!empty($product->short_description))
                        <li class="list-group-item"><strong>Short Description:</strong><br>{!! $product->short_description !!}</li>
                    @endif

                    @if (!empty($product->price))
                        <li class="list-group-item"><strong>Price:</strong> {{ $product->currency ?? '' }}
                            {{ number_format($product->price, 2) }}</li>
                    @endif

                    @if (!empty($product->sale_price))
                        <li class="list-group-item"><strong>Sale Price:</strong> {{ $product->currency ?? '' }}
                            {{ number_format($product->sale_price, 2) }}</li>
                    @endif

                    @if (!empty($product->quantity))
                        <li class="list-group-item"><strong>Quantity:</strong> {{ $product->quantity }}</li>
                    @endif

                    @if (!empty($product->stock_status))
                        <li class="list-group-item"><strong>Stock Status:</strong>
                            {{ ucfirst(str_replace('_', ' ', $product->stock_status)) }}</li>
                    @endif

                    @if (!empty($product->weight))
                        <li class="list-group-item"><strong>Weight:</strong> {{ $product->weight }}</li>
                    @endif

                    @if (!empty($product->dimensions))
                        <li class="list-group-item"><strong>Dimensions:</strong> {{ $product->dimensions }}</li>
                    @endif

                    @if (!empty($product->tags))
                        <li class="list-group-item"><strong>Tags:</strong> {{ $product->tags }}</li>
                    @endif

                    @if (!empty($product->features))
                        <li class="list-group-item"><strong>Features:</strong><br>{{ $product->features }}</li>
                    @endif

                    @if (!empty($product->meta_title))
                        <li class="list-group-item"><strong>Meta Title:</strong> {{ $product->meta_title }}</li>
                    @endif

                    @if (!empty($product->meta_description))
                        <li class="list-group-item"><strong>Meta Description:</strong><br>{!! $product->meta_description !!}</li>
                    @endif

                    @if (!empty($product->meta_keywords))
                        <li class="list-group-item"><strong>Meta Keywords:</strong><br>{{ $product->meta_keywords }}
                        </li>
                    @endif

                    @if (!empty($product->meta_canonical))
                        <li class="list-group-item"><strong>Meta Canonical:</strong> {{ $product->meta_canonical }}
                        </li>
                    @endif

                    @if (!empty($product->cost_price))
                        <li class="list-group-item"><strong>Cost Price:</strong> {{ $product->cost_price }}</li>
                    @endif

                    @if (!empty($product->shipping_class))
                        <li class="list-group-item"><strong>Shipping Class:</strong> {{ $product->shipping_class }}
                        </li>
                    @endif

                    @if (!is_null($product->is_featured))
                        <li class="list-group-item"><strong>Is Featured:</strong>
                            {{ $product->is_featured ? 'Yes' : 'No' }}</li>
                    @endif

                    @if (!empty($product->status))
                        <li class="list-group-item"><strong>Status:</strong> {{ ucfirst($product->status) }}</li>
                    @endif

                    @if (!empty($product->average_rating))
                        <li class="list-group-item"><strong>Average Rating:</strong> {{ $product->average_rating }}/5
                        </li>
                    @endif

                    @if (!empty($product->published_at))
                        <li class="list-group-item"><strong>Published At:</strong> {{ $product->published_at }}</li>
                    @endif
                </ul>
            </div>
        </div>
    </main>



    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Your Cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @php
                        $cartItems = \App\Models\Cart::with('product')->where('user_id', 1)->get();
                    @endphp
                    @forelse ($cartItems as $item)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>{{ $item->product->name ?? '' }}</div>
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Remove</button>
                            </form>
                        </div>
                    @empty
                        <p>No items in cart.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-secondary text-white text-center py-3 opacity-50">
        <p class="m-0">Footer</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
