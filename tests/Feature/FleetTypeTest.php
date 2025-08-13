<?php

namespace Tests\Feature;

use App\Models\FleetType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FleetTypeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user
        $this->user = User::factory()->create();
    }

    public function test_fleet_types_index_page_can_be_rendered(): void
    {
        $response = $this->actingAs($this->user)->get('/fleet-types');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('MasterData/FleetTypes/Index'));
    }

    public function test_fleet_types_create_page_can_be_rendered(): void
    {
        $response = $this->actingAs($this->user)->get('/fleet-types/create');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('MasterData/FleetTypes/Create'));
    }

    public function test_fleet_type_can_be_created(): void
    {
        $fleetTypeData = [
            'name' => 'Test Fleet Type',
            'description' => 'Test description for fleet type',
        ];

        $response = $this->actingAs($this->user)->post('/fleet-types', $fleetTypeData);

        $response->assertRedirect('/fleet-types');
        $this->assertDatabaseHas('fleet_types', $fleetTypeData);
    }

    public function test_fleet_type_creation_requires_authentication(): void
    {
        $fleetTypeData = [
            'name' => 'Test Fleet Type',
            'description' => 'Test description',
        ];

        $response = $this->post('/fleet-types', $fleetTypeData);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('fleet_types', $fleetTypeData);
    }

    public function test_fleet_type_name_is_required(): void
    {
        $fleetTypeData = [
            'description' => 'Test description',
        ];

        $response = $this->actingAs($this->user)->post('/fleet-types', $fleetTypeData);

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseMissing('fleet_types', $fleetTypeData);
    }

    public function test_fleet_type_name_must_be_unique(): void
    {
        // Create first fleet type
        FleetType::create([
            'name' => 'Duplicate Name',
            'description' => 'First description',
        ]);

        // Try to create second with same name
        $fleetTypeData = [
            'name' => 'Duplicate Name',
            'description' => 'Second description',
        ];

        $response = $this->actingAs($this->user)->post('/fleet-types', $fleetTypeData);

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseCount('fleet_types', 1);
    }

    public function test_fleet_types_edit_page_can_be_rendered(): void
    {
        $fleetType = FleetType::create([
            'name' => 'Test Fleet Type',
            'description' => 'Test description',
        ]);

        $response = $this->actingAs($this->user)->get("/fleet-types/{$fleetType->id}/edit");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('MasterData/FleetTypes/Edit'));
    }

    public function test_fleet_type_can_be_updated(): void
    {
        $fleetType = FleetType::create([
            'name' => 'Original Name',
            'description' => 'Original description',
        ]);

        $updateData = [
            'name' => 'Updated Name',
            'description' => 'Updated description',
        ];

        $response = $this->actingAs($this->user)->put("/fleet-types/{$fleetType->id}", $updateData);

        $response->assertRedirect('/fleet-types');
        $this->assertDatabaseHas('fleet_types', $updateData);
        $this->assertDatabaseMissing('fleet_types', [
            'name' => 'Original Name',
            'description' => 'Original description',
        ]);
    }

    public function test_fleet_type_can_be_deleted(): void
    {
        $fleetType = FleetType::create([
            'name' => 'To Be Deleted',
            'description' => 'Will be deleted',
        ]);

        $response = $this->actingAs($this->user)->delete("/fleet-types/{$fleetType->id}");

        $response->assertRedirect('/fleet-types');
        $this->assertDatabaseMissing('fleet_types', ['id' => $fleetType->id]);
    }

    public function test_fleet_types_are_listed_with_pagination(): void
    {
        // Create multiple fleet types
        FleetType::create(['name' => 'Type 1', 'description' => 'Description 1']);
        FleetType::create(['name' => 'Type 2', 'description' => 'Description 2']);
        FleetType::create(['name' => 'Type 3', 'description' => 'Description 3']);

        $response = $this->actingAs($this->user)->get('/fleet-types');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->has('fleetTypes') &&
            $page->component('MasterData/FleetTypes/Index')
        );
    }

    public function test_fleet_type_search_functionality(): void
    {
        FleetType::create(['name' => 'Searchable Type', 'description' => 'This should be found']);
        FleetType::create(['name' => 'Other Type', 'description' => 'This should not be found']);

        $response = $this->actingAs($this->user)->get('/fleet-types?search=Searchable');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->has('fleetTypes') &&
            $page->component('MasterData/FleetTypes/Index')
        );
    }
}
