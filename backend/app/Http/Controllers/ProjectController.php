<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Project;
use App\Http\Requests\Projects\ProjectStoreRequest;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();

        return response()->json($projects, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ProjectStoreRequest       $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectStoreRequest $request)
    {
        $project = new Project();

        \DB::beginTransaction();

        try {
            $project->fill($request->all());
            $project->save();

            \DB::commit();
        } catch (\Exception $e) {
            \Log::Error($e->getTraceAsString());
            \DB::rollback();

            return response()->json('create error', 500);
        }

        return response()->json($project, 201);
    }
}
