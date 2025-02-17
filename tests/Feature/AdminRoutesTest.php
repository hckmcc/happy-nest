<?php

namespace Tests\Feature;

use App\Models\Role;
use Tests\TestCase;
use App\Models\User;
use App\Models\Ad;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminRoutesTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_show_users_list()
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/users');

        $response->assertStatus(200);
    }

    public function test_delete_user()
    {
        $user = User::factory()->withRole('user')->create();

        $response = $this->actingAs($this->admin)
            ->delete("/admin/users/{$user->id}/delete");

        $response->assertStatus(302)
            ->assertRedirect();
        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }

    public function test_update_user_roles()
    {
        $adminRole = Role::where('slug', 'admin')->first() ?? Role::factory()->admin()->create();
        $user = User::factory()->withRole('user')->create();

        $response = $this->actingAs($this->admin)
            ->put("/admin/users/{$user->id}/roles", [
                'roles' => [$adminRole->id]
            ]);

        $response->assertStatus(302);

        $this->assertTrue($user->fresh()->roles->contains($adminRole->id));
    }

    public function test_create_category()
    {
        $categoryData = [
            'name' => 'Test Category',
            'description' => 'Test Description',
            'parent_category_id' => null
        ];

        $response = $this->actingAs($this->admin)
            ->post('/admin/categories/add', $categoryData);

        $response->assertStatus(302);
        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category'
        ]);
    }
}
