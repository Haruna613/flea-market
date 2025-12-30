<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ItemStoreTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    protected $user;
    protected $condition;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        $this->category = new Category();
        $this->category->name = 'ファッション';
        $this->category->save();

        $this->condition = new Condition();
        $this->condition->name = '良好';
        $this->condition->save();
    }

    public function test_user_can_store_item_info()
    {
        Storage::fake('public');
        $this->actingAs($this->user);

        $itemData = [
            'item_image'       => UploadedFile::fake()->create('item.jpg', 100),
            'item_category'    => [$this->category->id],
            'item_condition'   => $this->condition->id,
            'item_name'        => 'テスト商品名',
            'item_brand'       => 'テストブランド',
            'item_description' => 'テストの説明文です',
            'item_price'       => 5000,
        ];

        $response = $this->post(route('item.store'), $itemData);

        $response->assertSessionHasNoErrors();

        $response->assertRedirect(route('top'));

        $this->assertDatabaseHas('items', [
            'user_id' => $this->user->id,
            'name'    => 'テスト商品名',
            'price'   => 5000,
        ]);
    }
}
