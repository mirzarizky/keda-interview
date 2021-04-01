<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function authenticated_user_can_logout()
    {
        $this->seed(UserTypeSeeder::class);
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/auth/logout');

        $response->assertNoContent();
    }

    /**
     * @test
     */
    public function guest_cannot_logout()
    {
        $response = $this->postJson('/api/auth/logout');

        $response->assertUnauthorized();
    }
}
