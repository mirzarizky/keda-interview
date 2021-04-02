<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CustomerFeedbackTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     */
    public function customer_can_report_other_customer()
    {
        $this->withoutExceptionHandling();
        $this->seed(UserTypeSeeder::class);
        Sanctum::actingAs(
            User::factory()->customer()->create()
        );
        $otherCustomer = User::factory()->customer()->create();

        $response = $this->postJson('/api/report', [
            'reported_customer_id' => $otherCustomer->id,
            'feedback' => $this->faker->sentence,
            'is_bug' => $this->faker->boolean
        ]);

        $response->assertCreated();
    }

    /**
     * @test
     */
    public function customer_can_report_their_own_feedback()
    {
        $this->seed(UserTypeSeeder::class);
        Sanctum::actingAs(
            User::factory()->customer()->create()
        );

        $response = $this->postJson('/api/report', [
            'feedback' => $this->faker->sentence,
            'is_bug' => $this->faker->boolean
        ]);

        $response->assertCreated();
    }

    /**
     * @test
     */
    public function customer_cannot_report_customer_who_doesnt_exists()
    {
        $this->seed(UserTypeSeeder::class);
        Sanctum::actingAs(
            User::factory()->customer()->create()
        );
        $customerDoesntExistsId = 999;

        $response = $this->postJson('/api/report', [
            'reported_customer_id' => $customerDoesntExistsId,
            'feedback' => $this->faker->sentence,
            'is_bug' => $this->faker->boolean
        ]);

        $response->assertNotFound();
    }
}
