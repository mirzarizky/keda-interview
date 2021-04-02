<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SendMessageTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     */
    public function customer_or_staff_can_send_message_to_other_customer()
    {
        $this->seed(UserTypeSeeder::class);
        Sanctum::actingAs(
            User::factory()->create()
        );
        $otherCustomer = User::factory()->customer()->create();

        $response = $this->postJson('/api/message', [
            'message' => $this->faker->sentence,
            'receiver_id' => $otherCustomer->id
        ]);

        $response->assertCreated();
    }

    /**
     * @test
     */
    public function customer_cannot_send_message_to_staff()
    {
        $this->seed(UserTypeSeeder::class);
        Sanctum::actingAs(
            User::factory()->customer()->create()
        );
        $staff = User::factory()->staff()->create();

        $response = $this->postJson('/api/message', [
            'message' => $this->faker->sentence,
            'receiver_id' => $staff->id
        ]);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function staff_can_send_message_to_other_staff()
    {
        $this->seed(UserTypeSeeder::class);
        Sanctum::actingAs(
            User::factory()->staff()->create()
        );
        $otherStaff = User::factory()->staff()->create();

        $response = $this->postJson('/api/message', [
            'message' => $this->faker->sentence,
            'receiver_id' => $otherStaff->id
        ]);

        $response->assertCreated();
    }
}
