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

    /** 
     * status:200
    */
    /** @test */
    public function getAllProjects()
    {
        $projects = factory(Project::class, 10)->create();
        $response = $this->json('GET', 'api/projects');

        $response->assertStatus(200)
                 ->assertJsonFragment(
                     [
                         'id' => $projects[0]->id,
                         'title' => $projects[0]->title,
                         'description' => $projects[0]->description,
                         'state' => $projects[0]->state,
                         'user_id' => $projects[0]->user_id,
                     ],
                     [
                        'id' => $projects[9]->id,
                        'title' => $projects[9]->title,
                        'description' => $projects[9]->description,
                        'state' => $projects[9]->state,
                        'user_id' => $projects[9]->user_id,
                    ]
                 );
    }

    ///////////////////////
    // show
    ///////////////////////

    /** 
     * status:200
    */
    /** @test */
    public function showProject()
    {
        $project = factory(Project::class)->create();
        $project_id = $project->id;
        $response = $this->json('GET', "api/projects/{$project_id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(
                     [
                         'id' => $project->id,
                         'title' => $project->title,
                         'description' => $project->description,
                         'state' => $project->state,
                         'user_id' => $project->user_id
                     ]
                 );
    }
    
    /** 
     * status:404
    */
    /** @test */
    public function showProjectWhenTheIdDoseNotExist()
    {
        $project = factory(Project::class)->create();
        $project_id = $project->id + 1;
        $response = $this->json('GET', "api/projects/{$project_id}");

        $response->assertStatus(404)
                 ->assertJsonMissing(
                     [
                         'id' => $project->id,
                         'title' => $project->title,
                         'description' => $project->description,
                         'state' => $project->state,
                         'user_id' => $project->user_id,
                     ]
                 );
    }

    ///////////////////////
    //  store
    ///////////////////////

    /**
     * status:201
     */
    /** @test */
    public function storeProject()
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

        // レコードが追加されているかチェック
        $this->assertSame(Project::count(), $projectCount + 1);
    }

    ///////////////////////
    //  update
    ///////////////////////

    /**
     * status:200
     */
    /** @test */
    public function updateProject()
    {
        $projectCount = Project::count();

        // オリジナルデータ作成
        $params = [
            'title' => 'testTitle',
            'description' => 'testDescription',
            'state' => 'progress',
            'user_id' => 1
        ];
        $response = $this->json('POST', 'api/projects', $params);

        $project = Project::where('title', 'testTitle')->first();

        // アップデート用データ作成
        $params = [
            'title' => 'updatedTitle',
            'description' => 'updatedDescription',
            'state' => 'progress',
            'user_id' => 1
        ];
        $response = $this->json('PUT', "api/projects/{$project->id}", $params);

        $response->assertStatus(200);
        $this->assertDatabaseHas('projects', [
                'title' => 'updatedTitle',
                'description' => 'updatedDescription',
                'state' => 'progress',
                'user_id' => 1
             ])
             ->assertDatabaseMissing('projects', [
                'title' => 'testTitle',
                'description' => 'testDescription',
                'state' => 'progress',
                'user_id' => 1
             ]);

        // レコードが追加されていないことを確認
        $this->assertSame(Project::count(), $projectCount + 1);
    }

    /**
     * status:404
     */
    /** @test */
    public function updateProjectWhenTheIdDoseNotExist()
    {
        $projectCount = Project::count();

        // オリジナルデータ作成
        $params = [
            'title' => 'testTitle',
            'description' => 'testDescription',
            'state' => 'progress',
            'user_id' => 1
        ];
        $response = $this->json('POST', 'api/projects', $params);

        $project = Project::where('title', 'testTitle')->first();
        $project_id = $project->id + 1;

        // アップデート用データ作成
        $params = [
            'title' => 'updatedTitle',
            'description' => 'updatedDescription',
            'state' => 'progress',
            'user_id' => 1
        ];
        $response = $this->json('PUT', "api/projects/{$project_id}", $params);

        $response->assertStatus(404);
        $this->assertDatabaseHas('projects', [
                'title' => 'testTitle',
                'description' => 'testDescription',
                'state' => 'progress',
                'user_id' => 1
             ])
             ->assertDatabaseMissing('projects', [
                'title' => 'updatedTitle',
                'description' => 'updatedDescription',
                'state' => 'progress',
                'user_id' => 1
             ]);

        // レコードが追加されていないことを確認
        $this->assertSame(Project::count(), $projectCount + 1);
    }

    ///////////////////////
    // delete
    ///////////////////////

    /** 
     * status:200
    */
    /** @test */
    public function deleteProject()
    {
        $project = factory(Project::class)->create();
        $project_id = $project->id;
        $projectCount = Project::count();
        $response = $this->json('DELETE', "api/projects/{$project_id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('projects', 
                    [
                        'id' => $project->id,
                        'title' => $project->title,
                        'description' => $project->description,
                        'state' => $project->state,
                        'user_id' => $project->user_id
                    ]);

        // レコードが削除されていることを確認
        $this->assertSame(Project::count(), $projectCount - 1);
    }

    /** 
     * status:404
    */
    /** @test */
    public function deleteProjectWhenTheIdDoseNotExist()
    {
        $project = factory(Project::class)->create();
        $project_id = $project->id + 1;
        $projectCount = Project::count();
        $response = $this->json('DELETE', "api/projects/{$project_id}");

        $response->assertStatus(404);
        $this->assertDatabaseHas('projects', 
                    [
                        'id' => $project->id,
                        'title' => $project->title,
                        'description' => $project->description,
                        'state' => $project->state,
                        'user_id' => $project->user_id
                    ]);

        // レコードが削除されていないことを確認
        $this->assertSame(Project::count(), $projectCount);
    }
}
