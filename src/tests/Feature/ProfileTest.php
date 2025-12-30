<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\Condition;

class ProfileTest extends TestCase
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

    public function test_user_can_view_profile_page_with_correct_info()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image_path' => 'profile_images/test_image.png',
        ]);
        $otherUser = User::factory()->create();

        Item::factory()->create([
            'user_id' => $user->id,
            'name' => '私が出品した商品',
            'condition_id' => $this->condition->id
        ]);

        $myPurchasedItem = Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => '私が購入した商品',
            'condition_id' => $this->condition->id
        ]);
        Order::create([
            'user_id' => $user->id,
            'item_id' => $myPurchasedItem->id,
            'price' => $myPurchasedItem->price,
            'status' => 'paid',
            'shipping_postal_code' => '123-4567',
            'shipping_address_line1' => '東京都...',
        ]);

        $this->actingAs($user);

        $response = $this->get('/mypage');
        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee('test_image.png');
        $response->assertSee('私が出品した商品');

        $response = $this->get('/mypage?page=buy');
        $response->assertStatus(200);
        $response->assertSee('私が購入した商品');
    }
}
