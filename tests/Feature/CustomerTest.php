<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function staff_can_view_all_customers()
    {
        $this->seed(UserTypeSeeder::class);
        Sanctum::actingAs(
            User::factory()->staff()->create()
        );

        $response = $this->getJson('/api/customer');

        $response->assertOk();
    }

    /**
     * @test
     */
    public function non_staff_cannot_view_customers()
    {
        $this->seed(UserTypeSeeder::class);
        Sanctum::actingAs(
            User::factory()->customer()->create()
        );

        $response = $this->getJson('/api/customer');

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function staff_can_delete_customer()
    {
        $this->seed(UserTypeSeeder::class);
        Sanctum::actingAs(
            User::factory()->staff()->create()
        );
        $customer = User::factory()->customer()->create();

        $response = $this->deleteJson('api/customer/'.$customer->id);

        $deletedCustomer = User::withTrashed()->where('id', $customer->id)->first();

        $this->assertNotNull($deletedCustomer->deleted_at);
        $response->assertNoContent();
    }
}
