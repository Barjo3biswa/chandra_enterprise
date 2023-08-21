<?php

namespace App\Http\Controllers\REST;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;

use Auth,Session,DB,Crypt,Validator,Excel;
use App\Models\Dsr\DailyServiceReport, App\Models\Dsr\DailyServiceReportTransaction, App\Models\Client, App\Models\ClientAmcMaster, App\Models\Complaint, App\Models\Assign\AssignProductToClient, App\Models\Product, App\Models\SparePartMaster, App\Models\SparePartTransaction, App\Models\SparePart, App\Models\ComplaintTransaction, App\Models\IssueEngineer, App\Models\IssueEngineerTransaction, App\Models\Assign\AssignEngineer, App\Models\ClientAmcProduct;

class DsrController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser(); 
        // dd($user);
        $page = $request->get("page");
        if($user ){

        $json_arr = array();

        $dsr_reports = DailyServiceReport::with('dsr_products.product','dsr_products.group', 'dsr_transaction','client','product','engineer','complaint.assigned_engineers')->where('entry_by',$user->id);
        \Log::info($request->all());
        // Filter Added API
        $dsr_reports->when(request("client_name"), function($query){
            return $query->whereHas("client", function($sub_sub_query){
                return $sub_sub_query->where("name", "LIKE", "%".request("client_name")."%");
            });
        });
        $dsr_reports->when(request("branch_name"), function($query){
            return $query->whereHas("client", function($sub_sub_query){
                return $sub_sub_query->where("branch_name", "LIKE", "%".request("branch_name")."%");
            });
        });
        $dsr_reports->when(request("date_from"), function($query){
            return $query->whereDate("entry_datetime", ">=", request("date_from"));
        });
        $dsr_reports->when(request("date_to"), function($query){
            return $query->whereDate("entry_datetime", "<=", request("date_to"));
        });
        $dsr_reports->when(request("entry_date"), function($query){
            return $query->whereDate("entry_datetime", "=", request("entry_date"));
        });
        $dsr_reports->when(request("type"), function($query){
            return $query->where("maintenance_type", "=", request("type"));
        });
        // End of Filter Addition
       /*  $take = 10;
        if ($page) {
            $skip = $page - 1;
            $dsr_reports = $dsr_reports->take($take)->skip($skip * $take);
        }else{
            $dsr_reports = $dsr_reports->take($take);
        } */
        $dsr_reports = $dsr_reports->where('status',1)->orderBy('id','desc')->get();

        // return $dsr_reports->first()->complaint->assigned_engineers;
        if($dsr_reports){
            $dsr_reports->map(function($item){
                if($item->complaint){
                    $item->complaint->complaint_assigned_date = ($item->complaint ? 
                        ($item->complaint->assigned_engineers ? $item->complaint->assigned_engineers->first()->created_at->format("Y-m-d") : dateFormat($item->complaint->complaint_entry_date, "Y-m-d"))
                        : ""
                    );
                }
            });
        }
        if ($dsr_reports->count()) {
            $json_arr['status'] = true;
            $json_arr['dsr_reports'] = $dsr_reports;
        }else{
            $json_arr['status'] = false;
            $json_arr['dsr_reports'] = [];
        }


        $client = [];
        foreach ($dsr_reports as $key => $client_name) {
            array_push($client, $client_name['client_id']);
        }

        if (isset($client)) {
           
            $assigned_products = AssignProductToClient::with('client','product')->whereIn('client_id',$client)->where('status',1)->get();
  
            // $json_arr['status'] = true;
            $json_arr['assigned_products'] = $assigned_products;
        }else{
            // $json_arr['status'] = false;
            $json_arr['assigned_products'] = [];
        }


    $assigned_spare_parts = SparePartMaster::with('spare_part_transaction','user','spare_part','spare_part_transaction.spare_part')->where('engineer_id',$user->id)->where('trans_type','=','iss')->where('status',1)->get();


    $all_sp_prts = [];
    if (isset($assigned_spare_parts)) {

      foreach($assigned_spare_parts as $key => $value) {
        $all_spare_parts = SparePartTransaction::where('spare_part_master_id',$value->id)->where('status',1)->get();

        $all_spare_parts_to_array = SparePartTransaction::with('spare_part')->where('spare_part_master_id',$value->id)->where('status',1)->get()->toArray();


        foreach ($all_spare_parts_to_array as $key1 => $value1) {
          array_push($all_sp_prts, $value1);
        }

     }
      // $json_arr['status'] = true;
      $json_arr['assigned_spare_parts'] = $all_sp_prts;
    }

    else{
      // $json_arr['status'] = false;
      $json_arr['assigned_spare_parts'] = [];
    }


        //  return response()->json([
        //     'status' => true, 
        //     'data'=> [
        //         'dsr_reports' => $dsr_reports,
        //         'assigned_products' => $assigned_products
        //         ]
        //     ]);
        //      }else{
        //         return response()->json([
        //         'success' => false
        //     ]);
        // }
            if(isset($json_arr["status"]) && !$json_arr["status"]){
                $json_arr["message"] = "No data found.";
            }
        return response()->json($json_arr);}else{
            return response()->json([
                'status' => false
            ]);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser(); 
        // dd($user);
        if($user ){

        $json_arr = array();

        $client_id          = "";
        $maintenance_type   = "";
        $branch             = "";
        $brach_data         = [];
        $client_contact_person_name          = "";
        $client_contact_person_ph_no          = "";
        if($request->get("client_id")){
            $client_id = $request->get("client_id");
        }
        if($request->get("maintenance_type")){
            $maintenance_type = $request->get("maintenance_type");
        }
        if($request->get("branch")){
            $branch = $request->get("branch");
        }

        $assgn_eng = AssignEngineer::with('client','user','zone')->where('engineer_id',$user->id)->where('status',1)->get();

        $unique_clients = $assgn_eng->unique("client_id")->values()->all();

        if (isset($unique_clients)) {
            $json_arr['status'] = true;
            $json_arr['clients'] = $unique_clients;
        }else{
            $json_arr['status'] = false;
            $json_arr['clients'] = [];
        }

        if ($client_id != "" && $branch != "") {
            $client_contact_person_details = Client::where('name',$client_id)->where('branch_name',$branch)->where('status',1)->first();

            $json_arr['status'] = true;
            $json_arr['client_contact_person_details'] = $client_contact_person_details;

        }else{
            $json_arr['status'] = false;
            $json_arr['client_contact_person_details'] = [];
        }

        

        $last_dsr_report = DailyServiceReport::with('dsr_transaction','client','product','engineer','complaint')->where('entry_by',$user->id)->where('status',1)->orderBy('entry_datetime','desc')->first();

        if (isset($last_dsr_report)) {
            $json_arr['status'] = true;
            $json_arr['last_dsr_report'] = $last_dsr_report;
        }else{
            $json_arr['status'] = false;
            $json_arr['last_dsr_report'] = [];
        }

        $assigned_spare_parts = SparePartMaster::with('spare_part_transaction','user','spare_part')->where('engineer_id',$user->id)->where('trans_type','=','iss')->where('status',1)->get();

        $all_sp_prts = [];
        if ($assigned_spare_parts) {

            foreach($assigned_spare_parts as $key => $value) {
                   
                    $all_spare_parts_to_array = SparePartTransaction::with('spare_part')->where('spare_part_master_id',$value->id)->where('status',1)->get();


                    foreach ($all_spare_parts_to_array as $key1 => $value1) {
                        // array_push($all_sp_prts, $value1['spare_parts_id']);
                        $all_sp_prts[$value1->spare_parts_id]  = $value1;
                    }
               }
            $json_arr['status'] = true;
            $json_arr['all_spare_parts'] = $all_sp_prts;
        }else{
            $json_arr['status'] = false;
            $json_arr['all_spare_parts'] = [];
        }
      
        $today = date('d-m-Y');
        $json_arr['status'] = true;
        $json_arr['today'] = $today;

        $client_names = Client::select('id')->where('name',$client_id)->where('branch_name',$branch)->where('status',1)->get()->toArray();
            
        $client = [];
        foreach ($client_names as $key => $client_name) {
            array_push($client, $client_name['id']);
        }

        if (isset($client)) {
           
            $assigned_products = AssignProductToClient::with('client','product')->whereIn('client_id',$client)->where('status',1)->get();
  
            $json_arr['status'] = true;
            $json_arr['assigned_products'] = $assigned_products;
        }else{
            $json_arr['status'] = false;
            $json_arr['assigned_products'] = [];
        }   
            
        return response()->json($json_arr);
        }else{
            return response()->json([
                'status' => false
            ], 500);
        }
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
    public function validateScrNo(Request $request)
    {
        $rules = [
            "scr_no"    => "required"
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json([
                "status"    => false,
                "message"   => "scr_no field is required.",
            ]);
        }
        $find_scr_no = DailyServiceReport::where("scr_no", $request->get('scr_no'));
        if($request->get('id')){
            $find_scr_no = $find_scr_no->where("id", "!=", $request->get("id"));
        }
        $find_scr_no = $find_scr_no->count();
        if($find_scr_no){
            return response()->json([
                "status"    => false,
                "message"   => "Scr no already taken.",
            ]);
        }
        return response()->json([
            "status"    => true,
            "message"   => "Scr no is available.",
        ]);
    }
}
