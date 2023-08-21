<?php

namespace App\Http\Controllers\REST;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth,Session,DB,Crypt,Validator,Excel,JWTAuth;

use App\User, App\Models\AssignToolKit;

class ToolKitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser(); 

        if($user){
        $json_arr = array();

        $user_id = $request->user_id;

        $assignedtoolkits = AssignToolKit::with('toolkit')->where([['user_id',$user_id],['status',1]])->get();

        if (isset($assignedtoolkits)) {
          $json_arr['status'] = true;
          $json_arr['assignedtoolkits'] = $assignedtoolkits;
        }else{
          $json_arr['status'] = false;
          $json_arr['assignedtoolkits'] = [];
        }

        return response()->json($json_arr);}else{
          return response()->json([
            'status' => false,

          ]);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
