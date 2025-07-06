@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Shipping Settings</h4>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('shipping-settings.update') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="shipping_cost" class="form-label">Shipping Cost ($)</label>
                                <input type="number" name="shipping_cost" id="shipping_cost" class="form-control" 
                                       value="{{ $shippingSetting->shipping_cost ?? 0 }}" step="0.01" min="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Update Shipping Cost</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection 