<?php

namespace App\Http\Controllers;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\KasPeriod;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    /**
     * Store or resubmit a student's payment proof.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kas_period_id' => ['required', 'integer', 'exists:kas_periods,id'],
            'payment_method' => ['required', Rule::enum(PaymentMethod::class)],
            'proof_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
                Rule::requiredIf(fn () => $request->input('payment_method') === PaymentMethod::Transfer->value),
            ],
        ]);

        $user = $request->user();
        $kasPeriod = KasPeriod::query()->findOrFail($validated['kas_period_id']);

        $existingPayment = Payment::query()
            ->whereBelongsTo($user)
            ->whereBelongsTo($kasPeriod)
            ->first();

        if ($existingPayment !== null && $existingPayment->status !== PaymentStatus::Rejected) {
            return back()->with('error', 'Kamu sudah memiliki setoran aktif untuk tagihan ini.');
        }

        $proofPath = $request->file('proof_image')?->store('proofs', 'public');

        $attributes = [
            'kas_period_id' => $kasPeriod->id,
            'payment_method' => $validated['payment_method'],
            'proof_image' => $proofPath ?? $existingPayment?->proof_image,
            'status' => PaymentStatus::Pending,
            'verified_at' => null,
        ];

        if ($existingPayment !== null) {
            $existingPayment->update($attributes);
        } else {
            $user->payments()->create($attributes);
        }

        return back()->with('success', 'Bukti pembayaran terkirim dan menunggu verifikasi bendahara.');
    }

    /**
     * Approve a pending payment.
     */
    public function approve(Payment $payment): RedirectResponse
    {
        if ($payment->status !== PaymentStatus::Pending) {
            return back()->with('error', 'Hanya pembayaran berstatus pending yang dapat diverifikasi.');
        }

        $payment->update([
            'status' => PaymentStatus::Approved,
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Pembayaran berhasil disetujui.');
    }

    /**
     * Reject a pending payment.
     */
    public function reject(Payment $payment): RedirectResponse
    {
        if ($payment->status !== PaymentStatus::Pending) {
            return back()->with('error', 'Hanya pembayaran berstatus pending yang dapat diverifikasi.');
        }

        $payment->update([
            'status' => PaymentStatus::Rejected,
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Pembayaran ditolak. Siswa dapat mengunggah bukti baru.');
    }
}
