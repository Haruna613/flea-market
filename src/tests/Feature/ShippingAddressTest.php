<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Order;

class ShippingAddressTest extends TestCase
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

    public function test_updated_address_is_reflected_on_purchase_page()
    {
        $user = User::factory()->create([
            'postal_code' => '111-1111',
            'address' => '元の住所',
        ]);
        $this->actingAs($user);

        $item = Item::factory()->create(['condition_id' => $this->condition->id]);

        $newAddress = [
            'postal_code'   => '999-9999',
            'address'       => '変更後の住所123',
            'building_name' => '変更後のビル名',
        ];

        $response = $this->post(route('purchase.address.update', ['item_id' => $item->id]), $newAddress);

        $response->assertRedirect(route('item.purchase.show', ['item_id' => $item->id]));

        $response = $this->get(route('item.purchase.show', ['item_id' => $item->id]));

        $response->assertStatus(200);
        $response->assertSee('999-9999');
        $response->assertSee('変更後の住所123');
        $response->assertSee('変更後のビル名');
    }

    public function test_purchased_item_is_linked_to_correct_shipping_address()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $item = Item::factory()->create(['condition_id' => $this->condition->id]);

        $shippingData = [
            'payment_method' => 'card',
            'postal_code'    => '888-8888',
            'address'        => '最終送付先住所',
            'building_name'  => '最終ビル',
        ];

        $response = $this->postJson(route('purchase.create-checkout-session', ['item' => $item->id]), $shippingData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'shipping_postal_code' => '888-8888',
            'shipping_address_line1' => '最終送付先住所',
            'shipping_building_name' => '最終ビル',
        ]);
    }
}
