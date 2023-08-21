<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SparePartMaster, App\Models\SparePartTransaction, App\Models\IssueEngineerTransaction, App\User ;

class StockinReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function userStockIn()
    {
        $users = User::where('status',1)->get();
        return view('admin.reports.stockin.create',compact('users'));
    }

    public function userStockInStore(Request $request)
    {
        $user_id = $request->user_id;

        $user = User::where('id',$user_id)->where('status',1)->first();


        $assigned_spare_parts = SparePartMaster::with('spare_part_transaction','user')->where('engineer_id',$user_id)->where('trans_type','=','iss')->where('status',1)->get();

        $all_sp_prts = [];
        if ($assigned_spare_parts) {

            foreach($assigned_spare_parts as $key => $value) {
                    $all_spare_parts = SparePartTransaction::where('spare_part_master_id',$value->id)->where('status',1)->get();
                    // dd($all_spare_parts);

                    $all_spare_parts_to_array = SparePartTransaction::with('spare_part')->where('spare_part_master_id',$value->id)->where('status',1)->get();


                    foreach ($all_spare_parts_to_array as $key1 => $value1) {
                        // array_push($all_sp_prts, $value1['spare_parts_id']);
                        $all_sp_prts[$value1->spare_parts_id]  = $value1;
                    }


              }

              foreach ($all_sp_prts as $key2 => $value2) {

                        $stock_in[$key2] = IssueEngineerTransaction::with('spare_part','user')->where('spare_part_id',$key2)->where('engineer_id',$user_id)->where('status',1)->sum('stock_in');


                        $stock_out[$key2] = IssueEngineerTransaction::with('spare_part','user')->where('spare_part_id',$key2)->where('engineer_id',$user_id)->where('status',1)->sum('stock_out');

                        $stock_in_hand[$key2] = $stock_in[$key2]-$stock_out[$key2];
                    }
              }

              // dd($stock_in_hand);
              return view('admin.reports.stockin.stockin-result',compact('all_sp_prts','stock_in_hand','user'));
    }
}
