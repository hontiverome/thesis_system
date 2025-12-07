<?php

namespace Tests\Feature\Api\V1;

use Database\Factories\GroupFactory;
use App\Models\Group;
use App\Models\Proposal;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserProfileResearchArchiveTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    }

    #[Test]
    public function student_profile_includes_research_archive()
    {
        $user = User::factory()->create();
        $studentRole = Role::where('name', 'student')->first();
        $user->roles()->attach($studentRole->id);

        $group = Group::factory()->create();
        $group->users()->attach($user->id, ['role' => 'leader']);

        $proposal = Proposal::factory()->create([
            'group_id' => $group->id,
            'abstract' => 'This is a test abstract.',
            'manuscript_path' => '/path/to/manuscript.pdf',
            'published_date' => '2025-12-07',
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/users/me');

        $response->assertOk()
            ->assertJson([
                'research_archive' => [
                    'abstract' => 'This is a test abstract.',
                    'manuscript' => '/path/to/manuscript.pdf',
                    'published_date' => '2025-12-07',
                ]
            ]);
    }
}
