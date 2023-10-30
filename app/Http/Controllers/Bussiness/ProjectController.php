<?php

namespace App\Http\Controllers\Bussiness;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    function index(Request $request)
    {
        $projects = Project::where('user_id', $request->user()->id)->get();
        return response()->json([
            'state' => true,
            'data' => $projects,
        ], 200);
    }
    function create(Request $request)
    {
        $input = $request->all();
        $validateProject = Validator::make(
            $request->all(),
            [
                'project_title' => 'required',
                'project_details' => 'required',
                'space' => 'required',
                'service_category' => 'required',
                'offer_choosing_date' => 'required',
                'project_days_limit' => 'required',
                'last_offers_date' => 'required',
                'delivery_date' => 'required',
                'area' => 'required',
                'city' => 'required',
                'request_qty_tables' => 'required',
                'request_engs' => 'required',
                'title_deed' => 'required',
                'owner_id' => 'required',
                'other_files' => 'nullable',
            ]
        );
        $input['offer_choosing_date'] = Carbon::parse($request->offer_choosing_date)->format('Y-m-d');
        $input['last_offers_date'] = Carbon::parse($request->last_offers_date)->format('Y-m-d');
        $input['delivery_date'] = Carbon::parse($request->delivery_date)->format('Y-m-d');
        if ($validateProject->fails()) {
            return response()->json([
                'state' => false,
                'data' => 'validation error',
                'errors' => $validateProject->errors()
            ], 401);
        } else {
            $input['user_id'] = $request->user()->id;
            $input['state'] = 0;
            $other_files = [];
            $project = Project::create($input);
            // Upload Project Title Deed
            $title_deed = $request->file('title_deed');
            $titleDeed = $title_deed->getClientOriginalName();
            $title_deed->move("projects/$project->id", $titleDeed);

            // Upload Owner ID
            $owner_id = $request->file('owner_id');
            $ownerId = $owner_id->getClientOriginalName();
            $owner_id->move("projects/$project->id", $ownerId);

            // Upload Project Files
            if (isset($request->other_files)) {
                foreach ($request->other_files as $file) {
                    $fileName = $file->getClientOriginalName();
                    $file->move("projects/$project->id/other_files", $fileName);
                    array_push($other_files, $fileName);
                }
            }
            $input['other_files'] = $other_files;
            $project->update([
                'title_deed'=>$titleDeed,
                'owner_id'=>$ownerId,
                'other_files'=>$other_files,
            ]);
            return response()->json([
                'state' => true,
                'data' => $project,
            ], 200);
        }
    }
    function newfile(Request $request){
        $project = Project::find($request->project_id);
        $other_files = $project->other_files;
        foreach ($request->other_files as $file) {
            $fileName = $file->getClientOriginalName();
            $file->move("projects/$project->id/other_files", $fileName);
            array_push($other_files, $fileName);
        }

        $allFiles = $other_files;
        $project->update([
            'other_files'=>$allFiles
        ]);
        return response()->json([
            'state' => true,
            'data' => $project,
        ], 200);

    }
    function edit(Request $request, $project_id)
    {
        $input = $request->all();
        $project = Project::find($project_id);
        if ($request->user()->id == $project->user_id) {
            $project->update($input);
            return response()->json([
                'state' => true,
                'data' => $project,
            ], 200);
        } else {
            return response()->json([
                'state' => false,
                'data' => 'You Can\'t Edit This Project',
            ], 401);
        }
    }
    function delete(Request $request, $project_id)
    {
        $input = $request->all();
        $project = Project::find($project_id);
        if ($request->user()->id == $project->user_id) {
            $project->update($input);
            return response()->json([
                'state' => true,
                'data' => $project,
            ], 200);
        } else {
            return response()->json([
                'state' => false,
                'data' => 'You Can\'t Delete This Project',
            ], 401);
        }
    }
}
