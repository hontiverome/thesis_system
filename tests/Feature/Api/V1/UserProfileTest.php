<?php

namespace Tests\Feature\Api\V1;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    }

    #[Test]
    public function unauthenticated_user_cannot_access_profile()
    {
        $response = $this->getJson('/api/v1/users/me');

        $response->assertUnauthorized();
    }

    #[Test]
    public function authenticated_user_can_retrieve_profile_data()
    {
        $user = User::factory()->create();
        $role = Role::where('name', 'student')->first();
        $user->roles()->attach($role->id);

        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson('/api/v1/users/me');

        $response->assertOk()
            ->assertJson([
                'full_name' => $user->full_name,
                'email' => $user->email,
                'roles' => ['student'],
                'student_id' => $user->id_number,
            ]);
    }

    #[Test]
    public function student_profile_includes_student_id()
    {
        $user = User::factory()->create();
        $studentRole = Role::where('name', 'student')->first();
        $user->roles()->attach($studentRole->id);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/users/me');

        $response->assertOk()
            ->assertJsonStructure([
                'full_name',
                'email',
                'roles',
                'student_id'
            ])
            ->assertJson([
                'student_id' => $user->id_number
            ]);
    }

    #[Test]
    public function non_student_profile_does_not_include_student_id()
    {
        $user = User::factory()->create();
        $facultyRole = Role::where('name', 'faculty')->first();
        $user->roles()->attach($facultyRole->id);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/users/me');

        $response->assertOk()
            ->assertJsonStructure([
                'full_name',
                'email',
                'roles'
            ])
            ->assertJsonMissing([
                'student_id'
            ]);
    }
}