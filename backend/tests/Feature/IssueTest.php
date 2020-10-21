<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Model\Issue;
use Tests\TestCase;

class IssueTest extends TestCase
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
     * 全イシュー取得
     * status:200
    */
    /** @test */
    public function getAllIssues()
    {
        $issues = factory(Issue::class, 10)->create();
        $response = $this->json('GET', 'api/issues');

        $response->assertStatus(200)
                 ->assertJsonFragment(
                     [
                         'id' => $issues[0]->id,
                         'ordering' => $issues[0]->ordering,
                         'title' => $issues[0]->title,
                         'description' => $issues[0]->description,
                         'state' => $issues[0]->state,
                         'project_id' => $issues[0]->project_id,
                     ],
                     [
                        'id' => $issues[9]->id,
                        'ordering' => $issues[9]->ordering,
                        'title' => $issues[9]->title,
                        'description' => $issues[9]->description,
                        'state' => $issues[9]->state,
                        'project_id' => $issues[9]->project_id,
                    ]
                 );
    }

    ///////////////////////
    // show
    ///////////////////////

    /** 
     * 指定されたイシューを一つ取得
     * status:200
    */
    /** @test */
    public function showIssue()
    {
        $issue = factory(Issue::class)->create();
        $issue_id = $issue->id;
        $response = $this->json('GET', "api/issues/{$issue_id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(
                     [
                         'id' => $issue->id,
                         'ordering' => $issue->ordering,
                         'title' => $issue->title,
                         'description' => $issue->description,
                         'state' => $issue->state,
                         'project_id' => $issue->project_id
                     ]
                 );
    }
    
    /** 
     * 存在しないイシューを取得
     * status:404
    */
    /** @test */
    public function showIssueWhenTheIdDoseNotExist()
    {
        $issue = factory(Issue::class)->create();
        $issue_id = $issue->id + 1;
        $response = $this->json('GET', "api/issues/{$issue_id}");

        $response->assertStatus(404)
                 ->assertJsonMissing(
                     [
                         'id' => $issue->id,
                         'ordering' => $issue->ordering,
                         'title' => $issue->title,
                         'description' => $issue->description,
                         'state' => $issue->state,
                         'project_id' => $issue->project_id,
                     ]
                 );
    }

    ///////////////////////
    //  store
    ///////////////////////

    /**
     * 新しいイシューを保存
     * status:201
     */
    /** @test */
    public function storeIssue()
    {
        $issueCount = Issue::count();
        $params = [
            'title' => 'testTitle',
            'description' => 'testDescription',
            'state' => 'progress',
            'project_id' => 1
        ];
        $response = $this->json('POST', 'api/issues', $params);

        $response->assertStatus(201);
        $this->assertDatabaseHas('issues', [
                    'title' => 'testTitle',
                    'description' => 'testDescription',
                    'state' => 'progress',
                    'project_id' => 1
                 ]);

        // レコードが追加されているかチェック
        $this->assertSame(Issue::count(), $issueCount + 1);
    }

    ///////////////////////
    //  update
    ///////////////////////

    /**
     * イシューを更新
     * status:200
     */
    /** @test */
    public function updateIssue()
    {
        $issueCount = Issue::count();

        // オリジナルデータ作成
        $params = [
            'title' => 'testTitle',
            'description' => 'testDescription',
            'state' => 'progress',
            'project_id' => 1
        ];
        $response = $this->json('POST', 'api/issues', $params);

        $issue = Issue::where('title', 'testTitle')->first();

        // アップデート用データ作成
        $params = [
            'title' => 'updatedTitle',
            'description' => 'updatedDescription',
            'state' => 'progress',
            'project_id' => 1
        ];
        $response = $this->json('PUT', "api/issues/{$issue->id}", $params);

        $response->assertStatus(200);
        $this->assertDatabaseHas('issues', [
                'title' => 'updatedTitle',
                'description' => 'updatedDescription',
                'state' => 'progress',
                'project_id' => 1
             ])
             ->assertDatabaseMissing('issues', [
                'title' => 'testTitle',
                'description' => 'testDescription',
                'state' => 'progress',
                'project_id' => 1
             ]);

        // レコードが追加されていないことを確認
        $this->assertSame(Issue::count(), $issueCount + 1);
    }

    /**
     * 存在しないイシューを更新
     * status:404
     */
    /** @test */
    public function updateIssueWhenTheIdDoseNotExist()
    {
        $issueCount = Issue::count();

        // オリジナルデータ作成
        $params = [
            'title' => 'testTitle',
            'description' => 'testDescription',
            'state' => 'progress',
            'project_id' => 1
        ];
        $response = $this->json('POST', 'api/issues', $params);

        $issue = Issue::where('title', 'testTitle')->first();
        $issue_id = $issue->id + 1;

        // アップデート用データ作成
        $params = [
            'title' => 'updatedTitle',
            'description' => 'updatedDescription',
            'state' => 'progress',
            'project_id' => 1
        ];
        $response = $this->json('PUT', "api/issues/{$issue_id}", $params);

        $response->assertStatus(404);
        $this->assertDatabaseHas('issues', [
                'title' => 'testTitle',
                'description' => 'testDescription',
                'state' => 'progress',
                'project_id' => 1
             ])
             ->assertDatabaseMissing('issues', [
                'title' => 'updatedTitle',
                'description' => 'updatedDescription',
                'state' => 'progress',
                'project_id' => 1
             ]);

        // レコードが追加されていないことを確認
        $this->assertSame(Issue::count(), $issueCount + 1);
    }

    ///////////////////////
    // delete
    ///////////////////////

    /** 
     * イシューを削除
     * status:200
    */
    /** @test */
    public function deleteIssue()
    {
        $issue = factory(Issue::class)->create();
        $issue_id = $issue->id;
        $issueCount = Issue::count();
        $response = $this->json('DELETE', "api/issues/{$issue_id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('issues', 
                    [
                        'id' => $issue->id,
                        'ordering' => $issue->ordering,
                        'title' => $issue->title,
                        'description' => $issue->description,
                        'state' => $issue->state,
                        'project_id' => $issue->project_id
                    ]);

        // レコードが削除されていることを確認
        $this->assertSame(Issue::count(), $issueCount - 1);
    }

    /** 
     * 存在しないイシューを削除
     * status:404
    */
    /** @test */
    public function deleteIssueWhenTheIdDoseNotExist()
    {
        $issue = factory(Issue::class)->create();
        $issue_id = $issue->id + 1;
        $issueCount = Issue::count();
        $response = $this->json('DELETE', "api/issues/{$issue_id}");

        $response->assertStatus(404);
        $this->assertDatabaseHas('issues', 
                    [
                        'id' => $issue->id,
                        'ordering' => $issue->ordering,
                        'title' => $issue->title,
                        'description' => $issue->description,
                        'state' => $issue->state,
                        'project_id' => $issue->project_id
                    ]);

        // レコードが削除されていないことを確認
        $this->assertSame(Issue::count(), $issueCount);
    }
}
