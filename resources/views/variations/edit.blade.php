@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-white">
                        <h4 class="mb-0">Edit Variation</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('variations.update', $attribute->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Attribute Name</label>
                                <input type="text" class="form-control" name="name" value="{{ $attribute->name }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Attribute Values</label>
                                <div id="value-container">
                                    @foreach ($attribute->values as $value)
                                        <div class="input-group mb-2">
                                            <input type="hidden" name="value_ids[]" value="{{ $value->id }}">
                                            <input type="text" class="form-control" name="values[]"
                                                value="{{ $value->name }}">
                                            <input type="file" class="form-control" name="images[]">
                                            @if ($value->image)
                                                <img src="{{ asset($value->image) }}" width="40" height="40" style="object-fit:cover;">
                                            @endif
                                            <input type="color" class="form-control form-control-color" name="colors[]" value="{{ $value->color }}">
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2"
                                    onclick="addValueField()">+ Add Value</button>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('variations.index') }}" class="btn btn-secondary me-2">Back</a>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>

                        <script>
                            function addValueField() {
                                const container = document.getElementById('value-container');

                                const wrapper = document.createElement('div');
                                wrapper.className = 'input-group mb-2';

                                const input = document.createElement('input');
                                input.type = 'text';
                                input.name = 'values[]';
                                input.className = 'form-control';
                                input.placeholder = 'Value Name';

                                const image = document.createElement('input');
                                image.type = 'file';
                                image.name = 'images[]';
                                image.className = 'form-control';

                                const color = document.createElement('input');
                                color.type = 'color';
                                color.name = 'colors[]';
                                color.className = 'form-control form-control-color';

                                wrapper.appendChild(input);
                                wrapper.appendChild(image);
                                wrapper.appendChild(color);
                                container.appendChild(wrapper);
                            }
                        </script>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
