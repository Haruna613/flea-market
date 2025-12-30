<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\Like;

class MyListTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_only_liked_items_are_displayed()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $likedItem = Item::factory()->create(['name' => 'いいねした商品']);
        $notLikedItem = Item::factory()->create(['name' => 'いいねしてない商品']);

        Like::create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('いいねした商品');
        $response->assertDontSee('いいねしてない商品');
    }

    public function test_sold_items_in_mylist_display_sold_label()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $soldItem = Item::factory()->create(['name' => '売れた商品']);

        Like::create([
            'user_id' => $user->id,
            'item_id' => $soldItem->id,
        ]);

        Order::create([
            'user_id' => User::factory()->create()->id,
            'item_id' => $soldItem->id,
            'price' => $soldItem->price,
            'status' => 'paid',
            'shipping_postal_code' => '123-4567',
            'shipping_address_line1' => '東京都...',
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertSee('売れた商品');
        $response->assertSee('SOLD');
    }

    public function test_nothing_displayed_when_not_authenticated()
    {
        $response = $this->get('/?tab=mylist');

        $response->assertDontSee('いいねした商品');
    }
}
