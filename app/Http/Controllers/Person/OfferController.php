<?php

namespace App\Http\Controllers\Person;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Offer;
use App\Models\Project;
use App\Models\Stage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OfferController extends Controller
{
    function placeOffer(Request $request)
    {
        $validateOffer = Validator::make(
            $request->all(),
            [
                'project_id' => 'required|exists:projects,id',
                'offer_duration' => 'required',
                'offer_details' => 'required',
                'offer_value' => 'required',
                'offer_stages' => 'required',
            ]
        );
        $user = $request->user();
        if ($validateOffer->fails()) {
            return response()->json([
                'state' => false,
                'errors' => $validateOffer->errors(),
            ], 302);
        } else {
            $project = Project::find($request->project_id);
            $hasOffer = Offer::where('project_id', $project->id)->where('user_id', $request->user()->id)->first();
            if ($hasOffer) {
                return response()->json([
                    'state' => false,
                    'msg' => 'لقد قمت بتقديم عرض من قبل لهذا المشروع يرجي متابعة حالة العرض من لوحة التحكم',
                ], 302);
            } else {
                if ($project->state == 0) {
                    return response()->json([
                        'state' => false,
                        'msg' => 'برجاء الانتظار , المشروع المطلوب مازال قيد المراجعة',
                    ], 302);
                } elseif ($project->last_offers_date < Carbon::now()->format('Y-m-d')) {
                    return response()->json([
                        'state' => false,
                        'msg' => 'عذرا , لقد انتهت فترة قبول عروض التنفيذ لهذا المشروع',
                    ], 302);
                } else {
                    $input = $request->all();
                    $input['user_id'] = $request->user()->id;
                    $offer = Offer::create($input);
                    return response()->json([
                        'state' => true,
                        'msg' => 'شكرا لأهتمامك بتنفيذ المشروع , تم تقديم عرضك بنجاح',
                    ], 200);
                }
            }
        }
    }
    function updateOffer(Request $request)
    {
        $offer = Offer::find($request->offer_id);
        if ($offer->state != 0) {
            if ($offer->user_id !== $request->user()->id) {
                return response()->json([
                    'state' => false,
                    'errors' => 'لا تملك صلاحية تعديل العروض التي يقدمها أخرون',
                ], 302);
            } else {
                $input = $request->all();
                $validateOffer = Validator::make(
                    $request->all(),
                    [
                        'offer_period' => 'required',
                        'offer_details' => 'required',
                        'offer_value' => 'required',
                        'offer_stages' => 'required',
                    ]
                );
                if ($validateOffer->fails()) {
                    return response()->json([
                        'state' => true,
                        'msg' => 'يرجي أستكمال جميع بيانات العرض للتعديل',
                    ], 302);
                } else {
                    $input['project_id'] = $offer->project_id;
                    $offer->update($input);
                    return response()->json([
                        'state' => true,
                        'msg' => 'تم تعديل العرض بنجاح',
                    ], 200);
                }
            }
        } else {
            return response()->json([
                'state' => false,
                'msg' => 'لا يمكنك تعديل عرض تم قبولة مسبقا في مشروع',
            ], 302);
        }
    }
    function setStages(Request $request)
    {
        $offer = Offer::find($request->offer_id);
        $project = Project::find($offer->project_id);
        $contract = Contract::where('offer_id', $offer->id)->first();
        if ($contract->contract_state == 0) {
            if ($contract->stages->count() < $contract->contract_stages) {
                $input = $request->all();
                $input['contract_id'] = $contract->id;
                $input['stage_title'] = $request->stage_title;
                $input['stage_start_date'] = Carbon::parse($request->stage_start_date)->format('Y-m-d');
                $input['stage_due_date'] = Carbon::parse($request->stage_due_date)->format('Y-m-d');
                $stage = Stage::create($input);
                if ($contract->stages->count() == $contract->contract_stages) {
                    $contract->update([
                        'contract_state' => 1
                    ]);
                }
            } else if ($contract->stages->count() == $contract->contract_stages) {
                
                $contract->update([
                    'contract_state' => 1
                ]);
                $contract->update([
                    'contract_current_stage' => $contract->stages->first()->id
                ]);
                return response()->json([
                    'state' => true,
                    'msg' => 'تم أنشاء مراحل العقد بنجاح وبدأ العقد',
                ], 302);
            } else {
                return response()->json([
                    'state' => false,
                    'msg' => 'لا يمكنك انشاء مرحلة أضافية للمشروع',
                ], 302);
            }
        } else {
            return response()->json([
                'state' => false,
                'msg' => 'لا يمكن التعديل على العقد القائم بالفعل',
            ], 302);
        }
    }
}
