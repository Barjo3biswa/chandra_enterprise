<?php

namespace App\Http\Controllers\REST;

use App\Http\Controllers\Controller;
use App\Models\AssignToolKit;
use App\Models\ToolkitRequest;
use Illuminate\Http\Request;
use JWTAuth;
use Validator;

class ToolkitRequestController extends Controller
{
    function new (Request $request) {
        $user = JWTAuth::parseToken()->toUser();
        $request->merge([
            "request_by_id" => $user->id,
        ]);
        $request->merge([
            "client_id" => $request->branch_id,
        ]);
        // \Log::debug($request->all());
        $rules     = ToolkitRequest::$rules;
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                "status"  => false,
                "message" => "Whoops! Looks like you have missed something.",
                "data"    => $validator->errors(),
            ]);
        }
        // Checking wether use have already toolkit assigned or not 
/*         $is_toolkit_available_on_user = AssignToolKit::where("tool_kit_id", $request->get("toolkit_id"))
            ->where("user_id", $user->id)
            ->where("status", 1)
            ->count();
        if(!$is_toolkit_available_on_user){
            return response()->json([
                "status"  => false,
                "message" => "Request toolkit is not assigned yet to you.",
                "data"    => [],
            ]);
        } */
        // end of checking toolkit already assigned
        try {
            $toolkit = ToolkitRequest::create($request->all());
        } catch (\Throwable $th) {
            return response()->json([
                "status"  => false,
                "message" => "Whoops! something went wrong.",
                "data"    => [],
            ]);
        }
        return response()->json([
            "status"  => true,
            "message" => "Request Successfully sent.",
            "data"    => $toolkit->toArray(),
        ]);
    }
}
