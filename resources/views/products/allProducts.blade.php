<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>All Products</title>
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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin: 20px;">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin: 20px;">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert" style="margin: 20px;">
            <i class="fas fa-exclamation-triangle"></i>
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert" style="margin: 20px;">
            <i class="fas fa-info-circle"></i>
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 1000);
            });
        });
    </script>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin: 20px;">
            <i class="fas fa-exclamation-circle"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @php
        $staticCarts = \App\Models\Cart::with('product')->where('user_id', 1)->get();
    @endphp

    <main class="container my-5">
        <div class="row">
            @forelse($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow">
                        <img src="{{ asset($product->main_image) }}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($product->short_description, 100) }}</p>
                            <p class="fw-bold">{{ $product->currency }}
                                {{ number_format($product->sale_price ?? $product->price, 2) }}</p>
                        </div>
                        <div class="card-footer d-flex gap-2 w-100">
                            <div class="w-50">
                                <button type="button" class="btn btn-sm btn-outline-primary w-100"
                                    onclick="window.location.href='{{ route('products.details', $product->id) }}'">
                                    View Product
                                </button>
                            </div>

                            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="w-50">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary w-100">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center">No products found.</p>
            @endforelse
        </div>
    </main>

    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">My Cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @forelse($staticCarts as $cart)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>
                                <strong>{{ $cart->product->name ?? '' }}</strong>
                                <div class="text-muted">{{ $cart->product->currency ?? '' }}
                                    {{ $cart->product->sale_price ?? '' }}
                                </div>
                            </div>
                            <form action="{{ route('cart.remove', $cart->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Remove</button>
                            </form>
                        </div>
                    @empty
                        <p>No items in cart.</p>
                    @endforelse
                    @if ($staticCarts->count())
                        <div class="mt-3 text-end">
                            <a href="{{ route('checkout.page') }}" class="btn btn-primary">Checkout</a>
                        </div>
                    @endif
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
