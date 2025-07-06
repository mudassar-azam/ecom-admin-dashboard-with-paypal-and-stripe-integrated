@extends('layouts.app')
@section('content')
    <div class="container mt-4">
        <a href="{{ route('currencies.create') }}" class="btn btn-primary mb-3">Add Currency</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Symbol</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($currencies as $currency)
                    <tr>
                        <td>{{ $currency->symbol }}</td>
                        <td>{{ $currency->name }}</td>
                        <td>
                            <a href="{{ route('currencies.edit', $currency) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('currencies.destroy', $currency) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
