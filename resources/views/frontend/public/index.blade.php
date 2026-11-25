@extends('frontend.layouts.app')

@section('title', 'Transparansi Kas')

@section('content')
    <section class="relative overflow-hidden rounded-3xl bg-slate-900 px-6 py-10 text-white shadow-xl shadow-slate-900/10 sm:px-10 sm:py-12">
        <div class="pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full bg-emerald-500/20 blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -bottom-24 left-1/3 h-64 w-64 rounded-full bg-teal-400/10 blur-3xl" aria-hidden="true"></div>

        <div class="relative max-w-2xl">
            <span class="inline-flex items-center gap-2 rounded-full bg-emerald-400/10 px-3 py-1 text-xs font-bold uppercase tracking-[0.14em] text-emerald-300 ring-1 ring-inset ring-emerald-400/20">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                Terbuka untuk semua
            </span>
            <h1 class="mt-4 text-3xl font-extrabold leading-tight tracking-tight sm:text-4xl lg:text-5xl">
                Uang kas kelas, <span class="text-emerald-400">transparan</span> untuk semua.
            </h1>
            <p class="mt-4 max-w-xl text-sm leading-relaxed text-slate-300 sm:text-base">
                Pantau pemasukan, pengeluaran, dan sisa saldo kas kelas secara real-time. Tanpa perlu login, tanpa tanda tanya.
            </p>
        </div>
    </section>

    <section class="mt-6 grid gap-4 sm:grid-cols-3" aria-label="Ringkasan keuangan kas">
        <div class="rounded-3xl bg-gradient-to-br from-emerald-500 to-teal-600 p-6 text-white shadow-lg shadow-emerald-500/20 sm:col-span-1">
            <div class="flex items-center justify-between gap-3">
                <p class="text-sm font-semibold text-emerald-50">Saldo Kas Saat Ini</p>
                <span class="grid h-10 w-10 place-items-center rounded-2xl bg-white/15">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5v9m2.25-6.75c0-.83-.9-1.5-2.25-1.5s-2.25.67-2.25 1.5.9 1.5 2.25 1.5 2.25.67 2.25 1.5-.9 1.5-2.25 1.5-2.25-.67-2.25-1.5" />
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-extrabold tracking-tight">Rp {{ number_format($summary['balance'], 0, ',', '.') }}</p>
            <p class="mt-2 text-xs font-medium text-emerald-100">Pemasukan terverifikasi dikurangi pengeluaran</p>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <p class="text-sm font-semibold text-slate-500">Total Pemasukan</p>
                <span class="grid h-10 w-10 place-items-center rounded-2xl bg-emerald-50 text-emerald-600">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m0 0 5.25-5.25M12 19.5l-5.25-5.25" />
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Rp {{ number_format($summary['income'], 0, ',', '.') }}</p>
            <p class="mt-2 text-xs font-medium text-slate-400">Hanya dari setoran yang disetujui</p>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <p class="text-sm font-semibold text-slate-500">Total Pengeluaran</p>
                <span class="grid h-10 w-10 place-items-center rounded-2xl bg-rose-50 text-rose-600">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5v-15m0 0 5.25 5.25M12 4.5 6.75 9.75" />
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Rp {{ number_format($summary['expense'], 0, ',', '.') }}</p>
            <p class="mt-2 text-xs font-medium text-slate-400">Seluruh pengeluaran yang tercatat</p>
        </div>
    </section>

    <section class="mt-8 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Riwayat Transaksi</h2>
                <p class="mt-0.5 text-sm text-slate-500">Pemasukan terverifikasi dan pengeluaran terbaru kelas.</p>
            </div>
            <span class="inline-flex w-fit items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-200">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                Data publik
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-[0.12em] text-slate-500">
                    <tr>
                        <th scope="col" class="whitespace-nowrap px-5 py-3 sm:px-6">Tanggal</th>
                        <th scope="col" class="px-5 py-3 sm:px-6">Keterangan</th>
                        <th scope="col" class="px-5 py-3 sm:px-6">Jenis</th>
                        <th scope="col" class="whitespace-nowrap px-5 py-3 text-right sm:px-6">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($transactions as $transaction)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="whitespace-nowrap px-5 py-4 text-slate-500 sm:px-6">
                                {{ $transaction['date']->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4 sm:px-6">
                                <p class="font-semibold text-slate-900">{{ $transaction['title'] }}</p>
                                <p class="mt-0.5 text-xs text-slate-500">{{ $transaction['description'] }}</p>
                            </td>
                            <td class="px-5 py-4 sm:px-6">
                                @if ($transaction['type'] === 'income')
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-200">Masuk</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-1 text-xs font-bold text-rose-700 ring-1 ring-inset ring-rose-200">Keluar</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-right font-extrabold sm:px-6 {{ $transaction['type'] === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $transaction['type'] === 'income' ? '+' : '−' }} Rp {{ number_format($transaction['amount'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-14 text-center">
                                <span class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-slate-100 text-slate-400">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 4H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h6l4 4v10a2 2 0 0 1-2 2Z" />
                                    </svg>
                                </span>
                                <p class="mt-3 font-semibold text-slate-700">Belum ada transaksi</p>
                                <p class="mt-1 text-sm text-slate-500">Transaksi akan muncul setelah bendahara mencatat pemasukan atau pengeluaran.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
