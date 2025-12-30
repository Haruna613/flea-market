<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Category;

class ItemDetailTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_item_detail_page_displays_all_required_info()
    {
        $condition = Condition::create(['name' => '新品・未使用']);
        $item = Item::factory()->create([
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'price' => 5000,
            'description' => '商品の詳細説明文',
            'condition_id' => $condition->id,
        ]);

        $cat = Category::factory()->create(['name' => 'ファッション']);
        $item->categories()->attach($cat->id);

        $response = $this->get(route('item.detail', ['item_id' => $item->id]));

        $response->assertSee('テスト商品');
        $response->assertSee('テストブランド');
        $response->assertSee('5,000');
        $response->assertSee('商品の詳細説明文');
        $response->assertSee('新品・未使用');
        $response->assertSee('ファッション');

        $response->assertDontSee('SOLD OUT');
        $response->assertSee('購入手続きへ');
    }

    public function test_multiple_categories_are_displayed()
    {
        $condition = Condition::create(['name' => '良好']);
        $item = Item::factory()->create();
        $category1 = Category::factory()->create(['name' => 'ファッション']);
        $category2 = Category::factory()->create(['name' => 'メンズ']);

        $item->categories()->attach([$category1->id, $category2->id]);

        $response = $this->get(route('item.detail', ['item_id' => $item->id]));

        $response->assertSee('ファッション');
        $response->assertSee('メンズ');
    }
}
