@extends('layouts.app')

@section('title', 'Add Transaction')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-6 col-lg-7">
        <div class="card card-hero p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-1">Add Transaction</h3>
                    <p class="text-secondary mb-0">Record a new expense or income entry quickly.</p>
                </div>
                <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">Back to history</a>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('expenses.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="form-control" placeholder="e.g. Office supplies" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select" required>
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}" {{ old('type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select" required>
                            <option value="">Select category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ old('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Amount</label>
                        <input type="number" name="amount" value="{{ old('amount') }}" class="form-control" step="0.01" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date</label>
                        <input type="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Note</label>
                    <textarea name="note" rows="4" class="form-control">{{ old('note') }}</textarea>
                </div>

                <button class="btn btn-primary w-100">Save transaction</button>
            </form>
        </div>
    </div>
</div>
@endsection