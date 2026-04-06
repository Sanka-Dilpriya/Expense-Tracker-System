@extends('layouts.app')

@section('title', 'Edit Transaction')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-6 col-lg-7">
        <div class="card card-hero p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-1">Edit Transaction</h3>
                    <p class="text-secondary mb-0">Update transaction details and save the latest data.</p>
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

            <form method="POST" action="{{ route('expenses.update', $expense) }}">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select" required>
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}" {{ old('type', $expense->type) === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select" required>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ old('category', $expense->category) === $category ? 'selected' : '' }}>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $expense->title) }}" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Amount</label>
                        <input type="number" name="amount" class="form-control" step="0.01" value="{{ old('amount', $expense->amount) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date</label>
                        <input type="date" name="date" class="form-control" value="{{ old('date', $expense->date->format('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Note</label>
                    <textarea name="note" rows="4" class="form-control">{{ old('note', $expense->note) }}</textarea>
                </div>

                <button class="btn btn-primary w-100">Update transaction</button>
            </form>
        </div>
    </div>
</div>
@endsection
