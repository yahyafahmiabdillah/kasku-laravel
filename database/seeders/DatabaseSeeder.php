<?php

namespace Database\Seeders;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\Role;
use App\Models\Expense;
use App\Models\KasPeriod;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $bendahara = User::create([
            'name' => 'Bendahara Kelas',
            'nis' => null,
            'email' => 'bendahara@sekolah.sch.id',
            'password' => 'password',
            'role' => Role::Bendahara,
        ]);

        $studentData = [
            '2425001' => ['name' => 'Aditya Pratama', 'email' => 'aditya@sekolah.sch.id'],
            '2425002' => ['name' => 'Bunga Lestari', 'email' => 'bunga@sekolah.sch.id'],
            '2425003' => ['name' => 'Citra Dewi', 'email' => 'citra@sekolah.sch.id'],
            '2425004' => ['name' => 'Dimas Saputra', 'email' => 'dimas@sekolah.sch.id'],
            '2425005' => ['name' => 'Eka Putri', 'email' => 'eka@sekolah.sch.id'],
        ];

        $students = [];

        foreach ($studentData as $nis => $data) {
            $students[$nis] = User::create([
                'name' => $data['name'],
                'nis' => $nis,
                'email' => $data['email'],
                'password' => 'password',
                'role' => Role::Siswa,
            ]);
        }

        $kasBulanan = KasPeriod::create([
            'title' => 'Kas Kelas November 2026',
            'description' => 'Iuran kas kelas untuk kebutuhan operasional kelas bulan November 2026.',
            'amount' => 20000,
            'due_date' => '2026-11-30',
            'created_by' => $bendahara->id,
        ]);

        $danaStudyTour = KasPeriod::create([
            'title' => 'Dana Study Tour',
            'description' => 'Iuran bersama untuk kegiatan study tour akhir semester.',
            'amount' => 150000,
            'due_date' => '2026-12-20',
            'created_by' => $bendahara->id,
        ]);

        Payment::create([
            'kas_period_id' => $kasBulanan->id,
            'user_id' => $students['2425001']->id,
            'payment_method' => PaymentMethod::Tunai,
            'status' => PaymentStatus::Approved,
            'verified_at' => '2026-11-10 09:00:00',
        ]);

        Payment::create([
            'kas_period_id' => $kasBulanan->id,
            'user_id' => $students['2425002']->id,
            'payment_method' => PaymentMethod::Transfer,
            'proof_image' => 'payments/bunga-kas-november.jpg',
            'status' => PaymentStatus::Approved,
            'verified_at' => '2026-11-11 10:30:00',
        ]);

        Payment::create([
            'kas_period_id' => $kasBulanan->id,
            'user_id' => $students['2425003']->id,
            'payment_method' => PaymentMethod::Transfer,
            'proof_image' => 'payments/citra-kas-november.jpg',
            'status' => PaymentStatus::Approved,
            'verified_at' => '2026-11-12 08:15:00',
        ]);

        Payment::create([
            'kas_period_id' => $kasBulanan->id,
            'user_id' => $students['2425004']->id,
            'payment_method' => PaymentMethod::Tunai,
            'status' => PaymentStatus::Approved,
            'verified_at' => '2026-11-13 14:00:00',
        ]);

        Payment::create([
            'kas_period_id' => $kasBulanan->id,
            'user_id' => $students['2425005']->id,
            'payment_method' => PaymentMethod::Transfer,
            'proof_image' => 'payments/eka-kas-november.jpg',
            'status' => PaymentStatus::Pending,
        ]);

        Payment::create([
            'kas_period_id' => $danaStudyTour->id,
            'user_id' => $students['2425001']->id,
            'payment_method' => PaymentMethod::Transfer,
            'proof_image' => 'payments/aditya-study-tour.jpg',
            'status' => PaymentStatus::Approved,
            'verified_at' => '2026-11-20 11:45:00',
        ]);

        Payment::create([
            'kas_period_id' => $danaStudyTour->id,
            'user_id' => $students['2425002']->id,
            'payment_method' => PaymentMethod::Transfer,
            'proof_image' => 'payments/bunga-study-tour.jpg',
            'status' => PaymentStatus::Pending,
        ]);

        Payment::create([
            'kas_period_id' => $danaStudyTour->id,
            'user_id' => $students['2425003']->id,
            'payment_method' => PaymentMethod::Tunai,
            'status' => PaymentStatus::Pending,
        ]);

        Expense::create([
            'title' => 'Pembelian alat tulis kelas',
            'category' => 'Alat Tulis',
            'amount' => 45000,
            'receipt_image' => 'expenses/alat-tulis.jpg',
            'expense_date' => '2026-11-10',
            'recorded_by' => $bendahara->id,
        ]);

        Expense::create([
            'title' => 'Sewa proyektor untuk presentasi',
            'category' => 'Peralatan',
            'amount' => 100000,
            'receipt_image' => 'expenses/sewa-proyektor.jpg',
            'expense_date' => '2026-11-18',
            'recorded_by' => $bendahara->id,
        ]);
    }
}
