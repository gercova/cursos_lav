<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserExpirationAdminTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;
    protected User $student;

    protected function setUp(): void
    {
        parent::setUp();

        // Reset permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Spatie roles
        Role::findOrCreate('admin');
        Role::findOrCreate('student');

        // Create an admin user
        $this->admin = User::create([
            'dni' => (string) rand(10000000, 99999999),
            'names' => 'Admin User',
            'email' => 'admin_test_' . rand(1000, 9999) . '@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'country_code' => '+51',
            'phone' => '999999999',
            'nationality' => 'Peruana',
            'is_active' => true,
        ]);
        $this->admin->assignRole('admin');

        // Create a student user
        $this->student = User::create([
            'dni' => (string) rand(10000000, 99999999),
            'names' => 'Student User',
            'email' => 'student_test_' . rand(1000, 9999) . '@example.com',
            'password' => bcrypt('password123'),
            'role' => 'student',
            'country_code' => '+51',
            'phone' => '999999999',
            'nationality' => 'Peruana',
            'is_active' => true,
        ]);
        $this->student->assignRole('student');
    }

    /** @test */
    public function an_admin_can_update_a_users_expiration_date()
    {
        $expirationDate = '2027-12-31';

        $response = $this->actingAs($this->admin)
            ->putJson(route('admin.users.expiration', $this->student), [
                'expires_at' => $expirationDate,
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Fecha de expiración actualizada exitosamente.',
        ]);

        $this->student->refresh();
        $this->assertEquals($expirationDate . ' 00:00:00', $this->student->expires_at->format('Y-m-d H:i:s'));
    }

    /** @test */
    public function an_admin_cannot_clear_a_users_expiration_date()
    {
        $response = $this->actingAs($this->admin)
            ->putJson(route('admin.users.expiration', $this->student), [
                'expires_at' => null,
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['expires_at']);
    }

    /** @test */
    public function an_admin_receives_validation_error_for_invalid_expiration_date()
    {
        $response = $this->actingAs($this->admin)
            ->putJson(route('admin.users.expiration', $this->student), [
                'expires_at' => 'not-a-valid-date',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['expires_at']);
    }

    /** @test */
    public function a_non_admin_cannot_update_a_users_expiration_date()
    {
        $response = $this->actingAs($this->student)
            ->putJson(route('admin.users.expiration', $this->student), [
                'expires_at' => '2027-12-31',
            ]);

        $response->assertRedirect(route('home'));
    }
}
