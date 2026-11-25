@extends('frontend.layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-emerald-600">Panel Siswa</p>
            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Halo, {{ auth()->user()->name }}</h1>
            <p class="mt-1 text-sm text-slate-500">NIS {{ auth()->user()->nis }} · Pantau tagihan dan unggah bukti setoranmu.</p>
        </div>
        <a href="{{ route('public.dashboard') }}"
           class="inline-flex w-fit items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-emerald-200 hover:text-emerald-700">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            Lihat transparansi kelas
        </a>
    </div>

    <section class="mt-6 grid gap-4 sm:grid-cols-3" aria-label="Ringkasan kas">
        <div class="rounded-3xl bg-gradient-to-br from-emerald-500 to-teal-600 p-5 text-white shadow-lg shadow-emerald-500/20">
            <p class="text-sm font-semibold text-emerald-50">Saldo Kas Kelas</p>
            <p class="mt-2 text-2xl font-extrabold tracking-tight">Rp {{ number_format($summary['balance'], 0, ',', '.') }}</p>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-semibold text-slate-500">Tagihan Belum Lunas</p>
            <p class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900">{{ $unpaidCount }} <span class="text-sm font-semibold text-slate-400">tagihan</span></p>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-semibold text-slate-500">Total Setoran Disetujui</p>
            <p class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900">Rp {{ number_format($approvedTotal, 0, ',', '.') }}</p>
        </div>
    </section>

    <section class="mt-8">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Tagihan Kas</h2>
                <p class="mt-0.5 text-sm text-slate-500">Pilih tagihan, lalu unggah bukti pembayaranmu.</p>
            </div>
            <span class="text-xs font-semibold text-slate-400">{{ $kasPeriods->count() }} tagihan tersedia</span>
        </div>

        <div class="mt-4 grid gap-4 lg:grid-cols-2">
            @forelse ($kasPeriods as $period)
                @php
                    $payment = $period->payments->first();
                    $paymentStatus = $payment?->status;
                @endphp

                <article class="flex flex-col rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <h3 class="font-bold text-slate-900">{{ $period->title }}</h3>
                            <p class="mt-1 text-xs text-slate-500">Jatuh tempo {{ $period->due_date->format('d M Y') }}</p>
                        </div>

                        @if ($paymentStatus === \App\Enums\PaymentStatus::Approved)
                            <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Lunas
                            </span>
                        @elseif ($paymentStatus === \App\Enums\PaymentStatus::Pending)
                            <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700 ring-1 ring-inset ring-amber-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> Menunggu
                            </span>
                        @elseif ($paymentStatus === \App\Enums\PaymentStatus::Rejected)
                            <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-rose-50 px-3 py-1 text-xs font-bold text-rose-700 ring-1 ring-inset ring-rose-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Ditolak
                            </span>
                        @else
                            <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600 ring-1 ring-inset ring-slate-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Belum bayar
                            </span>
                        @endif
                    </div>

                    @if ($period->description)
                        <p class="mt-3 text-sm leading-relaxed text-slate-500">{{ $period->description }}</p>
                    @endif

                    <div class="mt-4 flex items-end justify-between gap-3 rounded-2xl bg-slate-50 px-4 py-3">
                        <span class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-400">Nominal</span>
                        <span class="text-xl font-extrabold tracking-tight text-slate-900">Rp {{ number_format($period->amount, 0, ',', '.') }}</span>
                    </div>

                    @if ($paymentStatus === \App\Enums\PaymentStatus::Pending)
                        <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                            <p class="font-semibold">Bukti sedang diperiksa bendahara.</p>
                            @if ($payment->proof_image)
                                <a href="{{ asset('storage/'.$payment->proof_image) }}" target="_blank" rel="noopener"
                                   class="mt-1 inline-block font-semibold text-amber-900 underline underline-offset-2">Lihat bukti yang diunggah</a>
                            @endif
                        </div>
                    @elseif ($paymentStatus === \App\Enums\PaymentStatus::Approved)
                        <div class="mt-4 flex items-start gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                            <svg class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                            <p class="font-semibold">
                                Setoran disetujui{{ $payment->verified_at ? ' pada '.$payment->verified_at->format('d M Y') : '' }}.
                            </p>
                        </div>
                    @else
                        @if ($paymentStatus === \App\Enums\PaymentStatus::Rejected)
                            <div class="mt-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                                <p class="font-semibold">Setoran sebelumnya ditolak. Silakan unggah bukti baru.</p>
                                @if ($payment->proof_image)
                                    <a href="{{ asset('storage/'.$payment->proof_image) }}" target="_blank" rel="noopener"
                                       class="mt-1 inline-block font-semibold text-rose-900 underline underline-offset-2">Lihat bukti sebelumnya</a>
                                @endif
                            </div>
                        @endif

                        <form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-3">
                            @csrf
                            <input type="hidden" name="kas_period_id" value="{{ $period->id }}">

                            <div class="grid gap-3 sm:grid-cols-2">
                                <div>
                                    <label for="payment_method_{{ $period->id }}" class="block text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Metode</label>
                                    <select id="payment_method_{{ $period->id }}" name="payment_method" required
                                            class="mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-semibold text-slate-700 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10">
                                        <option value="tunai">Tunai</option>
                                        <option value="transfer">Transfer</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="proof_image_{{ $period->id }}" class="block text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Bukti bayar</label>
                                    <input id="proof_image_{{ $period->id }}" name="proof_image" type="file" accept="image/*"
                                           class="mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs text-slate-500 shadow-sm file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-emerald-700 hover:file:bg-emerald-100">
                                </div>
                            </div>

                            <p class="text-xs text-slate-400">Bukti wajib diunggah untuk metode transfer. Format JPG, PNG, atau WEBP, maksimal 2 MB.</p>

                            <button type="submit"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-slate-700 focus:outline-none focus:ring-4 focus:ring-slate-500/20">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                </svg>
                                Kirim bukti bayar
                            </button>
                        </form>
                    @endif
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center lg:col-span-2">
                    <span class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-slate-100 text-slate-400">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 4H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h6l4 4v10a2 2 0 0 1-2 2Z" />
                        </svg>
                    </span>
                    <p class="mt-3 font-semibold text-slate-700">Belum ada tagihan</p>
                    <p class="mt-1 text-sm text-slate-500">Tagihan dari bendahara akan muncul di sini.</p>
                </div>
            @endforelse
        </div>
    </section>

    <section class="mt-8 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-5 sm:px-6">
            <h2 class="text-lg font-bold text-slate-900">Riwayat Setoran Saya</h2>
            <p class="mt-0.5 text-sm text-slate-500">Semua bukti pembayaran yang pernah kamu kirim.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-[0.12em] text-slate-500">
                    <tr>
                        <th scope="col" class="px-5 py-3 sm:px-6">Tagihan</th>
                        <th scope="col" class="whitespace-nowrap px-5 py-3 sm:px-6">Dikirim</th>
                        <th scope="col" class="px-5 py-3 sm:px-6">Metode</th>
                        <th scope="col" class="px-5 py-3 sm:px-6">Status</th>
                        <th scope="col" class="whitespace-nowrap px-5 py-3 text-right sm:px-6">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($payments as $payment)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-5 py-4 sm:px-6">
                                <p class="font-semibold text-slate-900">{{ $payment->kasPeriod->title }}</p>
                                @if ($payment->proof_image)
                                    <a href="{{ asset('storage/'.$payment->proof_image) }}" target="_blank" rel="noopener"
                                       class="mt-0.5 inline-block text-xs font-semibold text-emerald-600 underline-offset-2 hover:underline">Lihat bukti</a>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-slate-500 sm:px-6">{{ $payment->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-4 capitalize text-slate-600 sm:px-6">{{ $payment->payment_method->value }}</td>
                            <td class="px-5 py-4 sm:px-6">
                                @if ($payment->status === \App\Enums\PaymentStatus::Approved)
                                    <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-200">Disetujui</span>
                                @elseif ($payment->status === \App\Enums\PaymentStatus::Pending)
                                    <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700 ring-1 ring-inset ring-amber-200">Pending</span>
                                @else
                                    <span class="inline-flex rounded-full bg-rose-50 px-2.5 py-1 text-xs font-bold text-rose-700 ring-1 ring-inset ring-rose-200">Ditolak</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-right font-extrabold text-slate-900 sm:px-6">
                                Rp {{ number_format($payment->kasPeriod->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-500">Kamu belum pernah mengirim bukti setoran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
