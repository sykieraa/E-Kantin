<?php

namespace Tests\Feature;

use App\Models\Canteen;
use App\Models\Order;
use App\Models\Product;
use App\Models\Student;
use App\Models\User;
use App\Services\OrderService;
use App\Support\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

/**
 * Contoh pengujian otomatis untuk aturan paling penting di aplikasi ini:
 * stok harus berkurang setelah checkout, dan tidak boleh bisa minus.
 */
class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_stok_berkurang_setelah_checkout(): void
    {
        [$student, $product] = $this->buatDataDemo();

        Cart::add($product, 2);
        $order = app(OrderService::class)->checkout($student);

        $this->assertSame(8, $product->fresh()->stock);
        $this->assertSame(Order::PAYMENT_OUTLET, $order->payment_method);
    }

    public function test_tidak_bisa_memesan_melebihi_stok(): void
    {
        [$student, $product] = $this->buatDataDemo();

        Cart::add($product, 2);
        Cart::update($product->id, 99);

        $this->expectException(ValidationException::class);
        app(OrderService::class)->checkout($student);
    }

    private function buatDataDemo(): array
    {
        $sellerUser = User::create([
            'username' => 'penjualtest', 'name' => 'Penjual Test',
            'password' => 'password12', 'role' => User::ROLE_SELLER,
        ]);

        $canteen = Canteen::create([
            'user_id' => $sellerUser->id, 'name' => 'Kantin Test',
            'code' => 'T', 'status' => Canteen::STATUS_OPEN,
        ]);

        $studentUser = User::create([
            'username' => '9999', 'name' => 'Siswa Test',
            'password' => 'password12', 'role' => User::ROLE_STUDENT,
        ]);

        $student = Student::create([
            'user_id' => $studentUser->id, 'nis' => '9999',
            'name' => 'Siswa Test', 'class_name' => 'XI RPL 1',
        ]);

        $product = Product::create([
            'canteen_id' => $canteen->id, 'name' => 'Nasi Goreng',
            'price' => 15000, 'stock' => 10, 'is_available' => true,
        ]);

        return [$student, $product];
    }
}
