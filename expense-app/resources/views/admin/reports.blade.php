@extends('layouts.app')

@section('title', 'Monthly Reports')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-8">
        <div class="card card-hero p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-1">Monthly report generator</h3>
                    <p class="text-secondary mb-0">Generate and email financial reports to your admin inbox.</p>
                </div>
            </div>

            <form method="GET" action="{{ route('reports.index') }}" class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Select month</label>
                    <input type="month" name="month" value="{{ $month }}" class="form-control">
                </div>
                <div class="col-md-6 d-grid align-self-end">
                    <button type="submit" class="btn btn-outline-primary">Refresh report</button>
                </div>
            </form>

            <div class="row g-3">
                <div class="col-sm-4">
                    <div class="card shadow-soft border-0 p-3">
                        <small class="text-muted">Total Income</small>
                        <div class="h4 mt-2 text-success">LKR {{ number_format($reportData['total_income'], 2) }}</div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card shadow-soft border-0 p-3">
                        <small class="text-muted">Total Expense</small>
                        <div class="h4 mt-2 text-danger">LKR {{ number_format($reportData['total_expenses'], 2) }}</div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card shadow-soft border-0 p-3">
                        <small class="text-muted">Net Balance</small>
                        <div class="h4 mt-2 {{ $reportData['net_balance'] >= 0 ? 'text-success' : 'text-danger' }}">LKR {{ number_format($reportData['net_balance'], 2) }}</div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <form method="POST" action="{{ route('reports.send') }}">
                    @csrf
                    <input type="hidden" name="month" value="{{ $month }}">
                    <button type="submit" class="btn btn-primary">Send report by email</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
