@extends('layouts.app')

@section('content')

    <div class="container mt-4">
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">Edit Product</div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Name</label>
                                    <input type="text" name="name" value="{{ $product->name }}" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Slug</label>
                                    <input type="text" name="slug" value="{{ old('slug', $product->slug) }}"
                                        class="form-control @error('slug') is-invalid @enderror">

                                    @error('slug')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Brand</label>
                                    <select name="brand_id" class="form-control">
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}"
                                                {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Category</label>
                                    <select name="category_id" class="form-control">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>SKU</label>
                                    <input type="text" name="sku" value="{{ $product->sku }}" class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control rich-text">{{ $product->description }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Short Description</label>
                                    <textarea name="short_description" class="form-control rich-text">{{ $product->short_description }}</textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Price</label>
                                    <input type="number" name="price" value="{{ $product->price }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Sale Price</label>
                                    <input type="number" name="sale_price" value="{{ $product->sale_price }}"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Currency</label>
                                    <input type="text" name="currency" value="{{ $currency->symbol }}" readonly
                                        class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Quantity / Stock</label>
                                    <input type="number" name="quantity" value="{{ $product->quantity }}"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Stock Status</label>
                                    <select name="stock_status" class="form-control">
                                        <option value="in_stock"
                                            {{ $product->stock_status == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                        <option value="out_of_stock"
                                            {{ $product->stock_status == 'out_of_stock' ? 'selected' : '' }}>Out of Stock
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Main Image</label><br>
                                    @if ($product->main_image)
                                        <img src="{{ asset($product->main_image) }}" height="80" class="mb-2"><br>
                                    @endif
                                    <input type="file" name="main_image" class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Gallery Images</label><br>
                                    @if ($product->gallery_images)
                                        @foreach (json_decode($product->gallery_images, true) as $image)
                                            <img src="{{ asset($image) }}" width="60" class="me-1 mb-1">
                                        @endforeach
                                        <br>
                                    @endif
                                    <input type="file" name="gallery_images[]" class="form-control" multiple>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Video URL</label>
                                    <input type="url" name="video_url" value="{{ $product->video_url }}"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Weight</label>
                                    <input type="text" name="weight" value="{{ $product->weight }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Dimensions (LxWxH)</label>
                                    <input type="text" name="dimensions" value="{{ $product->dimensions }}"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Tags</label>
                                    <input type="text" name="tags" value="{{ $product->tags }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Features</label>
                                    <textarea name="features" class="form-control">{{ $product->features }}</textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Meta Title</label>
                                    <input type="text" name="meta_title" value="{{ $product->meta_title }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Meta Description</label>
                                    <textarea name="meta_description" class="form-control">{{ $product->meta_description }}</textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Meta Keywords</label>
                                    <textarea name="meta_keywords" class="form-control">{{ $product->meta_keywords }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Meta Canonical</label>
                                    <input type="text" name="meta_canonical" value="{{ $product->meta_canonical }}"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Cost Price</label>
                                    <input type="number" name="cost_price" value="{{ $product->cost_price }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Shipping Class</label>
                                    <input type="text" name="shipping_class" value="{{ $product->shipping_class }}"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Is Featured?</label>
                                    <select name="is_featured" class="form-control">
                                        <option value="1" {{ $product->is_featured ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ !$product->is_featured ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                        <option value="draft" {{ $product->status == 'draft' ? 'selected' : '' }}>Draft
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Average Rating</label><br>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <input type="radio" name="average_rating" value="{{ $i }}"
                                            {{ $product->average_rating == $i ? 'checked' : '' }}> ⭐
                                    @endfor
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Publish Date</label>
                                    <input type="datetime-local" name="published_at"
                                        value="{{ $product->published_at ? \Carbon\Carbon::parse($product->published_at)->format('Y-m-d\TH:i') : '' }}"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="text-end">
                                <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>
                                <button class="btn btn-primary">Update</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replaceAll('rich-text');
    </script>
@endsection

@endsection
