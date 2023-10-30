<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    function index(){
        $projects = Project::all();
        return response()->json([
            'state' => true,
            'data' => $projects,
        ], 200);
    }
    function approve(Request $request)
    {
        try {
            $project = Project::find($request->project_id);
            $project->update([
                'state' => 1,
                'note' => ''
            ]);
            return response()->json([
                'state' => true,
                'data' => $project,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'state' => true,
                'data' => $th->getMessage(),
            ], 200);
        }
    }
    function reject(Request $request)
    {
        $validateProject = Validator::make(
            $request->all(),
            [
                'project_id' => 'required|exists:projects,id',
                'rejection_reason' => 'required',
            ]
        );
        if($validateProject->fails()){
            return response()->json([
                'state' => false,
                'errors' => $validateProject->errors()
            ], 401);
        }else{
            $project = Project::find($request->project_id);
            $project->update([
                'state'=>2,
                'note'=> $request->rejection_reason
            ]);
            return response()->json([
                'state' => false,
                'data' => $project
            ], 200);
        }

    }
}
