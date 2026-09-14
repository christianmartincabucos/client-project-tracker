<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_projects_can_be_listed(): void
    {
        Project::factory()->count(2)->create();

        $this->getJson('/projects')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_project_can_be_created(): void
    {
        $payload = [
            'clientName' => 'Acme Corporation',
            'projectName' => 'New Website',
            'description' => 'A new build',
            'status' => 'Planning',
            'priority' => 'High',
            'startDate' => '2026-09-15',
            'dueDate' => '2026-10-15',
        ];

        $response = $this->postJson('/projects', $payload);
        $response
            ->assertCreated()
            ->assertJsonPath('data.clientName', 'Acme Corporation');

        $this->assertDatabaseHas('projects', [
            'project_name' => 'New Website',
        ]);
    }

    public function test_creation_validates_required_fields_and_dates(): void
    {
        $this->postJson('/projects', [
            'status' => 'Invalid',
            'priority' => 'Invalid',
            'startDate' => '2026-10-15',
            'dueDate' => '2026-10-01',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['client_name', 'project_name', 'status', 'priority', 'due_date']);
    }

    public function test_project_can_be_updated(): void
    {
        $project = Project::factory()->create();

        $this->putJson("/projects/{$project->id}", [
            'clientName' => 'Updated Client',
            'projectName' => $project->project_name,
            'status' => 'Completed',
            'priority' => 'Medium',
            'startDate' => '2026-09-01',
            'dueDate' => '2026-09-30',
        ])
            ->assertOk()
            ->assertJsonPath('data.clientName', 'Updated Client')
            ->assertJsonPath('data.status', 'Completed');
    }

    public function test_project_can_be_deleted(): void
    {
        $project = Project::factory()->create();

        $this->deleteJson("/projects/{$project->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_missing_project_returns_not_found(): void
    {
        $this->getJson('/projects/99999')->assertNotFound();
    }
}
