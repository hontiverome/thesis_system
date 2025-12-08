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
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    }

    // Student Login Tests
    public function test_student_can_login_with_correct_credentials()
    {
        $birthDate = Carbon::create(2000, 1, 15)->startOfDay();
        $password = 'password123';
        $user = User::factory()->create([
            'id_number' => '2023-00002-MN-0',
            'birth_date' => $birthDate,
            'password' => Hash::make($password),
        ]);
        $user->roles()->attach(Role::where('name', 'Student')->first());

        $response = $this->postJson('/api/v1/login', [
            'student_number' => '2023-00002-MN-0',
            'birth_month' => 1,
            'birth_day' => 15,
            'birth_year' => 2000,
            'password' => $password,
            'device_name' => 'test-device',
        ]);

        $response->assertStatus(200)->assertJsonStructure(['user', 'token']);
    }

    public function test_student_cannot_login_with_incorrect_password()
    {
        User::factory()->create(['password' => Hash::make('correct-password')]);
        $response = $this->postJson('/api/v1/login', [
            'student_number' => '2023-00002-MN-0',
            'birth_month' => 1, 'birth_day' => 15, 'birth_year' => 2000,
            'password' => 'wrong-password', 'device_name' => 'test-device',
        ]);
        $response->assertStatus(422);
    }
    
    // Faculty Login Tests
    public function test_faculty_can_login_with_correct_credentials()
    {
        $password = 'password123';
        $faculty = User::factory()->create([
            'id_number' => 'FAC-001',
            'password' => Hash::make($password),
        ]);
        $facultyRole = Role::firstOrCreate(['name' => 'faculty']);
        $faculty->roles()->attach($facultyRole);

        $response = $this->postJson('/api/v1/login', [
            'faculty_id' => 'FAC-001',
            'password' => $password,
            'device_name' => 'test-device',
        ]);

        $response->assertStatus(200)->assertJson([
            'user' => ['id' => $faculty->id],
        ]);
    }

    public function test_faculty_cannot_login_with_incorrect_password()
    {
        $faculty = User::factory()->create([
            'id_number' => 'FAC-001',
            'password' => Hash::make('correct-password'),
        ]);
        $facultyRole = Role::firstOrCreate(['name' => 'faculty']);
        $faculty->roles()->attach($facultyRole);

        $response = $this->postJson('/api/v1/login', [
            'faculty_id' => 'FAC-001',
            'password' => 'wrong-password',
            'device_name' => 'test-device',
        ]);

        $response->assertStatus(422);
    }

    public function test_student_cannot_login_as_faculty()
    {
        $password = 'password123';
        $student = User::factory()->create([
            'id_number' => '2023-00003-MN-0',
            'password' => Hash::make($password),
        ]);
        $student->roles()->attach(Role::where('name', 'Student')->first());

        $response = $this->postJson('/api/v1/login', [
            'faculty_id' => '2023-00003-MN-0',
            'password' => $password,
            'device_name' => 'test-device',
        ]);

        $response->assertStatus(422);
    }
}
