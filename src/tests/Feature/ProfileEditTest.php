<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ProfileEditTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_user_profile_edit_page_shows_initial_values()
    {
        $user = User::factory()->create([
            'name' => '設定前ユーザー名',
            'profile_image_path' => 'profile_images/initial_image.png',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区道玄坂',
            'building_name' => 'テックビル',
        ]);

        $this->actingAs($user);
        $response = $this->get('/mypage/profile');

        $response->assertStatus(200);

        $response->assertSee('value="設定前ユーザー名"', false);

        $response->assertSee('value="123-4567"', false);

        $response->assertSee('value="東京都渋谷区道玄坂"', false);

        $response->assertSee('value="テックビル"', false);

        $response->assertSee('initial_image.png');
    }
}
