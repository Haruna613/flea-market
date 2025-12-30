<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Condition;

class LikeTest extends TestCase
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

    public function test_user_can_like_an_item_via_ajax()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $item = Item::factory()->create();

        $response = $this->postJson(route('item.like.toggle', ['item' => $item->id]));

        $response->assertStatus(200)
                ->assertJson(['status' => 'liked']);

        $this->assertDatabaseHas('likes', ['user_id' => $user->id, 'item_id' => $item->id]);
    }

    public function test_like_icon_displays_pink_heart_when_liked()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $item = Item::factory()->create();
        $item->likes()->create(['user_id' => $user->id]);

        $response = $this->get(route('item.detail', ['item_id' => $item->id]));

        $response->assertSee('src="http://localhost/images/ハートロゴ_ピンク.png"', false);

        $response->assertDontSee('src="http://localhost/images/ハートロゴ_デフォルト.png"', false);
    }

    public function test_user_can_unlike_an_item()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create();
        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->postJson(route('item.like.toggle', ['item' => $item->id]));
        $response->assertJson(['status' => 'unliked']);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get(route('item.detail', ['item_id' => $item->id]));
        $response->assertSee('0');
    }
}
