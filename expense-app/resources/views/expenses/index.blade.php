@extends('layouts.app')

@section('title', 'Transaction History')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col-md-8">
        <h3 class="mb-1">Transaction History</h3>
        <p class="text-secondary mb-0">Search, filter and review your income and expense history in one place.</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="{{ route('expenses.create') }}" class="btn btn-primary">Add transaction</a>
    </div>
</div>

<div class="card card-hero p-4 mb-4">
    <form method="GET" action="{{ route('expenses.index') }}" class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Search</label>
            <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Title or note">
        </div>
        <div class="col-md-2">
            <label class="form-label">Type</label>
            <select name="type" class="form-select">
                <option value="">All types</option>
                @foreach($types as $key => $label)
                    <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Category</label>
            <select name="category" class="form-select">
                <option value="all">All categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">From</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
        </div>
        <div class="col-md-2">
            <label class="form-label">To</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
        </div>
        <div class="col-md-1 d-grid align-items-end">
            <button class="btn btn-outline-primary mt-4">Go</button>
        </div>
    </form>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card shadow-soft border-0 p-4">
            <small class="text-muted">Total income</small>
            <div class="h4 mt-2 text-success">LKR {{ number_format($totalIncome, 2) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-soft border-0 p-4">
            <small class="text-muted">Total expense</small>
            <div class="h4 mt-2 text-danger">LKR {{ number_format($totalExpenses, 2) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-soft border-0 p-4">
            <small class="text-muted">Net balance</small>
            <div class="h4 mt-2 {{ $netBalance >= 0 ? 'text-success' : 'text-danger' }}">LKR {{ number_format($netBalance, 2) }}</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card card-hero p-4">
            <h5 class="mb-4">Monthly flow</h5>
            <canvas id="transactionTrend" height="120"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card card-hero p-4">
            <h5 class="mb-4">Expense by category</h5>
            <canvas id="categoryBreakdown" height="260"></canvas>
        </div>
    </div>
</div>

<div class="card card-hero p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Note</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $expense)
                    <tr>
                        <td>{{ $expense->id }}</td>
                        <td>{{ $expense->title }}</td>
                        <td><span class="badge bg-{{ $expense->type === 'income' ? 'success' : 'danger' }}">{{ ucfirst($expense->type) }}</span></td>
                        <td>{{ $expense->category }}</td>
                        <td>LKR {{ number_format($expense->amount, 2) }}</td>
                        <td>{{ $expense->date->format('Y-m-d') }}</td>
                        <td>{{ $expense->note }}</td>
                        <td>
                            <a href="{{ route('expenses.show', $expense) }}" class="btn btn-sm btn-outline-secondary">View</a>
                            <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this transaction?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No transactions found. Add your first expense or income.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const transactionTrend = document.getElementById('transactionTrend');
    const categoryBreakdown = document.getElementById('categoryBreakdown');

    new Chart(transactionTrend, {
        type: 'line',
        data: {
            labels: @json($chartMonths),
            datasets: [
                {
                    label: 'Income',
                    data: @json($incomeTrend),
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.15)',
                    fill: true,
                    tension: 0.35,
                },
                {
                    label: 'Expense',
                    data: @json($expenseTrend),
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.15)',
                    fill: true,
                    tension: 0.35,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: { y: { ticks: { callback: value => 'LKR ' + value } } }
        }
    });

    new Chart(categoryBreakdown, {
        type: 'doughnut',
        data: {
            labels: @json($categoryData->pluck('category')),
            datasets: [{
                data: @json($categoryData->pluck('total')),
                backgroundColor: ['#0d6efd', '#198754', '#dc3545', '#fd7e14', '#6f42c1', '#20c997'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
@endpush
