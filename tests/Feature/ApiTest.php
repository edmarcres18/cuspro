<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test registration endpoint.
     *
     * @return void
     */
    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'user',
                         'access_token',
                         'token_type'
                     ]
                 ]);
    }

    /**
     * Test login endpoint.
     *
     * @return void
     */
    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'user',
                         'access_token',
                         'token_type'
                     ]
                 ]);
    }

    /**
     * Test protected endpoints.
     *
     * @return void
     */
    public function test_protected_endpoints_require_authentication()
    {
        // Test unauthenticated access
        $response = $this->getJson('/api/areas');
        $response->assertStatus(401);
        
        // Test authenticated access
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        
        $response = $this->getJson('/api/areas');
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /**
     * Test area API endpoints.
     *
     * @return void
     */
    public function test_area_api_endpoints()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        
        // Test creating an area
        $response = $this->postJson('/api/areas', [
            'name' => 'Test Area'
        ]);
        
        $response->assertStatus(201)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Area created successfully'
                 ]);
                 
        $areaId = $response->json('data.id');
        
        // Test getting an area
        $response = $this->getJson("/api/areas/{$areaId}");
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'data' => [
                         'id' => $areaId,
                         'name' => 'Test Area'
                     ]
                 ]);
                 
        // Test updating an area
        $response = $this->putJson("/api/areas/{$areaId}", [
            'name' => 'Updated Area'
        ]);
        
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Area updated successfully'
                 ]);
                 
        // Test deleting an area
        $response = $this->deleteJson("/api/areas/{$areaId}");
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Area deleted successfully'
                 ]);
    }
} 