<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Model\Project;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->projects = factory(Project::class, 10)->create();
    }

    /** @test */
    public function getAllProjects()
    {
        $response = $this->json('GET', 'api/projects');

        $response->assertStatus(200)
                 ->assertJsonFragment(
                     [
                         'id' => $this->projects[0]->id,
                         'title' => $this->projects[0]->title,
                         'description' => $this->projects[0]->description,
                         'state' => $this->projects[0]->state,
                         'user_id' => $this->projects[0]->user_id,
                     ],
                     [
                        'id' => $this->projects[10]->id,
                        'title' => $this->projects[10]->title,
                        'description' => $this->projects[10]->description,
                        'state' => $this->projects[10]->state,
                        'user_id' => $this->projects[10]->user_id,
                    ]
                 );
    }
}
