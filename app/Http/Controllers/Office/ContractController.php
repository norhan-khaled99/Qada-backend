<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Offer;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContractController extends Controller
{
    function create(Request $request)
    {
        $validateContract = Validator::make(
            $request->all(),
            [
                'project_id' => 'required|email',
                'offer_id' => 'required',
                'penalty' => 'required',
                'offer_value' => 'required',
            ]
        );
        $input = $request->all();
        $offer = Offer::find($request->offer_id);
        $project = Project::find($request->project_id);
        $input['project_owner_id'] = $request->user()->id;
        $input['offer_owner_id'] = $offer->user_id;
        $input['contract_value'] = $offer->offer_value;
        $input['contract_duration'] = $offer->offer_duration;
        $input['contract_stages'] = $offer->offer_stages;
        $input['contract_state'] = 0;
        $input['penality'] = 10;
        $input['offer_id'] = $request->offer_id;
        $contract = Contract::create($input);
        $project->update([
            'offer_id' => $offer->id
        ]);
        $offer->update([
            'state' => 1
        ]);
        return response()->json([
            'state' => true,
            'msg' => 'تم التعاقد بنجاح وفي انتظار بداية العقد بعد الانتهاء من وضع المراحل المتفق عليها من مقدم العرض'
        ], 200);
    }
}
