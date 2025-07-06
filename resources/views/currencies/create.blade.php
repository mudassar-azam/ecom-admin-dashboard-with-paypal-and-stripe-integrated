@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <form action="{{ route('currencies.store') }}" method="POST" class="mb-4">
                    @csrf
                    <div class="mb-3">
                        <label>Symbol</label>
                        <input type="text" name="symbol" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <button class="btn btn-success">Save</button>
                </form>

                <form action="{{ route('currencies.setDefault') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Set Default Currency</label>
                        <select name="currency_id" class="form-control" required>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}" {{ $currency->is_default ? 'selected' : '' }}>
                                    {{ $currency->symbol }} - {{ $currency->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-primary">Set</button>
                </form>

            </div>
        </div>
    </div>
@endsection
