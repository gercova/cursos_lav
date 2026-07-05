<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserExpirationUpdateTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles and permissions if not already present
        if (Permission::count() === 0) {
            $this->seed(RolePermissionSeeder::class);
        }
    }

    /** @test */
    public function an_authorized_admin_can_update_a_user_expiration_date()
    {
        $admin = User::create([
            'dni' => (string) rand(10000000, 99999999),
            'names' => 'Admin User',
            'email' => 'admin_exp_test_' . rand(1000, 9999) . '@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'country_code' => '+51',
            'phone' => '999999999',
            'nationality' => 'Peruana',
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        $student = User::create([
            'dni' => (string) rand(10000000, 99999999),
            'names' => 'Student User',
            'email' => 'student_exp_test_' . rand(1000, 9999) . '@example.com',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'country_code' => '+51',
            'phone' => '999999999',
            'nationality' => 'Peruana',
            'expires_at' => null,
            'is_active' => true,
        ]);

        $newDateString = '2027-12-31';
        $response = $this->actingAs($admin)
            ->putJson(route('admin.users.expiration', $student), [
                'expires_at' => $newDateString,
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $student->refresh();
        $this->assertNotNull($student->expires_at);
        $this->assertEquals($newDateString . ' 23:59:59', $student->expires_at->format('Y-m-d H:i:s'));
    }

    /** @test */
    public function updating_a_company_expiration_date_updates_all_affiliated_collaborators()
    {
        $admin = User::create([
            'dni' => (string) rand(10000000, 99999999),
            'names' => 'Admin User',
            'email' => 'admin_exp_test_' . rand(1000, 9999) . '@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'country_code' => '+51',
            'phone' => '999999999',
            'nationality' => 'Peruana',
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        $company = User::create([
            'dni' => (string) rand(10000000, 99999999),
            'names' => 'Company User',
            'email' => 'company_exp_test_' . rand(1000, 9999) . '@example.com',
            'password' => Hash::make('password123'),
            'role' => 'business',
            'country_code' => '+51',
            'phone' => '999999999',
            'nationality' => 'Peruana',
            'expires_at' => null,
            'is_active' => true,
        ]);

        $collaborators = [];
        for ($i = 0; $i < 3; $i++) {
            $collaborators[] = User::create([
                'dni' => (string) rand(10000000, 99999999),
                'names' => "Collaborator $i",
                'email' => "collab_exp_test_{$i}_" . rand(1000, 9999) . "@example.com",
                'password' => Hash::make('password123'),
                'role' => 'student',
                'parent_id' => $company->id,
                'country_code' => '+51',
                'phone' => '999999999',
                'nationality' => 'Peruana',
                'expires_at' => null,
                'is_active' => true,
            ]);
        }

        $newDateString = '2028-06-30';
        $response = $this->actingAs($admin)
            ->putJson(route('admin.users.expiration', $company), [
                'expires_at' => $newDateString,
            ]);

        $response->assertStatus(200);

        $company->refresh();
        $this->assertEquals($newDateString . ' 23:59:59', $company->expires_at->format('Y-m-d H:i:s'));

        foreach ($collaborators as $collab) {
            $collab->refresh();
            $this->assertEquals($newDateString . ' 23:59:59', $collab->expires_at->format('Y-m-d H:i:s'));
        }
    }

    /** @test */
    public function a_student_user_cannot_access_expiration_update_route()
    {
        $studentUser = User::create([
            'dni' => (string) rand(10000000, 99999999),
            'names' => 'Student User',
            'email' => 'student_exp_test_' . rand(1000, 9999) . '@example.com',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'country_code' => '+51',
            'phone' => '999999999',
            'nationality' => 'Peruana',
            'is_active' => true,
        ]);
        $studentUser->assignRole('student');

        $targetUser = User::create([
            'dni' => (string) rand(10000000, 99999999),
            'names' => 'Target User',
            'email' => 'target_exp_test_' . rand(1000, 9999) . '@example.com',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'country_code' => '+51',
            'phone' => '999999999',
            'nationality' => 'Peruana',
            'expires_at' => null,
            'is_active' => true,
        ]);

        // Students are redirected by the Admin middleware
        $response = $this->actingAs($studentUser)
            ->putJson(route('admin.users.expiration', $targetUser), [
                'expires_at' => '2027-12-31',
            ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
    }

    /** @test */
    public function an_instructor_user_without_edit_permission_is_forbidden()
    {
        $instructorUser = User::create([
            'dni' => (string) rand(10000000, 99999999),
            'names' => 'Instructor User',
            'email' => 'instructor_exp_test_' . rand(1000, 9999) . '@example.com',
            'password' => Hash::make('password123'),
            'role' => 'instructor',
            'country_code' => '+51',
            'phone' => '999999999',
            'nationality' => 'Peruana',
            'is_active' => true,
        ]);
        $instructorUser->assignRole('instructor'); // Instructors do not have edit_users permission

        $targetUser = User::create([
            'dni' => (string) rand(10000000, 99999999),
            'names' => 'Target User',
            'email' => 'target_exp_test_' . rand(1000, 9999) . '@example.com',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'country_code' => '+51',
            'phone' => '999999999',
            'nationality' => 'Peruana',
            'expires_at' => null,
            'is_active' => true,
        ]);

        // Instructors pass the Admin middleware but are blocked by Spatie's permission middleware (returns 403)
        $response = $this->actingAs($instructorUser)
            ->putJson(route('admin.users.expiration', $targetUser), [
                'expires_at' => '2027-12-31',
            ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function updating_with_missing_or_past_expiration_date_fails_validation()
    {
        $admin = User::create([
            'dni' => (string) rand(10000000, 99999999),
            'names' => 'Admin User',
            'email' => 'admin_exp_test_' . rand(1000, 9999) . '@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'country_code' => '+51',
            'phone' => '999999999',
            'nationality' => 'Peruana',
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        $student = User::create([
            'dni' => (string) rand(10000000, 99999999),
            'names' => 'Student User',
            'email' => 'student_exp_test_' . rand(1000, 9999) . '@example.com',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'country_code' => '+51',
            'phone' => '999999999',
            'nationality' => 'Peruana',
            'expires_at' => null,
            'is_active' => true,
        ]);

        // Case 1: Empty date
        $response1 = $this->actingAs($admin)
            ->putJson(route('admin.users.expiration', $student), [
                'expires_at' => '',
            ]);
        $response1->assertStatus(422);
        $response1->assertJsonValidationErrors(['expires_at']);

        // Case 2: Past date
        $pastDateString = now()->subDay()->format('Y-m-d');
        $response2 = $this->actingAs($admin)
            ->putJson(route('admin.users.expiration', $student), [
                'expires_at' => $pastDateString,
            ]);
        $response2->assertStatus(422);
        $response2->assertJsonValidationErrors(['expires_at']);
    }
}
