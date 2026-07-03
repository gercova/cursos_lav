<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginExpirationTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function a_user_with_no_expiration_date_can_log_in_successfully()
    {
        $dni = (string) rand(10000000, 99999999);
        $email = 'user_no_exp_' . $dni . '@example.com';
        
        $user = User::create([
            'dni' => $dni,
            'names' => 'John Doe',
            'email' => $email,
            'password' => Hash::make('password123'),
            'role' => 'student',
            'country_code' => '+51',
            'phone' => '999999999',
            'nationality' => 'Peruana',
            'expires_at' => null,
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => $email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function a_user_whose_account_has_expired_cannot_log_in()
    {
        $dni = (string) rand(10000000, 99999999);
        $email = 'user_expired_' . $dni . '@example.com';

        $user = User::create([
            'dni' => $dni,
            'names' => 'Jane Doe',
            'email' => $email,
            'password' => Hash::make('password123'),
            'role' => 'student',
            'country_code' => '+51',
            'phone' => '999999999',
            'nationality' => 'Peruana',
            'expires_at' => now()->subDay(),
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => $email,
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
        
        $errors = session('errors')->get('email');
        $this->assertStringContainsString('Tu cuenta ha caducado', $errors[0]);
    }

    /** @test */
    public function a_user_whose_account_expires_in_the_future_can_log_in()
    {
        $dni = (string) rand(10000000, 99999999);
        $email = 'user_future_exp_' . $dni . '@example.com';

        $user = User::create([
            'dni' => $dni,
            'names' => 'Bob Smith',
            'email' => $email,
            'password' => Hash::make('password123'),
            'role' => 'student',
            'country_code' => '+51',
            'phone' => '999999999',
            'nationality' => 'Peruana',
            'expires_at' => now()->addHours(2),
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => $email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function an_expired_admin_can_still_log_in()
    {
        $dni = (string) rand(10000000, 99999999);
        $email = 'admin_expired_' . $dni . '@example.com';

        $user = User::create([
            'dni' => $dni,
            'names' => 'Admin User',
            'email' => $email,
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'country_code' => '+51',
            'phone' => '999999999',
            'nationality' => 'Peruana',
            'expires_at' => now()->subDay(),
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => $email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }
}
