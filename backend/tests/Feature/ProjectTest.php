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
    }

    ///////////////////////
    // index
    ///////////////////////

    /** @test */
    public function getAllProjects()
    {
        $this->projects = factory(Project::class, 10)->create();
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
                        'id' => $this->projects[9]->id,
                        'title' => $this->projects[9]->title,
                        'description' => $this->projects[9]->description,
                        'state' => $this->projects[9]->state,
                        'user_id' => $this->projects[9]->user_id,
                    ]
                 );
    }
    
    ///////////////////////
    //  store
    ///////////////////////

    /** @test */
    public function createProject()
    {
        $projectCount = Project::count();
        $params = [
            'title' => 'testTitle',
            'description' => 'testDescription',
            'state' => 'progress',
            'user_id' => 1
        ];
        $response = $this->json('POST', 'api/projects', $params);

        $response->assertStatus(201);
        $this->assertDatabaseHas('projects', [
                    'title' => 'testTitle',
                    'description' => 'testDescription',
                    'state' => 'progress',
                    'user_id' => 1
                 ]);

        // Check if the record is added
        $this->assertSame(Project::count(), $projectCount + 1);
    }
}
