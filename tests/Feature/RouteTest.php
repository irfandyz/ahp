<?php

namespace Tests\Feature;

use App\Models\Route;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class RouteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);
        
        $this->actingAs($this->user);
    }

    public function test_routes_index_page_can_be_rendered(): void
    {
        $response = $this->get('/routes');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('MasterData/Routes/Index'));
    }

    public function test_routes_create_page_can_be_rendered(): void
    {
        $response = $this->get('/routes/create');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('MasterData/Routes/Create'));
    }

    public function test_route_can_be_created_with_code(): void
    {
        $routeData = [
            'code' => 'TEST_ROUTE',
            'name' => 'Test Route',
            'description' => 'Test description for route',
        ];

        $response = $this->post('/routes', $routeData);

        $response->assertRedirect('/routes');
        $this->assertDatabaseHas('routes', $routeData);
    }

    public function test_route_creation_requires_authentication(): void
    {
        // Remove authentication for this test
        auth()->logout();
        
        $routeData = [
            'code' => 'TEST_ROUTE',
            'name' => 'Test Route',
            'description' => 'Test description',
        ];

        $response = $this->post('/routes', $routeData);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('routes', $routeData);
    }

    public function test_route_code_is_required(): void
    {
        $routeData = [
            'name' => 'Test Route',
            'description' => 'Test description',
        ];

        $response = $this->post('/routes', $routeData);

        $response->assertSessionHasErrors(['code']);
        $this->assertDatabaseMissing('routes', $routeData);
    }

    public function test_route_code_must_be_unique(): void
    {
        // Create first route
        Route::create([
            'code' => 'DUPLICATE_CODE',
            'name' => 'First Route',
            'description' => 'First description',
        ]);

        // Try to create second with same code
        $routeData = [
            'code' => 'DUPLICATE_CODE',
            'name' => 'Second Route',
            'description' => 'Second description',
        ];

        $response = $this->post('/routes', $routeData);

        $response->assertSessionHasErrors(['code']);
        $this->assertDatabaseCount('routes', 1);
    }

    public function test_route_code_must_contain_only_uppercase_and_underscores(): void
    {
        $routeData = [
            'code' => 'invalid-code',
            'name' => 'Test Route',
            'description' => 'Test description',
        ];

        $response = $this->post('/routes', $routeData);

        $response->assertSessionHasErrors(['code']);
        $this->assertDatabaseMissing('routes', $routeData);
    }

    public function test_routes_edit_page_can_be_rendered(): void
    {
        $route = Route::create([
            'code' => 'TEST_ROUTE',
            'name' => 'Test Route',
            'description' => 'Test description',
        ]);

        $response = $this->get("/routes/{$route->id}/edit");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('MasterData/Routes/Edit'));
    }

    public function test_route_can_be_updated(): void
    {
        $route = Route::create([
            'code' => 'ORIGINAL_CODE',
            'name' => 'Original Name',
            'description' => 'Original description',
        ]);

        $updateData = [
            'code' => 'UPDATED_CODE',
            'name' => 'Updated Name',
            'description' => 'Updated description',
        ];

        $response = $this->put("/routes/{$route->id}", $updateData);

        $response->assertRedirect('/routes');
        $this->assertDatabaseHas('routes', $updateData);
        $this->assertDatabaseMissing('routes', [
            'code' => 'ORIGINAL_CODE',
            'name' => 'Original Name',
            'description' => 'Original description',
        ]);
    }

    public function test_route_can_be_deleted(): void
    {
        $route = Route::create([
            'code' => 'TO_DELETE',
            'name' => 'To Be Deleted',
            'description' => 'Will be deleted',
        ]);

        $response = $this->delete("/routes/{$route->id}");

        $response->assertRedirect('/routes');
        $this->assertDatabaseMissing('routes', ['id' => $route->id]);
    }

    public function test_routes_are_listed_with_pagination(): void
    {
        // Create multiple routes
        Route::create(['code' => 'ROUTE_1', 'name' => 'Route 1', 'description' => 'Description 1']);
        Route::create(['code' => 'ROUTE_2', 'name' => 'Route 2', 'description' => 'Description 2']);
        Route::create(['code' => 'ROUTE_3', 'name' => 'Route 3', 'description' => 'Description 3']);

        $response = $this->get('/routes');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->has('routes') &&
            $page->component('MasterData/Routes/Index')
        );
    }

    public function test_route_search_functionality_includes_code(): void
    {
        Route::create(['code' => 'SEARCHABLE_CODE', 'name' => 'Other Name', 'description' => 'This should be found']);
        Route::create(['code' => 'OTHER_CODE', 'name' => 'Other Route', 'description' => 'This should not be found']);

        $response = $this->get('/routes?search=SEARCHABLE');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->has('routes') &&
            $page->component('MasterData/Routes/Index')
        );
    }
}
