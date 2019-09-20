<?php


namespace Tests\Commands;


use App\Models\Order;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Str;
use Tests\CreatesApplication;

class OrderHandlerCommandTest extends TestCase
{

    use CreatesApplication, DatabaseMigrations;

    public function test_order_handler()
    {
        $this->artisan('order:success', ['order_id' => Str::random()])
            ->expectsOutput('订单不存在');
    }

    public function test_order_handler_with_order()
    {
        $user = factory(User::class)->create();

        $order = Order::create([
            'user_id' => $user->id,
            'charge' => 100,
            'status' => Order::STATUS_UNPAY,
            'order_id' => Str::random(),
            'payment' => '123',
            'payment_method' => '123',
        ]);

        $this->artisan('order:success', ['order_id' => $order->order_id])
            ->expectsOutput('处理成功');

        $order->refresh();
        $this->assertEquals(Order::STATUS_PAID, $order->status);
    }

    public function test_order_handler_with_paid_order()
    {
        $order = Order::create([
            'user_id' => 1,
            'charge' => 100,
            'status' => Order::STATUS_PAID,
            'order_id' => Str::random(),
            'payment' => '123',
            'payment_method' => '123',
        ]);

        $this->artisan('order:success', ['order_id' => $order->order_id])
            ->expectsOutput('该订单已支付');
    }

}