<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class KasPeriodController extends Controller
{
    /**
     * Store a newly created kas period.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'amount' => ['required', 'numeric', 'min:1000'],
            'due_date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $request->user()->createdKasPeriods()->create($validated);

        return back()->with('success', 'Tagihan kas berhasil dibuat.');
    }
}
