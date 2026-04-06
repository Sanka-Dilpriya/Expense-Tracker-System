@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row g-4">
    <div class="col-12 col-xl-8">
        <div class="card card-hero p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-1">Welcome back, {{ auth()->user()->name }}</h3>
                    <p class="text-secondary mb-0">Track your income, expenses, and growth with clear charts.</p>
                </div>
                <a href="{{ route('expenses.create') }}" class="btn btn-primary">Add entry</a>
            </div>

            <div class="row g-3">
                <div class="col-sm-4">
                    <div class="card shadow-soft border-0 p-3">
                        <small class="text-muted">This month income</small>
                        <div class="h4 mt-2 text-success">LKR {{ number_format($monthlyIncome, 2) }}</div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card shadow-soft border-0 p-3">
                        <small class="text-muted">This month expense</small>
                        <div class="h4 mt-2 text-danger">LKR {{ number_format($monthlyExpenses, 2) }}</div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card shadow-soft border-0 p-3">
                        <small class="text-muted">Net balance</small>
                        <div class="h4 mt-2 {{ $netBalance >= 0 ? 'text-success' : 'text-danger' }}">LKR {{ number_format($netBalance, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card card-hero p-4 h-100">
            <h5 class="mb-3">Quick actions</h5>
            <div class="list-group">
                <a href="{{ route('expenses.create') }}" class="list-group-item list-group-item-action">Add new expense or income</a>
                <a href="{{ route('expenses.index') }}" class="list-group-item list-group-item-action">View transaction history</a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('reports.index') }}" class="list-group-item list-group-item-action">Generate monthly report</a>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card card-hero p-4">
            <h5 class="mb-4">Yearly overview</h5>
            <canvas id="yearlyChart" height="120"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const labels = @json($months);
    const expenseData = @json($expenseData);
    const incomeData = @json($incomeData);

    new Chart(document.getElementById('yearlyChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Income',
                    data: incomeData,
                    borderColor: '#198754',
                    backgroundColor: '#d1e7dd',
                    tension: 0.35,
                    fill: true,
                },
                {
                    label: 'Expense',
                    data: expenseData,
                    borderColor: '#dc3545',
                    backgroundColor: '#f8d7da',
                    tension: 0.35,
                    fill: true,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: {
                y: { ticks: { callback: value => 'LKR ' + value } }
            }
        }
    });
</script>
@endpush
