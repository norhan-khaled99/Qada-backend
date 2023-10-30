<?php

namespace App\Http\Controllers\Bussiness;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Project;
use App\Models\Stage;
use Illuminate\Http\Request;

class StageController extends Controller
{
    function index(Request $request)
    {
        $projects = Project::where('state', 1)->where('user_id', $request->user()->id)->without('offers')->get();
        // $contracts = Contract::where('project_owner_id', $request->user()->id)->where('contract_state', 1)->get();
        // $stages = [];
        // foreach ($projects as $project) {
        //     $contracts = Contract::where('project_id', $project->id)->first();
        //     foreach ($contracts as $contract) {
        //         return response()->json([
        //             'state' => true,
        //             'msg' => $contracts,
        //         ], 200);
        //     }
        // }
        foreach ($projects as $project) {
            return response()->json([
                'state' => true,
                'msg' => $project->contract,
            ], 200);
        }
    }
    function approveStage(Request $request)
    {
        $stage = Stage::find($request->stage_id);
        $contract = $stage->contract;
        return response()->json([
            'state' => true,
            'msg' => 'تم مراجعة وتقييم المرحلة بنجاح',
        ], 200);
    }
}
