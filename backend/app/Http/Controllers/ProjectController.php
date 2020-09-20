<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Project;
use App\Http\Requests\Projects\ProjectStoreRequest;
use App\Http\Requests\Projects\ProjectUpdateRequest;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();

        return response()->json($projects, 200);
    }

    public function show($id)
    {
        $project = Project::find($id);

        if (is_null($project)) {
            return response()->json('project not found', 404);
        }

        return response()->json($project, 200);
    }

    /**
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

    /**
     *
     * @param  ProjectUpdateRequest       $request
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectUpdateRequest $request, $id)
    {
        $project = Project::find($id);

        if (is_null($project)) {
            return response()->json('project is not found', 404);
        }

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

        return response()->json($project, 200);
    }

    public function destroy($id)
    {
        $project = Project::find($id);

        if (is_null($project)) {
            return response()->json('project not found', 404);
        }

        \DB::beginTransaction();

        try {
            $project->delete();

            \DB::commit();
        } catch (\Exception $e) {
            \Log::Error($e->getTraceAsString());
            \DB::rollback();

            return response()->json('destroy error', 500);
        }

        return response()->json(200);
    }
}
