<?php

namespace App\Http\Controllers\REST;

use App\Http\Controllers\Controller;
use App\Models\UserTrackLocation;
use Illuminate\Http\Request;
use JWTAuth;
use Validator;

class LocationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser();
        // dd($user);
        if ($user) {
            $json_arr = array();

            $user_location              = new UserTrackLocation();
            $user_location->engineer_id = $user->id;
            $user_location->latitude    = $request->latitude;
            $user_location->longitude   = $request->longitude;
            $user_location->location    = $request->location;
            $user_location->track_date  = date('Y-m-d H:i:s');

            if ($request->latitude != null && $request->longitude != null && $request->location != null) {
                if ($user_location->save()) {
                    $json_arr['status']        = true;
                    $json_arr['user_location'] = $user_location;
                    $json_arr['message']       = 'User location tracking details saved successfully';
                } else {
                    $json_arr['status']        = false;
                    $json_arr['user_location'] = [];
                    $json_arr['message']       = 'Please fix the error and try again';
                }
            } else {
                $json_arr['status']        = false;
                $json_arr['user_location'] = [];
                $json_arr['message']       = 'Location not found';
            }

            return response()->json($json_arr);} else {
            return response()->json([
                'status' => false,

            ]);
        }
    }
    public function bulkLocations(Request $request)
    {
        $rules     = UserTrackLocation::$bulk_insert_rules;
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                "status"  => false,
                "message" => "Validation error.",
                "data"    => $validator->errors(),
            ]);
        }
        $prepared_data = $this->prepareData($request->all());
        try {
            $inserted      = UserTrackLocation::insert($prepared_data);
        } catch (\Throwable $th) {
            return response()->json([
                "status"  => false,
                "message" => "Location not inserted.",
                "data"    => $th,
            ]);
        }
        return response()->json([
            "status"  => true,
            "message" => "Location Updated.",
            "data"    => [],
        ]);
        return $prepared_data;
    }

    private function prepareData(array $values)
    {
        $created_at    = $deleted_at    = date("Y-m-d H:i:s");
        $return_values = [];
        foreach ($values["locations"] as $index => $item) {
            $item["created_at"] = $created_at;
            $item["updated_at"] = $deleted_at;
            $item["status"]     = 1;
            $return_values[]    = $item;
        }
        return $return_values;
    }
}
