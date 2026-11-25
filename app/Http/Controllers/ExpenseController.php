<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Store a newly recorded expense.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:1'],
            'receipt_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'expense_date' => ['required', 'date', 'before_or_equal:today'],
        ]);

        $validated['receipt_image'] = $request->file('receipt_image')?->store('receipts', 'public');

        $request->user()->recordedExpenses()->create($validated);

        return back()->with('success', 'Pengeluaran berhasil dicatat.');
    }
}
