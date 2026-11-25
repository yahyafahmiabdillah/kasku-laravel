<?php

namespace Tests\Feature;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Expense;
use App\Models\KasPeriod;
use App\Models\Payment;
use App\Models\User;
use App\Services\KasService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KasFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_dashboard_renders_balance_and_transactions(): void
    {
        $bendahara = User::factory()->bendahara()->create();
        $student = User::factory()->create();
        $kasPeriod = KasPeriod::factory()->for($bendahara, 'creator')->create(['amount' => 50000]);

        Payment::factory()->for($kasPeriod)->for($student)->approved()->create();
        Expense::factory()->for($bendahara, 'recorder')->create(['amount' => 20000]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Saldo Kas Saat Ini')
            ->assertSee('Rp 30.000');
    }

    public function test_student_can_submit_payment_and_balance_does_not_increase_while_pending(): void
    {
        Storage::fake('public');

        $student = User::factory()->create();
        $kasPeriod = KasPeriod::factory()->create(['amount' => 25000]);

        $this->actingAs($student)
            ->post(route('payments.store'), [
                'kas_period_id' => $kasPeriod->id,
                'payment_method' => PaymentMethod::Transfer->value,
                'proof_image' => $this->fakeImage('bukti-transfer.png'),
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $payment = Payment::query()->sole();

        $this->assertSame($kasPeriod->id, $payment->kas_period_id);
        $this->assertSame($student->id, $payment->user_id);
        $this->assertSame(PaymentStatus::Pending, $payment->status);
        $this->assertNull($payment->verified_at);
        Storage::disk('public')->assertExists($payment->proof_image);
        $this->assertSame(0.0, app(KasService::class)->balance());
    }

    public function test_treasurer_can_approve_payment_and_balance_increases(): void
    {
        $bendahara = User::factory()->bendahara()->create();
        $student = User::factory()->create();
        $kasPeriod = KasPeriod::factory()->create(['amount' => 50000]);
        $payment = Payment::factory()->for($kasPeriod)->for($student)->create([
            'payment_method' => PaymentMethod::Transfer,
            'status' => PaymentStatus::Pending,
        ]);

        $this->actingAs($bendahara)
            ->patch(route('payments.approve', $payment))
            ->assertRedirect()
            ->assertSessionHas('success');

        $payment->refresh();

        $this->assertSame(PaymentStatus::Approved, $payment->status);
        $this->assertNotNull($payment->verified_at);
        $this->assertSame(50000.0, app(KasService::class)->balance());
    }

    public function test_treasurer_can_record_expense_and_balance_decreases(): void
    {
        Storage::fake('public');

        $bendahara = User::factory()->bendahara()->create();
        $student = User::factory()->create();
        $kasPeriod = KasPeriod::factory()->create(['amount' => 100000]);

        Payment::factory()->for($kasPeriod)->for($student)->approved()->create();

        $this->actingAs($bendahara)
            ->post(route('expenses.store'), [
                'title' => 'Beli spidol dan penghapus',
                'category' => 'Alat Tulis',
                'amount' => 30000,
                'expense_date' => now()->toDateString(),
                'receipt_image' => $this->fakeImage('nota.png'),
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $expense = Expense::query()->sole();

        $this->assertSame('Beli spidol dan penghapus', $expense->title);
        $this->assertSame(30000.0, (float) $expense->amount);
        Storage::disk('public')->assertExists($expense->receipt_image);
        $this->assertSame(70000.0, app(KasService::class)->balance());
    }

    public function test_student_cannot_submit_duplicate_payment_for_same_period(): void
    {
        Storage::fake('public');

        $student = User::factory()->create();
        $kasPeriod = KasPeriod::factory()->create(['amount' => 25000]);
        Payment::factory()->for($kasPeriod)->for($student)->create([
            'status' => PaymentStatus::Pending,
        ]);

        $this->actingAs($student)
            ->post(route('payments.store'), [
                'kas_period_id' => $kasPeriod->id,
                'payment_method' => PaymentMethod::Tunai->value,
            ])
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseCount('payments', 1);
    }

    public function test_user_can_login_and_logout(): void
    {
        $user = User::factory()->create(['email' => 'siswa@sekolah.sch.id']);

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Masuk ke KasKu');

        $this->post(route('login.store'), [
            'email' => 'siswa@sekolah.sch.id',
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))->assertRedirect(route('public.dashboard'));

        $this->assertGuest();
    }

    public function test_treasurer_dashboard_renders_management_forms(): void
    {
        $bendahara = User::factory()->bendahara()->create();

        $this->actingAs($bendahara)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Buat Tagihan Kas')
            ->assertSee('Catat Pengeluaran');
    }

    public function test_student_dashboard_renders_bill_and_payment_form(): void
    {
        $student = User::factory()->create();
        KasPeriod::factory()->create(['title' => 'Kas Kelas Desember 2026']);

        $this->actingAs($student)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Kas Kelas Desember 2026')
            ->assertSee('Kirim bukti bayar');
    }

    public function test_student_cannot_record_expense(): void
    {
        $student = User::factory()->create();

        $this->actingAs($student)
            ->post(route('expenses.store'), [
                'title' => 'Pengeluaran tidak sah',
                'category' => 'Lainnya',
                'amount' => 10000,
                'expense_date' => now()->toDateString(),
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('expenses', 0);
    }

    /**
     * Build a real image upload without requiring the GD extension.
     */
    private function fakeImage(string $originalName): UploadedFile
    {
        return new UploadedFile(
            base_path('tests/Fixtures/proof.png'),
            $originalName,
            'image/png',
            null,
            true,
        );
    }
}
