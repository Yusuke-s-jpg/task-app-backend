<?php

use Illuminate\Database\Seeder;

class IssuesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('issues')->insert([
            'id'          => 1,
            'ordering'    => 1,
            'title'       => 'イシュー1',
            'description' => '内容',
            'state'       => 'wip',
            'project_id'  => 1,
            'created_at'  => now(),
            'updated_at'  => now()
        ]);
        DB::table('issues')->insert([
            'id'          => 2,
            'ordering'    => 2,
            'title'       => 'イシュー2',
            'description' => '内容',
            'state'       => 'wip',
            'project_id'  => 1,
            'created_at'  => now(),
            'updated_at'  => now()
        ]);
        DB::table('issues')->insert([
            'id'          => 3,
            'ordering'    => 3,
            'title'       => 'イシュー3',
            'description' => '内容',
            'state'       => 'wip',
            'project_id'  => 1,
            'created_at'  => now(),
            'updated_at'  => now()
        ]);
        DB::table('issues')->insert([
            'id'          => 4,
            'ordering'    => 4,
            'title'       => 'イシュー4',
            'description' => '内容',
            'state'       => 'wip',
            'project_id'  => 1,
            'created_at'  => now(),
            'updated_at'  => now()
        ]);
        DB::table('issues')->insert([
            'id'          => 5,
            'ordering'    => 5,
            'title'       => 'イシュー5',
            'description' => '内容',
            'state'       => 'wip',
            'project_id'  => 1,
            'created_at'  => now(),
            'updated_at'  => now()
        ]);
    }
}
