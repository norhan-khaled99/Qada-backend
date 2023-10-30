<?php

namespace App\Http\Controllers\Bussiness;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    function create(Request $request)
    {
        $comment =  Comment::create([
            'project_id' => $request->project_id,
            'user_id' => Auth::user()->id,
            'comment' => $request->comment,
        ]);
        return response()->json([
            'state' => true,
            'data' => $comment,
        ], 200);
    }
    function edit(Request $request)
    {
        $comment = Comment::find($request->comment_id);
        if($comment && $comment->user_id == Auth::user()->id){
            $comment->update([
                'comment'=>$request->comment
            ]);
            return response()->json([
                'state' => true,
                'msg' => 'تم اضافة تعليقك بنجاح',
                'data' => $comment,
            ], 200);
        }else{
            return response()->json([
                'state' => true,
                'data' => $comment,
            ], 200);
        }
    }
    function delete(Request $request)
    {
        $comment = Comment::find($request->comment_id);
        if($comment && $comment->user_id == Auth::user()->id){
            $comment->delete();
            return response()->json([
                'state' => true,
            ], 200);
        }else{
            return response()->json([
                'state' => false,
            ], 302);
        }
    }
}
