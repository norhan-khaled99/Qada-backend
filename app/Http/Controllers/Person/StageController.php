<?php

namespace App\Http\Controllers\Person;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StageController extends Controller
{
    function submitstage(Request $request)
    {
        $stage = Stage::find($request->stage_id);
        //dd($stage);
        if ($stage->state == 0) { // جاري العمل عليها
            $stage->update([
                'state' => 1,
                'submit_to_review' => 1,
                'submit_to_review_date' => Carbon::now()->format('Y-m-d'),
            ]);
            return response()->json([
                'state' => true,
                'msg' => 'تم أرسال المرحلة للمراجعة',
            ], 200);
        } else if ($stage->state == 1) { // تم رفعها من قبل وفي انتظار المراجعة
            return response()->json([
                'state' => false,
                'msg' => 'هذه المرحلة قيد المراجعة يرجي الانتظار',
            ], 302);
        } else if ($stage->state == 2) { // تم استلام المرحلة من صاحب المشروع والموافقة عليها من قبل
            return response()->json([
                'state' => false,
                'msg' => 'تم تقديم هذة المرحلة للمراجعة من قبل يرجى الانتظار',
            ], 302);
        } else if ($stage->state == 3) { // أعادة تقديم المرحلة للتتقييم
            $stage->update([
                'state' => 1,
                'submit_to_review' => 1,
                'submit_to_review_date' => Carbon::now()->format('Y-m-d'),
            ]);
            return response()->json([
                'state' => true,
                'msg' => 'تم أرسال المرحلة لاعادة التقييم',
            ], 200);
        }else if ($stage->state == 4) { // أعادة تقديم المرحلة للتقييم
            return response()->json([
                'state' => false,
                'msg' => 'تم الموافقة على هذة المرحلة من قبل بالفعل',
            ], 302);
        }
    }
}
