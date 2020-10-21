<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Issue;
use App\Http\Requests\Issues\IssueStoreRequest;
use App\Http\Requests\Issues\IssueUpdateRequest;

class IssueController extends Controller
{
    public function index()
    {
        $issues = Issue::all();

        return response()->json($issues, 200);
    }

    public function show($id)
    {
        $issue = Issue::find($id);

        if (is_null($issue)) {
            return response()->json('issue not found', 404);
        }

        return response()->json($issue, 200);
    }

    /**
     *
     * @param  IssueStoreRequest       $request
     * @return \Illuminate\Http\Response
     */
    public function store(IssueStoreRequest $request)
    {
        $issue = new Issue();

        \DB::beginTransaction();

        try {
            $issue->fill($request->all());
            $issue->ordering = Issue::count() + 1;
            $issue->state = 'wip';
            $issue->project_id = 1;
            $issue->save();

            \DB::commit();
        } catch (\Exception $e) {
            \Log::Error($e->getTraceAsString());
            \DB::rollback();

            return response()->json('create error', 500);
        }

        return response()->json($issue, 201);
    }

    /**
     *
     * @param  IssueUpdateRequest       $request
     * @return \Illuminate\Http\Response
     */
    public function update(IssueUpdateRequest $request, $id)
    {
        $issue = Issue::find($id);

        if (is_null($issue)) {
            return response()->json('issue is not found', 404);
        }

        \DB::beginTransaction();

        try {
            $issue->fill($request->all());
            $issue->save();

            \DB::commit();
        } catch (\Exception $e) {
            \Log::Error($e->getTraceAsString());
            \DB::rollback();

            return response()->json('create error', 500);
        }

        return response()->json($issue, 200);
    }

    public function destroy($id)
    {
        $issue = Issue::find($id);

        if (is_null($issue)) {
            return response()->json('issue not found', 404);
        }

        \DB::beginTransaction();

        try {
            $issue->delete();

            \DB::commit();
        } catch (\Exception $e) {
            \Log::Error($e->getTraceAsString());
            \DB::rollback();

            return response()->json('destroy error', 500);
        }

        return response()->json(200);
    }
}
