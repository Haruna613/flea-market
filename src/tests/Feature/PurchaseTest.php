<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Order;

class PurchaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    protected $condition;

    protected function setUp(): void
    {
        parent::setUp();
        $this->condition = Condition::create(['name' => '良好']);
    }

    public function test_user_can_complete_purchase()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['condition_id' => $this->condition->id]);

        $this->actingAs($user);

        $response = $this->post(route('purchase.create-checkout-session', ['item' => $item->id]), [
            'payment_method' => 'card',
        ]);

        $response->assertStatus(302);
    }

    public function test_purchased_item_displays_sold_label_on_index()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['condition_id' => $this->condition->id]);

        Order::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'price'   => $item->price,
            'status'  => Order::STATUS_PAID,
            'shipping_postal_code' => '123-4567',
            'shipping_address_line1' => '東京都渋谷区...',
        ]);

        $response = $this->get(route('top'));

        $response->assertSee($item->name);

        $response->assertSee('SOLD');
    }

    public function test_purchased_item_appears_in_user_profile()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['condition_id' => $this->condition->id]);

        $this->actingAs($user);

        Order::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'price'   => $item->price,
            'status'  => Order::STATUS_PAID,
            'shipping_postal_code' => '123-4567',
            'shipping_address_line1' => '東京都渋谷区...',
        ]);

        $response = $this->get(route('profile', ['page' => 'buy']));

        $response->assertStatus(200);
        $response->assertSee($item->name);
    }
}
