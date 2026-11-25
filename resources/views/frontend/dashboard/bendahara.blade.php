@extends('frontend.layouts.app')

@section('title', 'Dashboard Bendahara')

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-emerald-600">Panel Bendahara</p>
            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Halo, {{ auth()->user()->name }}</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola tagihan, verifikasi setoran siswa, dan catat pengeluaran kelas.</p>
        </div>
        <a href="{{ route('public.dashboard') }}"
           class="inline-flex w-fit items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-emerald-200 hover:text-emerald-700">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            Lihat halaman publik
        </a>
    </div>

    <section class="mt-6 grid gap-4 sm:grid-cols-3" aria-label="Ringkasan keuangan">
        <div class="rounded-3xl bg-gradient-to-br from-emerald-500 to-teal-600 p-5 text-white shadow-lg shadow-emerald-500/20">
            <p class="text-sm font-semibold text-emerald-50">Saldo Kas</p>
            <p class="mt-2 text-2xl font-extrabold tracking-tight">Rp {{ number_format($summary['balance'], 0, ',', '.') }}</p>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-semibold text-slate-500">Pemasukan</p>
            <p class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900">Rp {{ number_format($summary['income'], 0, ',', '.') }}</p>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-semibold text-slate-500">Pengeluaran</p>
            <p class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900">Rp {{ number_format($summary['expense'], 0, ',', '.') }}</p>
        </div>
    </section>

    <div class="mt-6 grid gap-6 xl:grid-cols-2">
        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Buat Tagihan Kas</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Tambahkan iuran mingguan atau dana event baru.</p>
                </div>
                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-2xl bg-emerald-50 text-emerald-600">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </span>
            </div>

            <form action="{{ route('kas-periods.store') }}" method="POST" class="mt-5 space-y-4">
                @csrf

                <div>
                    <label for="title" class="block text-sm font-semibold text-slate-700">Judul tagihan</label>
                    <input id="title" name="title" type="text" value="{{ old('title') }}" required maxlength="255"
                           placeholder="Contoh: Kas Kelas Desember 2026"
                           class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10">
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="amount" class="block text-sm font-semibold text-slate-700">Nominal (Rp)</label>
                        <input id="amount" name="amount" type="number" value="{{ old('amount') }}" required min="1000" step="500"
                               placeholder="20000"
                               class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10">
                    </div>
                    <div>
                        <label for="due_date" class="block text-sm font-semibold text-slate-700">Jatuh tempo</label>
                        <input id="due_date" name="due_date" type="date" value="{{ old('due_date') }}" required
                               class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10">
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-slate-700">Deskripsi <span class="font-normal text-slate-400">(opsional)</span></label>
                    <textarea id="description" name="description" rows="3" maxlength="2000"
                              placeholder="Keterangan singkat penggunaan dana"
                              class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10">{{ old('description') }}</textarea>
                </div>

                <button type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-500/20">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Simpan tagihan
                </button>
            </form>
        </section>

        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Menunggu Verifikasi</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Periksa bukti setoran sebelum menyetujui.</p>
                </div>
                <span class="inline-flex items-center rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700 ring-1 ring-inset ring-amber-200">
                    {{ $pendingPayments->count() }} pending
                </span>
            </div>

            <div class="mt-5 space-y-4">
                @forelse ($pendingPayments as $payment)
                    <article class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <p class="truncate font-bold text-slate-900">{{ $payment->user->name }}</p>
                                <p class="mt-0.5 text-xs text-slate-500">NIS {{ $payment->user->nis }} · {{ $payment->kasPeriod->title }}</p>
                            </div>
                            <p class="shrink-0 font-extrabold text-emerald-600">Rp {{ number_format($payment->kasPeriod->amount, 0, ',', '.') }}</p>
                        </div>

                        <div class="mt-3 flex flex-wrap items-center gap-2 text-xs">
                            <span class="rounded-full bg-white px-2.5 py-1 font-semibold capitalize text-slate-600 ring-1 ring-inset ring-slate-200">
                                {{ $payment->payment_method->value }}
                            </span>
                            @if ($payment->proof_image)
                                <a href="{{ asset('storage/'.$payment->proof_image) }}" target="_blank" rel="noopener"
                                   class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-200 transition hover:bg-emerald-100">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                    </svg>
                                    Lihat bukti
                                </a>
                            @else
                                <span class="rounded-full bg-white px-2.5 py-1 font-semibold text-slate-500 ring-1 ring-inset ring-slate-200">Tanpa bukti</span>
                            @endif
                            <span class="text-slate-400">{{ $payment->created_at->diffForHumans() }}</span>
                        </div>

                        <div class="mt-4 flex gap-2">
                            <form action="{{ route('payments.approve', $payment) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="w-full rounded-xl bg-emerald-600 px-3 py-2 text-xs font-bold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-500/20">
                                    Setujui
                                </button>
                            </form>
                            <form action="{{ route('payments.reject', $payment) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="w-full rounded-xl border border-rose-200 bg-white px-3 py-2 text-xs font-bold text-rose-600 transition hover:bg-rose-50 focus:outline-none focus:ring-4 focus:ring-rose-500/10">
                                    Tolak
                                </button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 px-6 py-10 text-center">
                        <span class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-emerald-50 text-emerald-600">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        </span>
                        <p class="mt-3 font-semibold text-slate-700">Semua setoran sudah diperiksa</p>
                        <p class="mt-1 text-sm text-slate-500">Tidak ada pembayaran yang menunggu verifikasi.</p>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Catat Pengeluaran</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Simpan nota agar laporan kas tetap akurat.</p>
                </div>
                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-2xl bg-rose-50 text-rose-600">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5v-15m0 0 5.25 5.25M12 4.5 6.75 9.75" />
                    </svg>
                </span>
            </div>

            <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data" class="mt-5 space-y-4">
                @csrf

                <div>
                    <label for="expense_title" class="block text-sm font-semibold text-slate-700">Nama pengeluaran</label>
                    <input id="expense_title" name="title" type="text" value="{{ old('title') }}" required maxlength="255"
                           placeholder="Contoh: Beli spidol dan penghapus"
                           class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10">
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="category" class="block text-sm font-semibold text-slate-700">Kategori</label>
                        <input id="category" name="category" type="text" value="{{ old('category') }}" required maxlength="100"
                               placeholder="Alat Tulis"
                               class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10">
                    </div>
                    <div>
                        <label for="expense_amount" class="block text-sm font-semibold text-slate-700">Nominal (Rp)</label>
                        <input id="expense_amount" name="amount" type="number" value="{{ old('amount') }}" required min="1" step="500"
                               placeholder="45000"
                               class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10">
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="expense_date" class="block text-sm font-semibold text-slate-700">Tanggal</label>
                        <input id="expense_date" name="expense_date" type="date" value="{{ old('expense_date', now()->toDateString()) }}" required
                               class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10">
                    </div>
                    <div>
                        <label for="receipt_image" class="block text-sm font-semibold text-slate-700">Foto nota <span class="font-normal text-slate-400">(opsional)</span></label>
                        <input id="receipt_image" name="receipt_image" type="file" accept="image/*"
                               class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-500 shadow-sm file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-emerald-700 hover:file:bg-emerald-100">
                    </div>
                </div>

                <button type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-slate-700 focus:outline-none focus:ring-4 focus:ring-slate-500/20">
                    Simpan pengeluaran
                </button>
            </form>
        </section>

        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Progres Tagihan</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Jumlah siswa yang setorannya sudah disetujui.</p>
                </div>
                <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                    {{ $studentCount }} siswa
                </span>
            </div>

            <div class="mt-5 space-y-3">
                @forelse ($kasPeriods as $period)
                    @php
                        $progress = $studentCount > 0
                            ? min(100, (int) round(($period->approved_payments_count / $studentCount) * 100))
                            : 0;
                    @endphp
                    <div class="rounded-2xl border border-slate-200 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="truncate font-bold text-slate-900">{{ $period->title }}</p>
                                <p class="mt-0.5 text-xs text-slate-500">Jatuh tempo {{ $period->due_date->format('d M Y') }}</p>
                            </div>
                            <p class="shrink-0 text-sm font-extrabold text-slate-900">Rp {{ number_format($period->amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="mt-3 flex items-center gap-3">
                            <div class="h-2 flex-1 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-500" style="width: {{ $progress }}%"></div>
                            </div>
                            <span class="w-12 text-right text-xs font-bold text-slate-500">{{ $period->approved_payments_count }}/{{ $studentCount }}</span>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 px-6 py-10 text-center">
                        <p class="font-semibold text-slate-700">Belum ada tagihan</p>
                        <p class="mt-1 text-sm text-slate-500">Buat tagihan pertama melalui formulir di atas.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    <section class="mt-6 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-2 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Pengeluaran Terbaru</h2>
                <p class="mt-0.5 text-sm text-slate-500">10 pengeluaran terakhir beserta notanya.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-[0.12em] text-slate-500">
                    <tr>
                        <th scope="col" class="whitespace-nowrap px-5 py-3 sm:px-6">Tanggal</th>
                        <th scope="col" class="px-5 py-3 sm:px-6">Pengeluaran</th>
                        <th scope="col" class="px-5 py-3 sm:px-6">Kategori</th>
                        <th scope="col" class="px-5 py-3 sm:px-6">Nota</th>
                        <th scope="col" class="whitespace-nowrap px-5 py-3 text-right sm:px-6">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($expenses as $expense)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="whitespace-nowrap px-5 py-4 text-slate-500 sm:px-6">{{ $expense->expense_date->format('d M Y') }}</td>
                            <td class="px-5 py-4 sm:px-6">
                                <p class="font-semibold text-slate-900">{{ $expense->title }}</p>
                                <p class="mt-0.5 text-xs text-slate-500">Dicatat oleh {{ $expense->recorder->name }}</p>
                            </td>
                            <td class="px-5 py-4 sm:px-6">
                                <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">{{ $expense->category }}</span>
                            </td>
                            <td class="px-5 py-4 sm:px-6">
                                @if ($expense->receipt_image)
                                    <a href="{{ asset('storage/'.$expense->receipt_image) }}" target="_blank" rel="noopener"
                                       class="font-semibold text-emerald-600 underline-offset-2 hover:underline">Lihat nota</a>
                                @else
                                    <span class="text-xs text-slate-400">Tidak ada</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-right font-extrabold text-rose-600 sm:px-6">
                                − Rp {{ number_format($expense->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-500">Belum ada pengeluaran tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
