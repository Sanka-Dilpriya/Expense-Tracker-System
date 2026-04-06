<?php

namespace App\Http\Controllers;

use App\Mail\MonthlyReportMail;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $month = $request->input('month', now()->format('Y-m'));
        $period = Carbon::createFromFormat('Y-m', $month)->startOfMonth();

        $expenses = Expense::whereMonth('date', $period->month)
            ->whereYear('date', $period->year)
            ->where('type', 'expense')
            ->sum('amount');

        $income = Expense::whereMonth('date', $period->month)
            ->whereYear('date', $period->year)
            ->where('type', 'income')
            ->sum('amount');

        $reportData = [
            'month' => $period->format('F Y'),
            'total_expenses' => $expenses,
            'total_income' => $income,
            'net_balance' => $income - $expenses,
        ];

        return view('admin.reports', compact('reportData', 'month'));
    }

    public function send(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $month = $request->input('month', now()->format('Y-m'));
        $period = Carbon::createFromFormat('Y-m', $month)->startOfMonth();

        $expenses = Expense::whereMonth('date', $period->month)
            ->whereYear('date', $period->year)
            ->where('type', 'expense')
            ->sum('amount');

        $income = Expense::whereMonth('date', $period->month)
            ->whereYear('date', $period->year)
            ->where('type', 'income')
            ->sum('amount');

        $reportData = [
            'month' => $period->format('F Y'),
            'total_expenses' => $expenses,
            'total_income' => $income,
            'net_balance' => $income - $expenses,
        ];

        $recipient = config('mail.from.address', auth()->user()->email);

        Mail::to($recipient)->send(new MonthlyReportMail($reportData));

        return back()->with('success', "Monthly report for {$reportData['month']} has been sent to {$recipient}.");
    }
}
