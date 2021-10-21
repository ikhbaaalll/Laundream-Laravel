<?php

namespace Tests\Feature;

use App\Models\Laundry;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ServiceControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_owner_can_see_all_services()
    {
        $owner = User::factory()->create(['role' => User::ROLE_OWNER]);
        $this->actingAs($owner);

        $laundry = Laundry::factory()->for($owner)->create();
        Service::factory()->for($laundry)->count(5)->create();

        $response = $this->getJson("/api/v1/owner/laundries/{$laundry->id}/services");

        $response->assertOk()
            ->assertJsonCount(5);
    }

    public function test_owner_can_see_all_services_where_belong_to_their_laundry()
    {
        $owner = User::factory()->create(['role' => User::ROLE_OWNER]);
        $this->actingAs($owner);

        $laundry = Laundry::factory()->for($owner)->create();
        Service::factory()->for($laundry)->count(5)->create();
        Service::factory()->count(5)->create();

        $response = $this->getJson("/api/v1/owner/laundries/{$laundry->id}/services");

        $response->assertOk()
            ->assertJsonCount(5);
    }

    public function test_owner_cannot_see_services_if_not_belong_to_their_laundry()
    {
        $owner = User::factory()->create(['role' => User::ROLE_OWNER]);
        $this->actingAs($owner);

        $laundry = Laundry::factory()->create();

        $response = $this->getJson("/api/v1/owner/laundries/{$laundry->id}/services");

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['service' => 'Tidak dapat melihat layanan!']);
    }

    public function test_owner_cannot_see_services_if_scope_not_provided()
    {
        $owner = User::factory()->create(['role' => User::ROLE_OWNER]);
        $this->actingAs($owner, []);

        $laundry = Laundry::factory()->for($owner)->create();

        $response = $this->getJson("/api/v1/owner/laundries/{$laundry->id}/services");

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['service' => 'Tidak dapat melihat layanan!']);
    }

    public function test_owner_can_make_service()
    {
        $owner = User::factory()->create(['role' => User::ROLE_OWNER]);
        $this->actingAs($owner);

        $laundry = Laundry::factory()->for($owner)->create();

        $response = $this->postJson("/api/v1/owner/laundries/{$laundry->id}/services", [
            'name' => 'Regular'
        ]);

        $response->assertCreated()
            ->assertJsonPath('name', 'Regular');
    }

    public function test_owner_cannot_make_service_if_not_belong_to_their_laundry()
    {
        $owner = User::factory()->create(['role' => User::ROLE_OWNER]);
        $this->actingAs($owner);

        $laundry = Laundry::factory()->create();

        $response = $this->postJson("/api/v1/owner/laundries/{$laundry->id}/services", [
            'name' => 'Regular'
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['service' => 'Tidak dapat membuat layanan!']);
    }

    public function test_owner_cannot_make_service_if_scope_not_provided()
    {
        $owner = User::factory()->create(['role' => User::ROLE_OWNER]);
        $this->actingAs($owner);

        $laundry = Laundry::factory()->create();

        $response = $this->postJson("/api/v1/owner/laundries/{$laundry->id}/services", [
            'name' => 'Regular'
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['service' => 'Tidak dapat membuat layanan!']);
    }

    public function test_owner_can_update_service()
    {
        $owner = User::factory()->create(['role' => User::ROLE_OWNER]);
        $this->actingAs($owner);

        $laundry = Laundry::factory()->for($owner)->create();
        $service = Service::factory()->for($laundry)->create();

        $response = $this->putJson("/api/v1/owner/laundries/{$laundry->id}/services/{$service->id}", [
            'name' => 'Regular'
        ]);

        $response->assertOk()
            ->assertJsonPath('name', 'Regular');
    }

    public function test_owner_cannot_update_service_if_not_belong_to_their_laundry()
    {
        $owner = User::factory()->create(['role' => User::ROLE_OWNER]);
        $this->actingAs($owner);

        $laundry = Laundry::factory()->create();
        $service = Service::factory()->for($laundry)->create();

        $response = $this->putJson("/api/v1/owner/laundries/{$laundry->id}/services/{$service->id}", [
            'name' => 'Regular'
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['service' => 'Tidak dapat mengubah layanan!']);
    }

    public function test_owner_cannot_update_service_if_scope_not_provided()
    {
        $owner = User::factory()->create(['role' => User::ROLE_OWNER]);
        $this->actingAs($owner, []);

        $laundry = Laundry::factory()->create();
        $service = Service::factory()->for($laundry)->create();

        $response = $this->putJson("/api/v1/owner/laundries/{$laundry->id}/services/{$service->id}", [
            'name' => 'Regular'
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['service' => 'Tidak dapat mengubah layanan!']);
    }

    public function test_owner_can_delete_service()
    {
        $owner = User::factory()->create(['role' => User::ROLE_OWNER]);
        $this->actingAs($owner);

        $laundry = Laundry::factory()->for($owner)->create();
        $service = Service::factory()->for($laundry)->create();

        $response = $this->deleteJson("/api/v1/owner/laundries/{$laundry->id}/services/{$service->id}");

        $response->assertOk();

        $this->assertModelMissing($service);
    }

    public function test_owner_cannot_delete_service_if_not_belong_to_their_laundry()
    {
        $owner = User::factory()->create(['role' => User::ROLE_OWNER]);
        $this->actingAs($owner);

        $laundry = Laundry::factory()->create();
        $service = Service::factory()->for($laundry)->create();

        $response = $this->deleteJson("/api/v1/owner/laundries/{$laundry->id}/services/{$service->id}");

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['service' => 'Tidak dapat menghapus layanan!']);

        $this->assertModelExists($service);
    }

    public function test_owner_cannot_delete_service_if_scope_not_provided()
    {
        $owner = User::factory()->create(['role' => User::ROLE_OWNER]);
        $this->actingAs($owner, []);

        $laundry = Laundry::factory()->create();
        $service = Service::factory()->for($laundry)->create();

        $response = $this->deleteJson("/api/v1/owner/laundries/{$laundry->id}/services/{$service->id}");

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['service' => 'Tidak dapat menghapus layanan!']);

        $this->assertModelExists($service);
    }
}
