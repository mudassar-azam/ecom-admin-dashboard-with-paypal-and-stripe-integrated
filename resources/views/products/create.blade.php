@extends('layouts.app')

@section('content')

    <div class="container mt-4">
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
                                <div class="col-md-6 mb-3">
                                    <label>Slug</label>
                                    <input type="text" name="slug"
                                        class="form-control @error('slug') is-invalid @enderror"
                                        value="{{ old('slug') }}">

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
                                                {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Category</label>
                                    <select name="category_id" class="form-control">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
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
                                    <textarea name="description" class="form-control rich-text">{{ old('description') }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Short Description</label>
                                    <textarea name="short_description" class="form-control rich-text">{{ old('short_description') }}</textarea>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Price</label>
                                    <input type="number" name="price" class="form-control" value="{{ old('price') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Sale Price</label>
                                    <input type="number" name="sale_price" class="form-control"
                                        value="{{ old('sale_price') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Currency</label>
                                    <input type="text" name="currency" class="form-control"
                                        value="{{ $currency->symbol }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Quantity / Stock</label>
                                    <input type="number" name="quantity" class="form-control"
                                        value="{{ old('quantity') }}">
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
                                <div class="col-md-6 mb-3">
                                    <label>Main Image</label>
                                    <input type="file" name="main_image" class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Gallery Images</label>
                                    <input type="file" name="gallery_images[]" class="form-control" multiple>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Video URL</label>
                                    <input type="url" name="video_url" class="form-control"
                                        value="{{ old('video_url') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Weight</label>
                                    <input type="text" name="weight" class="form-control" value="{{ old('weight') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Dimensions (LxWxH)</label>
                                    <input type="text" name="dimensions" class="form-control" placeholder="e.g. 10x20x30"
                                        value="{{ old('dimensions') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Tags</label>
                                    <input type="text" name="tags" class="form-control"
                                        value="{{ old('tags') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Features</label>
                                    <textarea name="features" class="form-control">{{ old('features') }}</textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Meta Title</label>
                                    <input type="text" name="meta_title" class="form-control"
                                        value="{{ old('meta_title') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Meta Description</label>
                                    <textarea name="meta_description" class="form-control">{{ old('meta_description') }}</textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Meta Keywords</label>
                                    <textarea name="meta_keywords" class="form-control">{{ old('meta_keywords') }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Meta Canonical</label>
                                    <input type="text" name="meta_canonical" class="form-control"
                                        value="{{ old('meta_canonical') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Cost Price</label>
                                    <input type="number" name="cost_price" class="form-control"
                                        value="{{ old('cost_price') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Shipping Class</label>
                                    <input type="text" name="shipping_class" class="form-control"
                                        value="{{ old('shipping_class') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Is Featured?</label>
                                    <select name="is_featured" class="form-control">
                                        <option value="1" {{ old('is_featured') == '1' ? 'selected' : '' }}>Yes
                                        </option>
                                        <option value="0" {{ old('is_featured') == '0' ? 'selected' : '' }}>No
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Average Rating</label><br>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <input type="radio" name="average_rating" value="{{ $i }}"
                                            {{ old('average_rating') == $i ? 'checked' : '' }}> ⭐
                                    @endfor
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Publish Date</label>
                                    <input type="datetime-local" name="published_at" class="form-control"
                                        value="{{ old('published_at') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" id="addVariationBtn" class="btn btn-info mb-3">Add
                                        Variation</button>
                                </div>
                            </div>

                            <div class="row" id="variationSection" style="display: none;">
                                <div class="col-md-6 mb-3">
                                    <label for="attributes">Attributes</label>
                                    <select id="attributes" name="attributes[]" class="form-control select2" multiple>
                                        @foreach ($attributes as $attribute)
                                            <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="attribute_values">Attribute Values</label>
                                    <select id="attribute_values" name="attribute_values[]" class="form-control select2"
                                        multiple>
                                    </select>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <button type="button" id="createVariationBtn" class="btn btn-success">Create
                                        Variation</button>
                                </div>

                                <div id="variationFieldsContainer" class="row"></div>
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
    </div>


    @if ($errors->any())
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                @foreach ($errors->all() as $error)
                    showToast("{{ $error }}");
                @endforeach
            });

            function showToast(message) {
                const toast = document.createElement('div');
                toast.textContent = message;
                toast.style.position = 'fixed';
                toast.style.top = '20px';
                toast.style.right = '20px';
                toast.style.background = '#dc3545';
                toast.style.color = 'white';
                toast.style.padding = '10px 15px';
                toast.style.borderRadius = '4px';
                toast.style.boxShadow = '0 0 10px rgba(0,0,0,0.1)';
                toast.style.zIndex = 9999;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 4000);
            }
        </script>
    @endif


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.select2').select2({
                width: '100%'
            });

            const attributeValueMap = {};
            let previouslySelectedAttributes = [];

            document.getElementById('addVariationBtn').addEventListener('click', function() {
                document.getElementById('variationSection').style.display = 'flex';
            });

            $('#attributes').on('change', function() {
                let currentSelectedAttributes = $(this).val() || [];

                const removedAttributes = previouslySelectedAttributes.filter(attr => !
                    currentSelectedAttributes.includes(attr));
                const addedAttributes = currentSelectedAttributes.filter(attr => !
                    previouslySelectedAttributes.includes(attr));

                removedAttributes.forEach(attributeId => {
                    if (attributeValueMap[attributeId]) {
                        attributeValueMap[attributeId].forEach(valueId => {
                            $(`#attribute_values option[value="${valueId}"]`).remove();
                        });
                        delete attributeValueMap[attributeId];
                    }
                });

                if (addedAttributes.length > 0) {
                    fetch(`/attribute-values/${addedAttributes.join(',')}`)
                        .then(response => response.json())
                        .then(data => {
                            const valueSelect = document.getElementById('attribute_values');
                            const existingValues = Array.from(valueSelect.options).map(opt => opt
                                .value);

                            data.forEach(function(value) {
                                if (!existingValues.includes(String(value.id))) {
                                    const option = document.createElement('option');
                                    option.value = value.id;
                                    option.text = value.name;
                                    option.dataset.attributeId = value.attribute_id;
                                    valueSelect.appendChild(option);
                                }

                                if (!attributeValueMap[value.attribute_id]) {
                                    attributeValueMap[value.attribute_id] = [];
                                }
                                if (!attributeValueMap[value.attribute_id].includes(String(value
                                        .id))) {
                                    attributeValueMap[value.attribute_id].push(String(value
                                        .id));
                                }
                            });

                            $('#attribute_values').trigger('change');
                        });
                }

                previouslySelectedAttributes = currentSelectedAttributes;
            });
        });
    </script>
    <script>
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-variation')) {
                e.target.closest('.card.border').remove();
            }
        });
        document.getElementById('createVariationBtn').addEventListener('click', function() {
            const selectedValues = $('#attribute_values').select2('data');

            const container = document.getElementById('variationFieldsContainer');
            container.innerHTML = '';

            const mainName = document.querySelector('input[name="name"]').value;
            const mainSKU = document.querySelector('input[name="sku"]').value;
            const mainDescription = document.querySelector('textarea[name="description"]').value;
            const mainSalePrice = document.querySelector('input[name="sale_price"]').value;
            const mainStockStatus = document.querySelector('select[name="stock_status"]').value;

            const selectedOptions = document.querySelectorAll('#attribute_values option:checked');

            selectedOptions.forEach(option => {
                const valueId = option.value;
                const valueText = option.textContent;
                const attributeId = option.dataset.attributeId;

                const div = document.createElement('div');
                div.classList.add('col-md-12', 'mb-4');
                div.innerHTML = `
                    <div class="card border border-secondary">
                        <div class="card-header bg-light fw-bold">Variation: ${valueText}</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Name</label>
                                    <input type="text" name="variations[${valueId}][name]" class="form-control" value="${mainName}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>SKU</label>
                                    <input type="text" name="variations[${valueId}][sku]" class="form-control" value="${mainSKU}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Description</label>
                                    <textarea name="variations[${valueId}][description]" class="form-control">${mainDescription}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Sale Price</label>
                                    <input type="number" name="variations[${valueId}][sale_price]" class="form-control" value="${mainSalePrice}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Stock Status</label>
                                    <select name="variations[${valueId}][stock_status]" class="form-control">
                                        <option value="in_stock" ${mainStockStatus === 'in_stock' ? 'selected' : ''}>In Stock</option>
                                        <option value="out_of_stock" ${mainStockStatus === 'out_of_stock' ? 'selected' : ''}>Out of Stock</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Gallery Images</label>
                                    <input type="file" name="variations[${valueId}][gallery_images][]" class="form-control" multiple>
                                </div>
                            </div>

                            <input type="hidden" name="variations[${valueId}][attribute_id]" value="${attributeId}">
                            <input type="hidden" name="variations[${valueId}][value_id]" value="${valueId}">
                            <div class="text-end">
                                <button type="button" class="btn btn-danger remove-variation">Remove</button>
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(div);
            });

        });
    </script>
    <script src="/assets/js/plugin/summernote/summernote-lite.min.js"></script>
    <script>
$(function() {
  $('.rich-text').summernote({
    height: 200
  });
});
</script>
@endsection

@endsection
