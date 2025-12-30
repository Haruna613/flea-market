<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;

class PaymentMethodTest extends TestCase
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

    public function test_payment_method_change_is_reflected_on_purchase_page()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['condition_id' => $this->condition->id]);

        $this->actingAs($user);

        $paymentMethod = 'コンビニ払い';

        $response = $this->get(route('item.purchase.show', [
            'item_id' => $item->id,
            'payment_method' => $paymentMethod
        ]));

        $response->assertStatus(200);

        $response->assertSee($paymentMethod);
    }
}
