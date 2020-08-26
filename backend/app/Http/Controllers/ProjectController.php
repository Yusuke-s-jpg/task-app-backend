<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();

        return response()->json($projects, 200);
    }
}
