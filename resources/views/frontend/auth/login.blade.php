@extends('frontend.layouts.app')

@section('title', 'Masuk')

@section('content')
    <div class="mx-auto grid max-w-5xl gap-6 lg:grid-cols-[1.05fr_1fr] lg:items-stretch">
        <section class="relative hidden overflow-hidden rounded-3xl bg-slate-900 p-8 text-white shadow-xl shadow-slate-900/10 lg:block">
            <div class="pointer-events-none absolute -right-20 -top-24 h-72 w-72 rounded-full bg-emerald-500/20 blur-3xl" aria-hidden="true"></div>
            <div class="pointer-events-none absolute -bottom-28 -left-10 h-64 w-64 rounded-full bg-teal-400/10 blur-3xl" aria-hidden="true"></div>

            <div class="relative flex h-full flex-col">
                <span class="inline-flex w-fit items-center gap-2 rounded-full bg-emerald-400/10 px-3 py-1 text-xs font-bold uppercase tracking-[0.14em] text-emerald-300 ring-1 ring-inset ring-emerald-400/20">
                    KasKu
                </span>
                <h1 class="mt-6 text-3xl font-extrabold leading-tight tracking-tight">
                    Satu tempat untuk kas kelas yang rapi dan terbuka.
                </h1>
                <p class="mt-4 text-sm leading-relaxed text-slate-300">
                    Bendahara mencatat, siswa mengunggah bukti, dan seluruh kelas bisa melihat saldo secara transparan.
                </p>

                <div class="mt-auto space-y-3 pt-10 text-sm">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Akun demo</p>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-xs font-semibold text-emerald-300">Bendahara</p>
                        <p class="mt-1 font-mono text-sm text-white">bendahara@sekolah.sch.id</p>
                        <p class="font-mono text-xs text-slate-400">password: password</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-xs font-semibold text-teal-300">Siswa</p>
                        <p class="mt-1 font-mono text-sm text-white">aditya@sekolah.sch.id</p>
                        <p class="font-mono text-xs text-slate-400">password: password</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="mx-auto max-w-md">
                <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-200">
                    Selamat datang kembali
                </span>
                <h2 class="mt-4 text-2xl font-extrabold tracking-tight text-slate-900">Masuk ke KasKu</h2>
                <p class="mt-1 text-sm text-slate-500">Gunakan email sekolah dan password akunmu.</p>

                <form method="POST" action="{{ route('login.store') }}" class="mt-6 space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                               placeholder="nama@sekolah.sch.id"
                               class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10">
                        @error('email')
                            <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                        <input id="password" name="password" type="password" required autocomplete="current-password"
                               placeholder="••••••••"
                               class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10">
                        @error('password')
                            <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <input type="checkbox" name="remember" value="1"
                               class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        Ingat saya
                    </label>

                    <button type="submit"
                            class="w-full rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-500/20 transition hover:from-emerald-600 hover:to-teal-700 focus:outline-none focus:ring-4 focus:ring-emerald-500/20">
                        Masuk
                    </button>
                </form>

                <p class="mt-6 text-center text-xs text-slate-500">
                    Belum punya akun? Hubungi bendahara kelas untuk didaftarkan.
                </p>
            </div>
        </section>
    </div>
@endsection
