<?php

namespace Tests\Feature;

use App\Http\Controllers\AdController;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\User;
use App\Models\Ad;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticatedRoutesTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->withRole('user')->create();
    }

    public function test_show_profile()
    {
        $response = $this->actingAs($this->user)
            ->get('/profile');

        $response->assertStatus(200);
    }

    public function test_update_profile()
    {
        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ];

        $response = $this->actingAs($this->user)
            ->post('/profile', $updateData);

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Updated Name'
        ]);
    }
    public function test_show_my_ads()
    {
        $response = $this->actingAs($this->user)
            ->get('/my_ads');

        $response->assertStatus(200);
    }

    public function test_add_to_favourites()
    {
        $ad = Ad::factory()->create();

        $response = $this->actingAs($this->user)
            ->post("/favourites/{$ad->id}");

        $response->assertStatus(200);
        $this->assertDatabaseHas('favourites', [
            'user_id' => $this->user->id,
            'ad_id' => $ad->id
        ]);
    }
}
