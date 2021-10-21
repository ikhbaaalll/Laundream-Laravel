<?php

namespace Tests\Feature;

use App\Models\Laundry;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LaundryControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_list_all_laundry()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Laundry::factory()->count(10)->create();

        $response = $this->get('/api/v1/laundries');

        $response->assertOk()
            ->assertJsonCount(10);
    }

    public function test_cannot_see_list_all_laundry()
    {
        Laundry::factory()->create();

        $response = $this->getJson('/api/v1/laundries');

        $response->assertUnauthorized();
    }

    public function test_admin_can_create_laundry()
    {
        $user = User::factory()->create();
        $this->actingAs($user, ['laundry.create']);

        $response = $this->postJson('/api/v1/laundries', [
            'email' => 'laundry@gmail.com',
            'password' => '12345678',
            'laundry' => 'Laundry Test',
            'name' => 'Laundry'
        ]);

        $response->assertCreated()
            ->assertJsonPath('user.email', 'laundry@gmail.com');

        $this->assertDatabaseHas('laundries', [
            'id' => $response->json('id')
        ]);
    }

    public function test_if_not_admin_cannot_create_laundry()
    {
        $response = $this->postJson('/api/v1/laundries', [
            'email' => 'laundry@gmail.com',
            'password' => '12345678',
            'laundry' => 'Laundry Test',
            'name' => 'Laundry'
        ]);

        $response->assertUnauthorized();
    }

    public function test_create_laundry_validation()
    {
        $user = User::factory()->create();
        $this->actingAs($user, ['laundry.create']);

        $response = $this->postJson('/api/v1/laundries', [
            'password' => '12345678',
            'laundry' => 'Laundry Test',
            'name' => 'Laundry'
        ]);

        $response->assertUnprocessable();
    }

    public function test_admin_can_see_laundry()
    {
        $user = User::factory()->create();
        $this->actingAs($user, ['laundry.show']);

        $laundry = Laundry::factory()->create();

        $response = $this->getJson("/api/v1/laundries/{$laundry->id}");

        $response->assertOk()
            ->assertJsonPath('id', $laundry->id);
    }

    public function test_admin_can_update_status_laundry()
    {
        $user = User::factory()->create();
        $this->actingAs($user, ['laundry.update.status']);

        $laundry = Laundry::factory()->create();

        $response = $this->putJson(
            "/api/v1/laundries/{$laundry->id}",
            [
                'status' => 0
            ]
        );

        $response->assertOk()
            ->assertJsonPath('status', Laundry::STATUS_INACTIVE);
    }

    public function test_admin_can_delete_laundry()
    {
        $user = User::factory()->create();
        $this->actingAs($user, ['laundry.delete']);

        $laundry = Laundry::factory()->create();

        $response = $this->deleteJson("/api/v1/laundries/{$laundry->id}");

        $response->assertOk();

        $this->assertModelMissing($laundry);
    }
}
