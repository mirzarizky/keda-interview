<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\User;
use Database\Seeders\UserTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function user_can_retrieve_their_own_messages()
    {
        $this->seed(UserTypeSeeder::class);
        Sanctum::actingAs($user = User::factory()->create());
        $messages = Message::factory()
            ->count(20)
            ->create(['sender_id' => $user->id]);

        $response = $this->getJson('/api/messages');

        $response->assertOk();
        $response->assertJsonCount($messages->count());
    }

    /**
     * @test
     */
    public function staff_can_retrieve_all_messages()
    {
        $this->seed(UserTypeSeeder::class);
        Sanctum::actingAs($user = User::factory()
            ->staff()
            ->create());

        $messages = Message::factory()
            ->count(20)
            ->create();

        $response = $this->getJson('/api/all-messages');

        $response->assertOk();
        $response->assertJsonCount($messages->count());
    }

    /**
     * @test
     */
    public function non_staff_cannot_retrieve_all_messages()
    {
        $this->seed(UserTypeSeeder::class);
        Sanctum::actingAs($user = User::factory()
            ->customer()
            ->create());

        Message::factory()
            ->count(20)
            ->create();

        $response = $this->getJson('/api/all-messages');

        $response->assertForbidden();
    }

}
