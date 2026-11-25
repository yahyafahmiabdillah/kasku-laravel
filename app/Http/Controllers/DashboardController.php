<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Enums\Role;
use App\Models\Expense;
use App\Models\KasPeriod;
use App\Models\Payment;
use App\Models\User;
use App\Services\KasService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the dashboard for the authenticated user's role.
     */
    public function __invoke(Request $request, KasService $kasService): View
    {
        $user = $request->user();

        if ($user->role === Role::Bendahara) {
            $pendingPayments = Payment::query()
                ->with(['kasPeriod:id,title,amount', 'user:id,name,nis'])
                ->where('status', PaymentStatus::Pending->value)
                ->oldest('created_at')
                ->oldest('id')
                ->get();

            $kasPeriods = KasPeriod::query()
                ->withCount([
                    'payments as approved_payments_count' => fn ($query) => $query->where('status', PaymentStatus::Approved->value),
                ])
                ->latest('due_date')
                ->latest('id')
                ->get();

            $expenses = Expense::query()
                ->with('recorder:id,name')
                ->latest('expense_date')
                ->latest('id')
                ->limit(10)
                ->get();

            return view('frontend.dashboard.bendahara', [
                'summary' => $kasService->summary(),
                'pendingPayments' => $pendingPayments,
                'kasPeriods' => $kasPeriods,
                'expenses' => $expenses,
                'studentCount' => User::query()->where('role', Role::Siswa->value)->count(),
            ]);
        }

        $kasPeriods = KasPeriod::query()
            ->with([
                'payments' => fn ($query) => $query->where('user_id', $user->id),
            ])
            ->orderBy('due_date')
            ->orderBy('id')
            ->get();

        $payments = Payment::query()
            ->with('kasPeriod:id,title,amount')
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->latest('id')
            ->get();

        $approvedTotal = $payments
            ->where('status', PaymentStatus::Approved)
            ->sum(fn (Payment $payment) => (float) $payment->kasPeriod->amount);

        $unpaidCount = $kasPeriods->filter(function (KasPeriod $period): bool {
            $payment = $period->payments->first();

            return $payment === null || $payment->status === PaymentStatus::Rejected;
        })->count();

        return view('frontend.dashboard.siswa', [
            'summary' => $kasService->summary(),
            'kasPeriods' => $kasPeriods,
            'payments' => $payments,
            'approvedTotal' => $approvedTotal,
            'unpaidCount' => $unpaidCount,
        ]);
    }
}
