<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Laravel\Jetstream\Contracts\AddsTeamMembers;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_available_users_can_be_retrieved()
    {
        $team    = Team::factory()->create();
        $user    = $team->owner;
        $project = Project::factory()->for($user)->for($team)->create();

        $memberA = User::factory()->create();
        $memberB = User::factory()->create();

        app(AddsTeamMembers::class)->add($user, $team, $memberA->email, 'editor');
        app(AddsTeamMembers::class)->add($user, $team, $memberB->email, 'editor');

        $availableUsers = $project->getAvailableUsers();

        $this->assertCount(2, $availableUsers);
        $this->assertTrue($availableUsers->contains($memberA));
        $this->assertTrue($availableUsers->contains($memberB));
    }
}
