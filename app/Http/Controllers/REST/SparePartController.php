<?php

namespace App\Http\Controllers\REST;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth,Session,DB,Crypt,Validator,Excel,JWTAuth;

use App\Models\SparePartMaster, App\Models\SparePartTransaction, App\Models\SparePart, App\Models\IssueEngineer, App\Models\IssueEngineerTransaction;

class SparePartController extends Controller
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

        $assigned_spare_parts = SparePartMaster::with('spare_part','spare_part_transaction','spare_part_transaction.spare_part')->where('engineer_id',$user_id)->where('trans_type','=','iss')->where('status',1)->get();

        // if (isset($assigned_spare_parts)) {
        //   $json_arr['status'] = true;
        //   $json_arr['assigned_spare_parts'] = $assigned_spare_parts;
        // }else{
        //   $json_arr['status'] = false;
        //   $json_arr['assigned_spare_parts'] = [];
        // }

        $all_sp_prts = [];
        if ($assigned_spare_parts) {

            foreach($assigned_spare_parts as $key => $value) {
                    $all_spare_parts = SparePartTransaction::with('spare_part')->where('spare_part_master_id',$value->id)->where('status',1)->get();
                    // dd($all_spare_parts);

                    if (isset($all_spare_parts)) {
                      $json_arr['status'] = true;
                      $json_arr['all_spare_parts'] = $all_spare_parts;
                    }else{
                      $json_arr['status'] = false;
                      $json_arr['all_spare_parts'] = [];
                    }

                    $all_spare_parts_to_array = SparePartTransaction::with('spare_part')->where('spare_part_master_id',$value->id)->where('status',1)->get();


                    foreach ($all_spare_parts_to_array as $key1 => $value1) {
                        // array_push($all_sp_prts, $value1['spare_parts_id']);
                        $all_sp_prts[$value1->spare_parts_id]  = $value1;
                    }

                    // dd($all_sp_prts);

                    // dd($stock_in_hand);
      
              }

              foreach ($all_sp_prts as $spare_part_id_key => $value2) {

                $sp_name = SparePart::where('id',$value2->spare_parts_id)->first()->name;
                // dd($sp_name);

                        $stock_in[$spare_part_id_key] = IssueEngineerTransaction::with('spare_part','user')->where('spare_part_id',$spare_part_id_key)->where('engineer_id',$user_id)->where('status',1)->sum('stock_in');


                        $stock_out[$spare_part_id_key] = IssueEngineerTransaction::with('spare_part','user')->where('spare_part_id',$spare_part_id_key)->where('engineer_id',$user_id)->where('status',1)->sum('stock_out');

                        $stock_in_hand[$spare_part_id_key] = $stock_in[$spare_part_id_key]-$stock_out[$spare_part_id_key];
                    }
        }

        if (isset($stock_in_hand)) {
          $json_arr['status'] = true;
          $json_arr['stock_in_hand'] = $this->organiseInArray($stock_in_hand);
        }else{
          $json_arr['status'] = false;
          $json_arr['stock_in_hand'] = [];
        }
		if(!isset($json_arr['all_spare_parts'])){
			$json_arr['all_spare_parts'] = [];
		}     
        if(!$json_arr['status']){
            $json_arr['message'] = "Stock Not found.";
        }

        return response()->json($json_arr);}else{
          return response()->json([
            'status' => false,
            'message' =>"Stock Not found."

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
    private function organiseInArray($array_list)
    {
        $return_array = [];
        if(!$array_list){
            return [];
        }
        foreach ($array_list as $id => $quantity) {
            $return_array[] = [
                "id"        => $id,
                "quantity"  => $quantity
            ];
        }
        return $return_array;
    }
}
