@extends('layouts.app')

@section('content')
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">Create Product</div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>SKU</label>
                                <input type="text" name="sku" class="form-control" value="{{ old('sku') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Description</label>
                                <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Sale Price</label>
                                <input type="number" name="sale_price" class="form-control"
                                    value="{{ old('sale_price') }}">
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Stock Status</label>
                                <select name="stock_status" class="form-control">
                                    <option value="in_stock" {{ old('stock_status') == 'in_stock' ? 'selected' : '' }}>
                                        In Stock</option>
                                    <option value="out_of_stock"
                                        {{ old('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Gallery Images</label>
                                <input type="file" name="gallery_images[]" class="form-control" multiple>
                            </div>
                        </div>



                        <div class="row">
                            <button>Add Variation</button>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>
                            <button class="btn btn-primary">Save</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endsection

@endsection
