<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use App\Models\Condition;

class CommentTest extends TestCase
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

    public function test_authenticated_user_can_send_comment()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $item = Item::factory()->create(['condition_id' => $this->condition->id]);

        $commentData = ['comment_body' => 'これはテストコメントです。'];

        $response = $this->post(route('item.comment.store', ['item' => $item->id]), $commentData);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment_body' => 'これはテストコメントです。'
        ]);

        $response->assertRedirect('/');
    }

    public function test_guest_user_cannot_send_comment()
    {
        $item = Item::factory()->create(['condition_id' => $this->condition->id]);
        $commentData = ['comment_body' => 'ログインしてないコメント'];

        $response = $this->post(route('item.comment.store', ['item' => $item->id]), $commentData);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('comments', [
            'comment_body' => 'ログインしてないコメント'
        ]);
    }

    public function test_comment_is_required()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $item = Item::factory()->create(['condition_id' => $this->condition->id]);

        $response = $this->post(route('item.comment.store', ['item' => $item->id]), [
            'comment_body' => ''
        ]);

        $response->assertSessionHasErrors([
            'comment_body' => 'コメントは必ず入力してください'
        ]);
    }

    public function test_comment_max_length()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $item = Item::factory()->create(['condition_id' => $this->condition->id]);

        $response = $this->post(route('item.comment.store', ['item' => $item->id]), [
            'comment_body' => str_repeat('あ', 256)
        ]);

        $response->assertSessionHasErrors([
            'comment_body' => 'コメントは255文字以内で入力してください'
        ]);
    }
}
