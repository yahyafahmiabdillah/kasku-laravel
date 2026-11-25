<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Models\Expense;
use App\Models\Payment;
use App\Services\KasService;
use Illuminate\View\View;

class PublicDashboardController extends Controller
{
    /**
     * Show the public cash transparency dashboard.
     */
    public function __invoke(KasService $kasService): View
    {
        $payments = Payment::query()
            ->with(['kasPeriod:id,title,amount', 'user:id,name'])
            ->where('status', PaymentStatus::Approved->value)
            ->latest('verified_at')
            ->latest('id')
            ->limit(15)
            ->get()
            ->map(fn (Payment $payment) => [
                'date' => $payment->verified_at ?? $payment->created_at,
                'title' => $payment->kasPeriod->title,
                'description' => $payment->user->name,
                'type' => 'income',
                'amount' => (float) $payment->kasPeriod->amount,
            ]);

        $expenses = Expense::query()
            ->with('recorder:id,name')
            ->latest('expense_date')
            ->latest('id')
            ->limit(15)
            ->get()
            ->map(fn (Expense $expense) => [
                'date' => $expense->expense_date,
                'title' => $expense->title,
                'description' => $expense->recorder->name,
                'type' => 'expense',
                'amount' => (float) $expense->amount,
            ]);

        $transactions = $payments
            ->concat($expenses)
            ->sortByDesc(fn (array $transaction) => $transaction['date']->getTimestamp())
            ->take(15)
            ->values();

        return view('frontend.public.index', [
            'summary' => $kasService->summary(),
            'transactions' => $transactions,
        ]);
    }
}
