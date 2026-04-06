<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $query = Expense::query();

        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        $currentMonth = now()->startOfMonth();
        $monthlyExpenses = $query->where('type', 'expense')->whereBetween('date', [$currentMonth, $currentMonth->clone()->endOfMonth()])->sum('amount');
        $monthlyIncome = $query->where('type', 'income')->whereBetween('date', [$currentMonth, $currentMonth->clone()->endOfMonth()])->sum('amount');
        $netBalance = $monthlyIncome - $monthlyExpenses;

        $chartData = $query->selectRaw('MONTH(date) as month, type, SUM(amount) as total')
            ->whereYear('date', now()->year)
            ->groupBy('month', 'type')
            ->orderBy('month')
            ->get()
            ->groupBy('month');

        $months = [];
        $expenseData = [];
        $incomeData = [];

        for ($i = 1; $i <= 12; $i++) {
            $months[] = now()->month($i)->format('M');
            $expenseData[] = $chartData[$i]->where('type', 'expense')->sum('total') ?? 0;
            $incomeData[] = $chartData[$i]->where('type', 'income')->sum('total') ?? 0;
        }

        return view('dashboard', compact('monthlyExpenses', 'monthlyIncome', 'netBalance', 'months', 'expenseData', 'incomeData'));
    }
}
