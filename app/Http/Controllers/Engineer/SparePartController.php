<?php

namespace App\Http\Controllers\Engineer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth,Session,DB,Crypt,Validator,Excel;

use App\Models\SparePartMaster, App\Models\SparePartTransaction, App\Models\SparePart, App\Models\IssueEngineer, App\Models\IssueEngineerTransaction;

class SparePartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $assigned_spare_parts = SparePartMaster::with('spare_part_transaction','user')->where('engineer_id',Auth::user()->id)->where('trans_type','=','iss')->where('status',1)->get();

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

                    // dd($all_sp_prts);

                    // dd($stock_in_hand);
      
              }

              foreach ($all_sp_prts as $spare_part_id_key => $value2) {

                        $stock_in[$spare_part_id_key] = IssueEngineerTransaction::with('spare_part','user')->where('spare_part_id',$spare_part_id_key)->where('engineer_id',Auth::user()->id)->where('status',1)->sum('stock_in');


                        $stock_out[$spare_part_id_key] = IssueEngineerTransaction::with('spare_part','user')->where('spare_part_id',$spare_part_id_key)->where('engineer_id',Auth::user()->id)->where('status',1)->sum('stock_out');

                        $stock_in_hand[$spare_part_id_key] = $stock_in[$spare_part_id_key]-$stock_out[$spare_part_id_key];
                    }
        }

// dd($all_sp_prts);

        return view('engineer.spare-part.index',compact('assigned_spare_parts','all_spare_parts','stock_in_hand', "all_sp_prts"));
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


    public function export(Request $request)
    {
      $assigned_spare_parts = SparePartMaster::with('spare_part_transaction','user')->where('engineer_id',Auth::user()->id)->where('trans_type','=','iss')->where('status',1)->get();

        if ($assigned_spare_parts) {

            foreach($assigned_spare_parts as $key => $value) {
                    $all_spare_parts = SparePartTransaction::where('spare_part_master_id',$value->id)->where('status',1)->get();

                    $all_spare_parts_to_array = SparePartTransaction::where('spare_part_master_id',$value->id)->where('status',1)->get()->toArray();

                    $all_sp_prts = [];
                    foreach ($all_spare_parts_to_array as $key1 => $value1) {
                        array_push($all_sp_prts, $value1['spare_parts_id']);
                    }

                    foreach ($all_sp_prts as $key2 => $value2) {

                        $stock_in[] = IssueEngineerTransaction::with('spare_part','user')->where('spare_part_id',$value2)->where('engineer_id',Auth::user()->id)->where('status',1)->sum('stock_in');


                        $stock_out[] = IssueEngineerTransaction::with('spare_part','user')->where('spare_part_id',$value2)->where('engineer_id',Auth::user()->id)->where('status',1)->sum('stock_out');

                        $stock_in_hand[] = $stock_in[$key2]-$stock_out[$key2];
                    }
      
              }

        }
          
        try{
            Excel::create('SpartPartStockDetails '.date('dmyHis'), function( $excel) use($all_spare_parts, $stock_in_hand){
                $excel->sheet('SpartPartStock-Details ', function($sheet) use($all_spare_parts, $stock_in_hand){
                  $sheet->setTitle('SpartPartStock-Details');

                  $sheet->cells('A1:Q1', function($cells) {
                    $cells->setFontWeight('bold');
                  });
                  
                  $arr = [];
                    foreach($all_spare_parts->chunk(500) as $res):
                        foreach( $res as $k => $v) {
                            //dd($v);
                            $arr[$k]['Sl No']                           = $k+1;
                            $arr[$k]['Spare Part Name']                 = $v->spare_part->name;
                            $arr[$k]['Part No']                         = $v->spare_part->part_no;
                            $arr[$k]['Brand']                           = $v->spare_part->brand;
                            $arr[$k]['Group']                           = $v->spare_part->group->name;
                            if ( $v->spare_part->company_id != null ) {
                                $arr[$k]['Company']                         = $v->spare_part->company->name;
                            }else{
                                $arr[$k]['Company']                         = '';
                            }
                            if ($stock_in_hand[$k] == 0) {
                                $arr[$k]['Stock In Hand']                   = '0';
                            }else
                            {
                                $arr[$k]['Stock In Hand']                   = $stock_in_hand[$k];
                            }
                          
                        }
                  
                     endforeach;
                    $sheet->fromArray($arr, null, 'A1', false, true);
                  
                });
            })->download('xlsx');
        }
        catch(Exception $e)
        {
            Session::flash('error','Unable to export !');
            return Redirect::back();
        }

        Session::flash('success','Successfully exported client details');
        return Redirect::route('issued-spare-parts-details');
    }
}
