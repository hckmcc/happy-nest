<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Ad;
use App\Models\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PublicRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_route()
    {
        $response = $this->get('/home');
        $response->assertStatus(200);
    }

    public function test_search_route()
    {
        $response = $this->get('/search?query=test');
        $response->assertStatus(200);
    }

    public function test_show_ad_route()
    {
        $ad = Ad::factory()->create();
        $response = $this->get("/ads/{$ad->id}");
        $response->assertStatus(200);
    }

    public function test_show_seller_route()
    {
        $user = User::factory()->create();
        $response = $this->get("/user/{$user->id}");
        $response->assertStatus(200);
    }

    public function test_categories_list_route()
    {
        $response = $this->get('/categories');
        $response->assertStatus(200);
    }

    public function test_show_category_route()
    {
        $category = Category::factory()->create();
        $response = $this->get("/categories/{$category->id}");
        $response->assertStatus(200);
    }
}
