@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Stripe Orders</h4>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Order ID</th>
                                <th>Customer Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>City</th>
                                <th>State</th>
                                <th>ZIP</th>
                                <th>Country</th>
                                <th>Status</th>
                                <th>Total Items</th>
                                <th>Order Items</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $index => $order)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $order->_order_id ?? $order->id }}</td>
                                    <td>{{ $order->name }}</td>
                                    <td>{{ $order->email }}</td>
                                    <td>{{ $order->phone }}</td>
                                    <td>{{ Str::limit($order->shipping_address, 30) }}</td>
                                    <td>{{ $order->city }}</td>
                                    <td>{{ $order->state }}</td>
                                    <td>{{ $order->zipcode }}</td>
                                    <td>{{ $order->country }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm status-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#updateStatusModal" 
                                                data-order-id="{{ $order->id }}"
                                                data-current-status="{{ $order->status }}">
                                            @if($order->status == 1)
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($order->status == 2)
                                                <span class="badge bg-success">Delivered</span>
                                            @elseif($order->status == 3)
                                                <span class="badge bg-danger">Cancelled</span>
                                            @elseif($order->status == 4)
                                                <span class="badge bg-info">Processing</span>
                                            @elseif($order->status == 5)
                                                <span class="badge bg-primary">Shipped</span>
                                            @else
                                                <span class="badge bg-secondary">Unknown</span>
                                            @endif
                                        </button>
                                    </td>
                                    <td>{{ $order->items->count() }}</td>
                                    <td>
                                        <div class="small">
                                            @foreach($order->items as $item)
                                                <div class="mb-1">
                                                    <strong>{{ $item->product->name ?? 'Product Not Found' }}</strong>
                                                    <br>
                                                    <span class="text-muted">
                                                        Qty: {{ $item->quantity }} | 
                                                        Price: ${{ number_format($item->product->sale_price ?? $item->product->price ?? 0, 2) }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>{{ $order->created_at->format('d M Y h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="text-center">No Stripe orders found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateStatusModalLabel">Update Order Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateStatusForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">Order Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="1">Pending</option>
                                <option value="2">Delivered</option>
                                <option value="3">Cancelled</option>
                                <option value="4">Processing</option>
                                <option value="5">Shipped</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="status_notes" class="form-label">Status Notes (Optional)</label>
                            <textarea class="form-control" id="status_notes" name="status_notes" rows="3" placeholder="Add any notes about the status change..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .status-btn {
            border: none;
            background: none;
            padding: 0;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .status-btn:hover {
            transform: scale(1.1);
        }
        
        .status-btn .badge {
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
        }
    </style>

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
            const updateStatusModal = document.getElementById('updateStatusModal');
            if (updateStatusModal) {
                updateStatusModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const orderId = button.getAttribute('data-order-id');
                    const currentStatus = button.getAttribute('data-current-status');
                    const form = document.getElementById('updateStatusForm');
                    form.action = `/orders/${orderId}/update-status`;
                    const statusSelect = document.getElementById('status');
                    statusSelect.value = currentStatus;
                });
            }
        });
    </script>
@endsection 