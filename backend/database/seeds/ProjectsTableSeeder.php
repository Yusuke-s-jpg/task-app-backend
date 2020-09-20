<?php

use Illuminate\Database\Seeder;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('projects')->insert([
            'id'          => 1,
            'title'       => 'プロジェクト1',
            'description' => '内容',
            'state'       => 'progress',
            'user_id'     => 1,
            'created_at'  => now(),
            'updated_at'  => now()
        ]);
        DB::table('projects')->insert([
            'id'          => 2,
            'title'       => 'プロジェクト2',
            'description' => '内容',
            'state'       => 'progress',
            'user_id'     => 1,
            'created_at'  => now(),
            'updated_at'  => now()
        ]);
        DB::table('projects')->insert([
            'id'          => 3,
            'title'       => 'プロジェクト3',
            'description' => '内容',
            'state'       => 'progress',
            'user_id'     => 1,
            'created_at'  => now(),
            'updated_at'  => now()
        ]);
        DB::table('projects')->insert([
            'id'          => 4,
            'title'       => 'プロジェクト4',
            'description' => '内容',
            'state'       => 'progress',
            'user_id'     => 1,
            'created_at'  => now(),
            'updated_at'  => now()
        ]);
        DB::table('projects')->insert([
            'id'          => 5,
            'title'       => 'プロジェクト5',
            'description' => '内容',
            'state'       => 'progress',
            'user_id'     => 1,
            'created_at'  => now(),
            'updated_at'  => now()
        ]);
    }
}
