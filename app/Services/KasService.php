<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Models\Expense;
use App\Models\Payment;

class KasService
{
    /**
     * Total income from verified payments only.
     */
    public function totalIncome(): float
    {
        return (float) Payment::query()
            ->where('status', PaymentStatus::Approved->value)
            ->join('kas_periods', 'kas_periods.id', '=', 'payments.kas_period_id')
            ->sum('kas_periods.amount');
    }

    /**
     * Total of all recorded expenses.
     */
    public function totalExpense(): float
    {
        return (float) Expense::query()->sum('amount');
    }

    /**
     * Remaining cash balance after deducting expenses from verified income.
     */
    public function balance(): float
    {
        return $this->totalIncome() - $this->totalExpense();
    }

    /**
     * Financial summary used by dashboards.
     *
     * @return array{income: float, expense: float, balance: float}
     */
    public function summary(): array
    {
        $income = $this->totalIncome();
        $expense = $this->totalExpense();

        return [
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense,
        ];
    }
}
