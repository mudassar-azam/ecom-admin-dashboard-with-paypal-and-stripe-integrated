<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <header class="bg-dark text-white text-center py-3">
        <h2>Checkout</h2>
    </header>

    <main class="container my-5">
        <div class="row">
            <div class="col-md-8">
                <h4 class="mb-4">Your Cart Items</h4>

                @if ($cartItems->count())
                    <form id="orderForm" method="POST" action="{{ route('checkout.process') }}">
                        @csrf

                        @foreach ($cartItems as $cart)
                            <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                                <div class="w-50">
                                    <h5 class="mb-1">{{ $cart->product->name ?? 'N/A' }}</h5>
                                    <p class="text-muted mb-0">
                                        {{ $cart->product->currency ?? '' }}
                                        {{ number_format($cart->product->sale_price ?? 0, 2) }}
                                    </p>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="hidden" name="product_ids[]" value="{{ $cart->product->id }}">
                                    <input type="number" name="quantities[]" value="1" min="1"
                                        class="form-control form-control-sm quantity-input" style="width: 80px;" 
                                        data-price="{{ $cart->product->sale_price ?? 0 }}">
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('remove-form-{{ $cart->id }}').submit();" class="btn btn-sm btn-danger">Remove</a>
                                </div>
                            </div>
                        @endforeach

                        <div class="mb-3 mt-4">
                            <h3>Your Details</h3>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" name="name" id="name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" name="email" id="email" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" name="phone" id="phone" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="country" class="form-label">Country</label>
                                    <input type="text" name="country" id="country" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Shipping Address</label>
                            <textarea name="shipping_address" id="shipping_address" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" name="city" id="city" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="state" class="form-label">State/Province</label>
                                    <input type="text" name="state" id="state" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="zipcode" class="form-label">ZIP/Postal Code</label>
                                    <input type="text" name="zipcode" id="zipcode" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="total" id="totalAmount" value="0">

                        <div class="mt-4 text-end">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#paymentModal">
                                Confirm Order
                            </button>
                        </div>
                    </form>

                    @foreach ($cartItems as $cart)
                        <form id="remove-form-{{ $cart->id }}" action="{{ route('cart.remove', $cart->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endforeach
                @else
                    <p class="text-muted">No items in your cart.</p>
                @endif
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="subtotal">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span id="shipping">${{ number_format($shippingCost, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total:</span>
                            <span id="total">${{ number_format($shippingCost, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">
                <h5 class="modal-title mb-3">Choose Payment Method</h5>
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-primary w-45" onclick="submitOrder('paypal')">Pay with
                        PayPal</button>
                    <button type="button" class="btn btn-outline-dark w-45" onclick="submitOrder('stripe')">Pay with
                        Stripe</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3">
        <p class="m-0">&copy; {{ date('Y') }} Your Store</p>
    </footer>

    <script>
        function calculateTotal() {
            let subtotal = 0;
            const quantities = document.querySelectorAll('.quantity-input');
            
            quantities.forEach(function(input) {
                const quantity = parseInt(input.value) || 0;
                const price = parseFloat(input.dataset.price) || 0;
                subtotal += quantity * price;
            });
            
            const shipping = {{ $shippingCost }};
            const total = subtotal + shipping;
            
            document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
            document.getElementById('total').textContent = '$' + total.toFixed(2);
            document.getElementById('totalAmount').value = total.toFixed(2);
        }

        document.addEventListener('DOMContentLoaded', function() {
            calculateTotal();
            
            document.querySelectorAll('.quantity-input').forEach(function(input) {
                input.addEventListener('change', calculateTotal);
                input.addEventListener('input', calculateTotal);
            });
        });

        function submitOrder(method) {
            const form = document.getElementById('orderForm');
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'payment_method';
            hiddenInput.value = method;
            form.appendChild(hiddenInput);
            form.submit();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
