@extends('layouts.app')

@section('title', 'Transaction Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-9">
        <div class="card card-hero p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-1">Transaction details</h3>
                    <p class="text-secondary mb-0">Review the transaction information and choose whether to edit or delete it.</p>
                </div>
                <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">Back to list</a>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <h6 class="text-uppercase text-muted mb-2">Title</h6>
                        <p class="mb-0">{{ $expense->title }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <h6 class="text-uppercase text-muted mb-2">Category</h6>
                        <p class="mb-0">{{ $expense->category }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <h6 class="text-uppercase text-muted mb-2">Amount</h6>
                        <p class="mb-0">LKR {{ number_format($expense->amount, 2) }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <h6 class="text-uppercase text-muted mb-2">Date</h6>
                        <p class="mb-0">{{ $expense->date->format('Y-m-d') }}</p>
                    </div>
                </div>
                <div class="col-12">
                    <div class="border rounded p-3">
                        <h6 class="text-uppercase text-muted mb-2">Note</h6>
                        <p class="mb-0">{{ $expense->note ?? 'No note added.' }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex flex-wrap gap-2">
                <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-warning">Edit transaction</a>
                <form action="{{ route('expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('Delete this transaction?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete transaction</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
