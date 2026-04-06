<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    private function categories(): array
    {
        return ['Food', 'Travel', 'Bills', 'Shopping', 'Salary', 'Other'];
    }

    private function types(): array
    {
        return ['expense' => 'Expense', 'income' => 'Income'];
    }

    private function baseQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = Expense::query();

        if (!Auth::user()?->isAdmin()) {
            $query->where('user_id', Auth::id());
        }

        return $query;
    }

    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $query = $this->baseQuery();

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($sub) use ($keyword) {
                $sub->where('title', 'like', "%{$keyword}%")
                    ->orWhere('note', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('type') && in_array($request->type, array_keys($this->types()), true)) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $expenses = $query->latest()->get();
        $totalExpenses = $expenses->where('type', 'expense')->sum('amount');
        $totalIncome = $expenses->where('type', 'income')->sum('amount');
        $netBalance = $totalIncome - $totalExpenses;

        $chartMonths = [];
        $expenseTrend = [];
        $incomeTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $chartMonths[] = $month->format('M');
            $expenseTrend[] = $this->baseQuery()->where('type', 'expense')
                ->whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->sum('amount');
            $incomeTrend[] = $this->baseQuery()->where('type', 'income')
                ->whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->sum('amount');
        }

        $categoryData = $this->baseQuery()->selectRaw('category, SUM(amount) as total')
            ->where('type', 'expense')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        return view('expenses.index', [
            'expenses' => $expenses,
            'categories' => $this->categories(),
            'types' => $this->types(),
            'totalExpenses' => $totalExpenses,
            'totalIncome' => $totalIncome,
            'netBalance' => $netBalance,
            'chartMonths' => $chartMonths,
            'expenseTrend' => $expenseTrend,
            'incomeTrend' => $incomeTrend,
            'categoryData' => $categoryData,
        ]);
    }

    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return view('expenses.create', [
            'categories' => $this->categories(),
            'types' => $this->types(),
        ]);
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'category' => 'required|in:Food,Travel,Bills,Shopping,Salary,Other',
            'type' => 'required|in:expense,income',
            'note' => 'nullable|string|max:1000',
        ]);

        Expense::create([
            'title' => $request->title,
            'category' => $request->category,
            'type' => $request->type,
            'amount' => $request->amount,
            'date' => $request->date,
            'note' => $request->note,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('expenses.index')->with('success', 'Transaction saved successfully.');
    }

    public function show($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $expense = Expense::findOrFail($id);
        if (!Auth::user()->isAdmin() && $expense->user_id !== Auth::id()) {
            abort(403);
        }

        return view('expenses.show', ['expense' => $expense]);
    }

    public function edit($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $expense = Expense::findOrFail($id);
        if (!Auth::user()->isAdmin() && $expense->user_id !== Auth::id()) {
            abort(403);
        }

        return view('expenses.edit', [
            'expense' => $expense,
            'categories' => $this->categories(),
            'types' => $this->types(),
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $expense = Expense::findOrFail($id);
        if (!Auth::user()->isAdmin() && $expense->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'category' => 'required|in:Food,Travel,Bills,Shopping,Salary,Other',
            'type' => 'required|in:expense,income',
            'note' => 'nullable|string|max:1000',
        ]);

        $expense->update([
            'title' => $request->title,
            'category' => $request->category,
            'type' => $request->type,
            'amount' => $request->amount,
            'date' => $request->date,
            'note' => $request->note,
        ]);

        return redirect()->route('expenses.index')->with('success', 'Transaction updated successfully.');
    }

    public function destroy($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $expense = Expense::findOrFail($id);
        if (!Auth::user()->isAdmin() && $expense->user_id !== Auth::id()) {
            abort(403);
        }

        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Transaction removed successfully.');
    }
}
