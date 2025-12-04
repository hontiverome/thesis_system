<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed the roles because the user factory might depend on it, 
        // and attach a role to the created user.
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    }

    public function test_user_can_login_with_correct_credentials()
    {
        // Create a user to log in
        $birthDate = Carbon::create(2000, 1, 15)->startOfDay();
        $password = 'password123';
        $user = User::factory()->create([
            'id_number' => '2023-00002-ST-0',
            'birth_date' => $birthDate,
            'password' => Hash::make($password),
        ]);
        $studentRole = Role::where('name', 'Student')->first();
        $user->roles()->attach($studentRole);

        $loginData = [
            'student_number' => '2023-00002-ST-0',
            'birth_month' => 1,
            'birth_day' => 15,
            'birth_year' => 2000,
            'password' => $password,
            'device_name' => 'test-device',
        ];

        $response = $this->postJson('/api/v1/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => ['id', 'full_name', 'email', 'roles'],
                'token',
                'token_type',
            ])
            ->assertJson([
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                ],
                'token_type' => 'Bearer',
            ]);
    }

    public function test_user_cannot_login_with_incorrect_password()
    {
        $birthDate = Carbon::create(2000, 1, 15)->startOfDay();
        User::factory()->create([
            'id_number' => '2023-00002-ST-0',
            'birth_date' => $birthDate,
            'password' => Hash::make('correct-password'),
        ]);

        $loginData = [
            'student_number' => '2023-00002-ST-0',
            'birth_month' => 1,
            'birth_day' => 15,
            'birth_year' => 2000,
            'password' => 'wrong-password',
            'device_name' => 'test-device',
        ];

        $response = $this->postJson('/api/v1/login', $loginData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['student_number']);
    }

    public function test_user_cannot_login_with_incorrect_birth_date()
    {
        $birthDate = Carbon::create(2000, 1, 15)->startOfDay();
        $password = 'password123';
        User::factory()->create([
            'id_number' => '2023-00002-ST-0',
            'birth_date' => $birthDate,
            'password' => Hash::make($password),
        ]);

        $loginData = [
            'student_number' => '2023-00002-ST-0',
            'birth_month' => 1,
            'birth_day' => 16, // Incorrect day
            'birth_year' => 2000,
            'password' => $password,
            'device_name' => 'test-device',
        ];

        $response = $this->postJson('/api/v1/login', $loginData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['student_number']);
    }

    public function test_user_cannot_login_with_non_existent_student_number()
    {
        $loginData = [
            'student_number' => '2023-99999-ST-0', // Does not exist
            'birth_month' => 1,
            'birth_day' => 15,
            'birth_year' => 2000,
            'password' => 'password',
            'device_name' => 'test-device',
        ];

        $response = $this->postJson('/api/v1/login', $loginData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['student_number']);
    }
}
