@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Create Variation</h4>
                </div>
                <div class="card-body">
                    <form action="{{route('variations.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Attribute Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Attribute Values</label>
                            <div id="value-container">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="values[]" placeholder="Value Name">
                                    <input type="file" class="form-control" name="images[]">
                                    <input type="color" class="form-control form-control-color" name="colors[]">
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addValueField()">+ Add Value</button>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{route('variations.index')}}" class="btn btn-secondary me-2">Back</a>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
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
@endsection
