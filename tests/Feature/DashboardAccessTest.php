<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate', ['--database' => 'mysql']);
        $this->artisan('db:seed', ['--class' => 'RolesSeeder', '--database' => 'mysql']);
    }

    protected function createAdmin(): User
    {
        return User::forceCreate([
            'role_id' => Role::where('name', 'Admin')->first()->id,
            'username' => 'admin_user',
            'email' => 'admin@example.com',
            'name' => 'Admin User',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
    }

    protected function createInternship(): User
    {
        return User::forceCreate([
            'role_id' => Role::where('name', 'Internship')->first()->id,
            'username' => 'internship_user',
            'email' => 'internship@example.com',
            'name' => 'Internship User',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
    }

    public function test_admin_can_access_home_dashboard(): void
    {
        $user = $this->createAdmin();

        $response = $this->actingAs($user)->get('/home');

        $response->assertOk();
        $response->assertSee('Hello, Admin!');
    }

    public function test_internship_user_cannot_access_home_dashboard(): void
    {
        $user = $this->createInternship();

        $response = $this->actingAs($user)->get('/home');

        $response->assertForbidden();
    }

    public function test_internship_user_can_access_index_dashboard(): void
    {
        $user = $this->createInternship();

        $response = $this->actingAs($user)->get('/');

        $response->assertOk();
        $response->assertSee('Hello, Internship User!');
    }

    public function test_admin_user_cannot_access_internship_index_dashboard(): void
    {
        $user = $this->createAdmin();

        $response = $this->actingAs($user)->get('/');

        $response->assertForbidden();
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/home');

        $response->assertRedirect();
    }

    public function test_guest_cannot_access_internship_dashboard(): void
    {
        $response = $this->get('/');

        $response->assertRedirect();
    }
}
