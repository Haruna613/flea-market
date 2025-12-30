<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Order;
use App\Models\Condition;

class ItemTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->condition = Condition::create(['name' => '良好']);
    }

    public function test_can_get_all_items()
    {
        Item::factory()->count(2)->create();

        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertViewHas('items', function ($items) {
            return $items->count() === 2;
        });
    }

    public function test_sold_items_display_sold_label()
    {
        $item = Item::factory()->create();

        Order::create([
            'user_id' => User::factory()->create()->id,
            'item_id' => $item->id,
            'price' => $item->price,
            'status' => Order::STATUS_PAID,
            'shipping_postal_code' => '123-4567',
            'shipping_address_line1' => '東京都渋谷区...',
            'shipping_building_name' => 'テストビル101',
        ]);

        $response = $this->get('/');

        $response->assertSee('SOLD');
    }

    public function test_own_items_are_not_displayed_in_list()
    {
        $me = User::factory()->create();
        $this->actingAs($me);

        Item::factory()->create([
            'user_id' => $me->id,
            'name' => '私の商品',
            'condition_id' => $this->condition->id
        ]);
        Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'name' => '他人の商品',
            'condition_id' => $this->condition->id
        ]);

        $response = $this->get('/');

        $response->assertSee('他人の商品');
        $response->assertDontSee('私の商品');
    }

    public function test_can_search_items_by_partial_name()
    {
        Item::factory()->create(['name' => 'コーヒー豆']);
        Item::factory()->create(['name' => 'アイスコーヒー']);
        Item::factory()->create(['name' => '紅茶の葉']);

        $response = $this->get('/?keyword=コーヒー');

        $response->assertStatus(200);
        $response->assertSee('コーヒー豆');
        $response->assertSee('アイスコーヒー');
        $response->assertDontSee('紅茶の葉');
    }

    public function test_search_keyword_is_persisted_when_switching_to_mylist()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $searchUrl = '/?keyword=時計';
        $response = $this->get($searchUrl);
        $response->assertSee('時計');

        $mylistUrl = '/?tab=mylist&keyword=時計';
        $response = $this->get($mylistUrl);

        $response->assertStatus(200);

        $response->assertSeeInOrder(['class="header__item-search--input"', 'value="時計"'], false);
    }
}
