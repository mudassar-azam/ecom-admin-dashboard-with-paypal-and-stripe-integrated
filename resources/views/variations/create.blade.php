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
                    <form action="{{route('variations.store')}}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Attribute Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="value" class="form-label">Attribute Value</label>
                            <input type="text" class="form-control" name="value" required>
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
@endsection
