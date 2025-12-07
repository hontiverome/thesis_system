<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed the roles
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    }

    /**
     * Test a user can register successfully.
     *
     * @return void
     */
    public function test_user_can_register_successfully()
    {
        $password = 'password123';
        $data = [
            'student_number' => '2023-00001-MN-0',
            'birth_month' => 1,
            'birth_day' => 1,
            'birth_year' => 2000,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $response = $this->postJson('/api/v1/register', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'user' => ['id', 'full_name', 'email', 'roles'],
                'token',
                'token_type',
            ])
            ->assertJson([
                'message' => 'Registration successful.',
                'user' => [
                    'email' => '2023-00001-MN-0@iskolarngbayan.pup.edu.ph',
                    'roles' => ['student'],
                ],
                'token_type' => 'Bearer',
            ]);

        $this->assertDatabaseHas('users', [
            'id_number' => '2023-00001-MN-0',
            'email' => '2023-00001-MN-0@iskolarngbayan.pup.edu.ph',
        ]);

        $user = User::where('id_number', '2023-00001-MN-0')->first();
        $this->assertTrue($user->roles->contains('name', 'student'));
    }

    /**
     * Test registration fails with invalid data.
     *
     * @return void
     */
    public function test_registration_fails_with_invalid_data()
    {
        $data = [
            'student_number' => '', // Invalid
            'birth_month' => 13,   // Invalid
            'birth_day' => 32,     // Invalid
            'birth_year' => 200,   // Invalid
            'password' => 'short',
            'password_confirmation' => 'mismatch',
        ];

        $response = $this->postJson('/api/v1/register', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['student_number', 'birth_month', 'birth_day', 'birth_year', 'password']);
    }

    /**
     * Test registration fails if student number is not unique.
     *
     * @return void
     */
    public function test_registration_fails_if_student_number_is_not_unique()
    {
        // Create a user first
        User::factory()->create(['id_number' => '2023-00001-MN-0']);

        $password = 'password123';
        $data = [
            'student_number' => '2023-00001-MN-0',
            'birth_month' => 1,
            'birth_day' => 1,
            'birth_year' => 2000,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $response = $this->postJson('/api/v1/register', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['student_number']);
    }
}
