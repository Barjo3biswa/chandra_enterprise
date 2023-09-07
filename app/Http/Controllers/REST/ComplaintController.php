<?php

namespace App\Http\Controllers\REST;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB,Crypt,Session,Auth,Validator;
use JWTAuth;
use Config;
use App\Models\Dsr\DailyServiceReportProduct;

use App\Models\Complaint, App\Models\ComplaintMaster, App\Models\ComplaintTransaction, App\Models\Client, App\Models\Group, App\Models\Product, App\Models\Zone, App\Models\Assign\AssignEngineer, App\User, App\Models\Assign\AssignProductToClient, App\Models\Email, App\Models\SparePartMaster, App\Models\SparePartTransaction, App\Models\Dsr\DailyServiceReport, App\Models\IssueEngineer, App\Models\IssueEngineerTransaction, App\Models\Dsr\DailyServiceReportTransaction;

class ComplaintController extends Controller
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
        if($user ){ 

        $clients = Client::where('status',1)->groupBy('name')->get();
        $zones = Zone::where('status',1)->get();
        $groups = Group::where('status',1)->get();
        $c_masters = ComplaintMaster::where('status',1)->orderBy('id','desc')->get();
        $products = Product::where('status',1)->groupBy('name')->get();


        $complaints = Complaint::with('client', 'group','product','comp_master','user', "assigned_engineers.engineer");
            if($request->from_date) {
                $complaints = $complaints->whereDate('complaint_entry_date', '>=', date('Y-m-d', strtotime($request->from_date)));
            }

            if($request->to_date) {
                $complaints = $complaints->whereDate('complaint_entry_date', '<=', date('Y-m-d', strtotime($request->to_date)));
            }

            if($request->zone_id) {

                $z_id = Client::where('zone_id',$request->zone_id)->first();

                if($z_id){
                   $complaints = $complaints->where('client_id','like','%'.$z_id->id.'%'); 
                }

                
            }

           
            if($request->client_id) {

                $client_names = Client::select('id')->where('name','like','%'.$request->client_id.'%')->where('status',1)->get()->toArray();
                // dd($client_name);
                $client = [];
                foreach ($client_names as $key => $client_name) {
                    array_push($client, $client_name['id']);
                }

                foreach ($client_name as $key => $value) {
                   
                    $complaints = $complaints->whereIn('client_id',$client);
                    
                }
                // dd($complaints);

             }

            

            if($request->branch) {
                $branch = Client::where('branch_name','like','%'.$request->branch.'%')->first()->id;

                $complaints = $complaints->where('client_id','like','%'.$branch.'%');
            }

            if($request->complaint_no) {
                $complaints = $complaints->where('complaint_no','like','%'.$request->complaint_no.'%');
            }

            if($request->priority) {
                $complaints = $complaints->where('priority','like','%'.$request->priority.'%');
            }

            if ($request->complaint_status) {
                $complaints = $complaints->where('complaint_status','like','%'.$request->complaint_status.'%');
            }

            if($request->group_id) {
                $complaints = $complaints->where('group_id','like','%'.$request->group_id.'%');
            }

            if($request->complaint_master_id) {
                $complaints = $complaints->where('complaint_master_id','like','%'.$request->complaint_master_id.'%');
            }

            if($request->product_id) {
                // dd($request->product_id);
                $complaints = $complaints->where('product_id','like','%'.$request->product_id.'%');
            }

            // old query
            // $results = $complaints->where('assigned_to',$user->id)->where('complaint_status','!=',3);
            $complaints = $complaints->where(function($query) use ($user){
                return $query->where('assigned_to', $user->id)
                    ->orWhereHas("assigned_engineers", function($query_sub) use ($user){
                        return $query_sub->where("engineer_id", $user->id);
                    });
            })
            ->where('complaint_status','!=',3);
            $results = $complaints->orderBy('complaint_entry_date', 'DESC')->where('status',1)->get();
            return response()->json([
            'status' => true, 
            'data'=> [
                'results' => $results
                ]
            ]);
             }else{
                return response()->json([
                'success' => false
            ]);
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
        \Log::info($request->all());
        // return $request->all();
        $user = JWTAuth::parseToken()->toUser(); 
        // dd($user);
        if($user ){
            /*$rules = [
                "scr_no"    => "required|unique:daily_service_reports,scr_no"
            ];
            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
                return response()->json([
                    "status"    => false,
                    "message"   => "Scr No Already Exists."
                ]);
            } */
        $json_arr = array();
      
        $today = date("Y-m-d H:i:s");
        $maintenance_type = $request->maintenance_type;
        $client_name = $request->client_id;
        $branch = $request->branch;
        $client_id = Client::where('branch_name',$branch)->where('name',$client_name)->first()->id;
        // dd($client_id);
        
        DB::beginTransaction();
        $dsr = new DailyServiceReport();
        try {
        $products = $request->products;
        if ($maintenance_type == 1) {
           $dsr->client_id = $client_id;

           $contact_person_details = $request->contact_person_details;

            if ($contact_person_details == 1) {
                 $data['contact_persons_value'] = 1;
                 $dsr->contact_person_name = $request->contact_person_name;
                 $dsr->contact_person_ph_no = $request->c_p_1_ph_no;
            }

            if ($contact_person_details == 2) {
                 $data['contact_persons_value'] = 2;
                 $dsr->contact_person_name = $request->c_p_2_name;
                 $dsr->contact_person_ph_no = $request->c_p_2_ph_no;
            }

           
           $dsr->maintenance_type = $maintenance_type;

           if($request->input('call_receive_date') != ''){
                $req_date = $request->input('call_receive_date');
                $tdr = str_replace("/", "-", $req_date);
                $new_mnf_dt = date('Y-m-d',strtotime($tdr));
            }
            else
            $new_mnf_dt = " ";

           $dsr->call_receive_date =  $new_mnf_dt;


           if($request->input('call_attend_date') != ''){
                $req_date1 = $request->input('call_attend_date');
                $tdr1 = str_replace("/", "-", $req_date1);
                $new_mnf_dt1 = date('Y-m-d',strtotime($tdr1));
            }else{
                $new_mnf_dt1 = " ";
            }

           $dsr->call_attend_date =  $new_mnf_dt1;

           $dsr->complaint_id = $request->complaint_id;

           $complaint_no = Complaint::where('id',$request->complaint_id)->where('status',1)->first()->complaint_no;
           
           $dsr->complaint_no = $complaint_no;
           $dsr->scr_no       = $request->get('scr_no');
           $dsr->complaint_status = $request->complaint_status;
 
           $dsr->entry_datetime = $today;
           $dsr->entry_by = $user->id;
           if ($dsr->save()) {
                foreach ($products as $key => $product) {
                    $group_id      = Product::where('id', $product["product_id"])->where('status', 1)->first()->group_id;
                    $dsr->group_id = $group_id;
                    $insert_data = [
                        "daily_service_report_id"         => $dsr->id,
                        "product_id"                      => $product["product_id"],
                        "group_id"                        => $group_id,
                        "nature_of_complaint_by_customer" => $product["nature_of_complaint_by_customer_amc"],
                        "fault_observation_by_engineer"   => $product["fault_observation_by_engineer_amc"],
                        "action_taken_by_engineer"        => $product["action_taken_by_engineer_amc"],
                        "remark_on_product"               => $product["remarks_amc"],
                        "model_no"                        => $product["model_no"],
                        "serial_no"                       => $product["serial_no"],
                    ];
                    DailyServiceReportProduct::create($insert_data);
                }
                // DailyServiceReportProduct::create($insert_data);
                $json_arr['status'] = true;
                $json_arr['message'] = 'Successfully submitted complaint details';
            }else{
                $json_arr['status'] = false;
                $json_arr['message'] = 'Please fix the error and try again';
                // $json_arr['dsr'] = $dsr;
            }

           $complaint = Complaint::where('id',$dsr->complaint_id)->where('status',1)->first();
           $complaint->complaint_status = $request->complaint_status;
           $complaint->last_updated_date = $today;

           if ($request->complaint_status == 2) {
               $last_updated_remarks = 'Complaint under-process';
           }
           if ($request->complaint_status == 3) {
               $last_updated_remarks = 'Complaint closed';
           }
          
           $complaint->last_updated_remarks = $last_updated_remarks;
           $complaint->last_remarks_by = $user->id;
           if ($complaint->save()) {
                $json_arr['status'] = true;
                $json_arr['message'] = 'Successfully submitted complaint details';
                // $json_arr['complaint'] = $complaint;
           }else{
                $json_arr['status'] = false;
                $json_arr['message'] = 'Please fix the error and try again';
           }

           $comp_trans_last_status = ComplaintTransaction::where('complaint_id',$complaint->id)->where('status',1)->update(['status' => 0]);

           $comp_transaction = new ComplaintTransaction();
           $comp_transaction->complaint_id = $complaint->id;
           $comp_transaction->transaction_date = $today;
           $comp_transaction->transaction_by = $complaint->last_remarks_by;
           $comp_transaction->remarks = $complaint->last_updated_remarks;
           $comp_transaction->transaction_remarks = $dsr->remarks;
           if ($comp_transaction->save()) {
               $json_arr['status'] = true;
               $json_arr['message'] = 'Successfully submitted complaint details';
               // $json_arr['comp_transaction'] = $comp_transaction;
           }else{
               $json_arr['status'] = false;
               $json_arr['message'] = 'Please fix the error and try again';
           }
        
           $spare_part_id = $request->spare_part_id;

           foreach ($request->spare_part_id as $key => $value) {
            if(!empty($value)){
               


               $sp_master = new SparePartMaster();
               $sp_master->engineer_id = $user->id;
               $sp_master->dsr_id = $dsr->id;
               $sp_master->date_of_transaction = date('Y-m-d');
               $sp_master->trans_type = 'sup';
               if ($sp_master->save()) {
                   $json_arr['status'] = true;
                   $json_arr['message'] = 'Successfully submitted complaint details';
                   // $json_arr['sp_master'] = $sp_master;
               }else{
                   $json_arr['status'] = false;
                   $json_arr['message'] = 'Please fix the error and try again';
               }


               $sp_master_trans = new SparePartTransaction();
               $sp_master_trans->spare_part_master_id = $sp_master->id;
               $sp_master_trans->spare_parts_id = $spare_part_id[$key];
               $sp_master_trans->description = 'Supplied spare part for dsr';
               $sp_master_trans->transaction_date = date('Y-m-d');
               $sp_master_trans->transaction_type = 'sup';
               $sp_master_trans->supplied_quantity = $request->spare_part_quantity[$key];
               $sp_master_trans->last_transaction_by = $user->id;
               if ($sp_master_trans->save()) {
                   $json_arr['status'] = true;
                   $json_arr['message'] = 'Successfully submitted complaint details';
                   // $json_arr['sp_master_trans'] = $sp_master_trans; 
               }else{
                   $json_arr['status'] = false;
                   $json_arr['message'] = 'Please fix the error and try again';
               }

               $iss_engineer = new IssueEngineer();
               $iss_engineer->spare_part_master_id = $sp_master->id;
               $iss_engineer->engineer_id = $user->id;
               $iss_engineer->spare_part_id = $spare_part_id[$key];
               // $iss_engineer->stock_in_hand = ;
               if ($iss_engineer->save()) {
                   $json_arr['status'] = true;
                   $json_arr['message'] = 'Successfully submitted complaint details';
                   // $json_arr['iss_engineer'] = $iss_engineer;
               }else{
                   $json_arr['status'] = false;
                   $json_arr['message'] = 'Please fix the error and try again';
               }

               $iss_engineer_trans = new IssueEngineerTransaction();
               $iss_engineer_trans->engineer_sp_trans_id = $sp_master_trans->id;
               $iss_engineer_trans->spare_part_master_id = $sp_master->id;
               $iss_engineer_trans->engineer_id = $user->id;
               $iss_engineer_trans->spare_part_id = $spare_part_id[$key];
               $iss_engineer_trans->description = 'Supplied spare part for dsr';
               $iss_engineer_trans->transaction_date = date('Y-m-d');
               $iss_engineer_trans->stock_out = $request->spare_part_quantity[$key];
               if ($iss_engineer_trans->save()) {
                   $json_arr['status'] = true;
                   $json_arr['message'] = 'Successfully submitted complaint details';
                   // $json_arr['iss_engineer_trans'] = $iss_engineer_trans;
               }else{
                   $json_arr['status'] = false;
                   $json_arr['message'] = 'Please fix the error and try again';
               }


               $dsr_transaction = new DailyServiceReportTransaction();
               $dsr_transaction->daily_service_report_id = $dsr->id;
               $dsr_transaction->spare_part_id = $spare_part_id[$key];
               $dsr_transaction->spare_part_quantity = $request->spare_part_quantity[$key];

               $stock_in_hand = IssueEngineerTransaction::where('status',1)->where([['engineer_id',Auth::user()->id],['spare_part_id',$spare_part_id]])->sum('stock_in');

               $stock_out = IssueEngineerTransaction::where('status',1)->where([['engineer_id',Auth::user()->id],['spare_part_id',$spare_part_id]])->sum('stock_out');

               // dd($stock_in_hand );

               $dsr_transaction->spare_part_stock_in_hand = $stock_in_hand - $stock_out;


               $dsr_transaction->spare_part_taken_back = $request->spare_part_taken_back[$key];
               $dsr_transaction->spare_part_taken_back_quantity = $request->spare_part_taken_back_quantity[$key];
               $dsr_transaction->unit_price_free = $request->unit_price_free[$key];
               $dsr_transaction->unit_price_chargeable = $request->unit_price_chargeable[$key];

               if ($request->labour_free[$key] != null) {
                   $dsr_transaction->labour_free = $request->labour_free[$key];
               }else{
                 $dsr_transaction->labour_free = 0;
               }

               if ($dsr_transaction->save()) {
                   $json_arr['status'] = true;
                   $json_arr['message'] = 'Successfully submitted complaint details';
                   // $json_arr['dsr_transaction'] = $dsr_transaction;
               }else{
                   $json_arr['status'] = false;
                   $json_arr['message'] = 'Please fix the error and try again';
               }

           }
         }

        
        }
        
        } catch (\Throwable $th) {
            \Log::error($th);
            DB::rollback();
            return response()->json([
                'success' => false,
                "message"   => "DSR not submitted.",
                "error" =>  $th->getMessage()
            ]);
        }
        DB::commit();
        return response()->json($json_arr);}else{
          return response()->json([
            'success' => false
          ]);
        }


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
        \Log::info($request->all());
        $user = JWTAuth::parseToken()->toUser(); 
        try {
            if($user){
                $json_arr = array();
                // $dsr_id = $request->id;
                /*$rules = [
                    "scr_no"    => "required"
                ];
                $validator = Validator::make($request->all(), $rules);
                if($validator->fails()){
                    return response()->json([
                        "status"    => false,
                        "message"   => "scr_no field is required.",
                    ]);
                } */
    
                $dsr = DailyServiceReport::with('dsr_transaction','client','product','engineer','complaint')
                    ->where('id', $id)
                    ->where('status', 1)
                    ->first();
    
                $today            = date("Y-m-d H:i:s");
                $maintenance_type = $request->maintenance_type;
                $client_name      = $request->client_id;
                $branch           = $request->branch;
                $dsr->scr_no      = $request->get("scr_no");
    
                $client_id = Client::where('branch_name',$branch)->where('name',$client_name)
                    ->first()
                    ->id;
    
                // Break down maintenance
                if ($maintenance_type == 1) {
                    $dsr->client_id         = $client_id;
                    $contact_person_details = $request->contact_person_details;
    
                    if ($contact_person_details == 1) {
                        $data['contact_persons_value'] = 1;
                        $dsr->contact_person_name      = $request->contact_person_name;
                        $dsr->contact_person_ph_no     = $request->c_p_1_ph_no;
                    }
    
                    if ($contact_person_details == 2) {
                        $data['contact_persons_value'] = 2;
                        $dsr->contact_person_name      = $request->c_p_2_name;
                        $dsr->contact_person_ph_no     = $request->c_p_2_ph_no;
                    }
    
                    $dsr->maintenance_type = $maintenance_type;
    
                    if ($request->input('call_receive_date') != '') {
                        $req_date   = $request->input('call_receive_date');
                        $tdr        = str_replace("/", "-", $req_date);
                        $new_mnf_dt = date('Y-m-d', strtotime($tdr));
                    } else {
                        $new_mnf_dt = " ";
                    }
    
                    $dsr->call_receive_date = $new_mnf_dt;
    
                    if ($request->input('call_attend_date') != '') {
                        $req_date1   = $request->input('call_attend_date');
                        $tdr1        = str_replace("/", "-", $req_date1);
                        $new_mnf_dt1 = date('Y-m-d', strtotime($tdr1));
                    } else {
                        $new_mnf_dt1 = " ";
                    }
                    $dsr->call_attend_date = $new_mnf_dt1;
                    // $dsr->complaint_id     = $request->complaint_id;
                    // $complaint_no          = Complaint::where('id', $request->complaint_id)->where('status', 1)->first()->complaint_no;
    
                    // $dsr->complaint_no                    = $complaint_no;
                    $dsr->complaint_status                = $request->complaint_status;
                    /* $dsr->nature_of_complaint_by_customer = $request->nature_of_complaint_by_customer;
                    $dsr->fault_observation_by_engineer   = $request->fault_observation_by_engineer;
                    $dsr->action_taken_by_engineer        = $request->action_taken_by_engineer;
                    $dsr->remarks                         = $request->remarks;
                    $dsr->product_id                      = $request->product_id;
    
                    if ($request->product_id != '') {
                        $group_id      = Product::where('id', $request->product_id)->where('status', 1)->first()->group_id;
                        $dsr->group_id = $group_id;
                    }
    
                    $dsr->model_no       = $request->model_no;
                    $dsr->serial_no      = $request->serial_no; */
                    $dsr->entry_datetime = $today;
                    $dsr->entry_by       = $user->id;
                    
                    $products            = $request->products;
                    if ($dsr->save()) {
                        $json_arr['status'] = true;
                        $json_arr['message'] = 'Successfully updated complaint details';
                        // $json_arr['iss_engineer_trans'] = $iss_engineer_trans;
                        $dsr->dsr_products()->delete();
                        foreach ($products as $key => $product) {
                            $group_id      = "";
                            $group_id      = Product::where('id', $product["product_id"])->where('status', 1)->first()->group_id;

                            $insert_data = [
                                // "daily_service_report_id"         => $dsr->id,
                                "product_id"                      => $product["product_id"],
                                "group_id"                        => $group_id,
                                "nature_of_complaint_by_customer" => $product["nature_of_complaint_by_customer_amc"],
                                "fault_observation_by_engineer"   => $product["fault_observation_by_engineer_amc"],
                                "action_taken_by_engineer"        => $product["action_taken_by_engineer_amc"],
                                "remark_on_product"               => $product["remarks_amc"],
                                "model_no"                        => $product["model_no"],
                                "serial_no"                       => $product["serial_no"],
                            ];
                            $dsr->dsr_products()->create($insert_data);
                        }
                    }else{
                        $json_arr['status'] = false;
                        $json_arr['message'] = 'Please fix the error and try again';
                    }
    
    
                    $sSpare_master = SparePartMaster::where('dsr_id',$dsr->id)->where('status',1)->get();
    
                    foreach ($sSpare_master as $key => $value) {
                        $spare_master = SparePartMaster::where('dsr_id', $dsr->id)->where('status', 1)->update(['status' => 0]);
                        $sp_trans     = SparePartTransaction::where('spare_part_master_id', $value->id)->where('status', 1)->update(['status' => 0]);
                        $iIss_eng     = IssueEngineer::where('spare_part_master_id', $value->id)->where('status', 1)->get();
    
                        foreach ($iIss_eng as $key1 => $value1) {
                            $iss_eng_trans = IssueEngineerTransaction::where('engineer_sp_trans_id', $value1->id)->where('spare_part_master_id', $value->id)->where('status', 1)->update(['status' => 0]);
                        }
    
                        $iss_eng   = IssueEngineer::where('spare_part_master_id', $value->id)->where('status', 1)->update(['status' => 0]);
                        $dsr_trans = DailyServiceReportTransaction::where('daily_service_report_id', $dsr->id)->where('status', 1)->update(['status' => 0]);
                    }
    
    
                
                
                    $complaint = Complaint::where('id',$dsr->complaint_id)->where('status',1)->first();
    
                    $complaint->complaint_status  = $request->complaint_status;
                    $complaint->last_updated_date = $today;
    
                    if ($request->complaint_status == 2) {
                        $last_updated_remarks = 'Complaint under-process';
                    }
                    if ($request->complaint_status == 3) {
                        $last_updated_remarks = 'Complaint closed';
                    }
    
                    $complaint->last_updated_remarks = $last_updated_remarks;
                    $complaint->last_remarks_by      = $user->id;
                    if ($complaint->save()) {
                        $json_arr['status']  = true;
                        $json_arr['message'] = 'Successfully updated complaint details';
                        // $json_arr['iss_engineer_trans'] = $iss_engineer_trans;
                    } else {
                        $json_arr['status']  = false;
                        $json_arr['message'] = 'Please fix the error and try again';
                    }
    
    
                
                    $comp_transaction = ComplaintTransaction::where('complaint_id',$complaint->id)->where('status',1)->first();
    
                    $comp_transaction->complaint_id        = $complaint->id;
                    $comp_transaction->transaction_date    = $today;
                    $comp_transaction->transaction_by      = $complaint->last_remarks_by;
                    $comp_transaction->remarks             = $complaint->last_updated_remarks;
                    $comp_transaction->transaction_remarks = $dsr->remarks;
                    if ($comp_transaction->save()) {
                        $json_arr['status']  = true;
                        $json_arr['message'] = 'Successfully updated complaint details';
                        // $json_arr['iss_engineer_trans'] = $iss_engineer_trans;
                    } else {
                        $json_arr['status']  = false;
                        $json_arr['message'] = 'Please fix the error and try again';
                    }
    
                    $spare_part_id = $request->spare_part_id;
    
    
                    foreach ($request->spare_part_id as $key => $value) {
                        if (!empty($value)) {
                            $sp_master                      = new SparePartMaster();
                            $sp_master->engineer_id         = $user->id;
                            $sp_master->dsr_id              = $dsr->id;
                            $sp_master->date_of_transaction = date('Y-m-d');
                            $sp_master->trans_type          = 'sup';
                            if ($sp_master->save()) {
                                $json_arr['status']  = true;
                                $json_arr['message'] = 'Successfully updated complaint details';
                                // $json_arr['iss_engineer_trans'] = $iss_engineer_trans;
                            } else {
                                $json_arr['status']  = false;
                                $json_arr['message'] = 'Please fix the error and try again';
                            }
    
                            $sp_master_trans                       = new SparePartTransaction();
                            $sp_master_trans->spare_part_master_id = $sp_master->id;
                            $sp_master_trans->spare_parts_id       = $spare_part_id[$key];
                            $sp_master_trans->description          = 'Supplied spare part for dsr';
                            $sp_master_trans->transaction_date     = date('Y-m-d');
                            $sp_master_trans->transaction_type     = 'sup';
                            $sp_master_trans->supplied_quantity    = $request->spare_part_quantity[$key];
                            $sp_master_trans->last_transaction_by  = $user->id;
                            if ($sp_master_trans->save()) {
                                $json_arr['status']  = true;
                                $json_arr['message'] = 'Successfully updated complaint details';
                                // $json_arr['iss_engineer_trans'] = $iss_engineer_trans;
                            } else {
                                $json_arr['status']  = false;
                                $json_arr['message'] = 'Please fix the error and try again';
                            }
    
                            $iss_engineer                       = new IssueEngineer();
                            $iss_engineer->spare_part_master_id = $sp_master->id;
                            $iss_engineer->engineer_id          = $user->id;
                            $iss_engineer->spare_part_id        = $spare_part_id[$key];
                            if ($iss_engineer->save()) {
                                $json_arr['status']  = true;
                                $json_arr['message'] = 'Successfully updated complaint details';
                                // $json_arr['iss_engineer_trans'] = $iss_engineer_trans;
                            } else {
                                $json_arr['status']  = false;
                                $json_arr['message'] = 'Please fix the error and try again';
                            }
    
                            $iss_engineer_trans                       = new IssueEngineerTransaction();
                            $iss_engineer_trans->engineer_sp_trans_id = $sp_master_trans->id;
                            $iss_engineer_trans->spare_part_master_id = $sp_master->id;
                            $iss_engineer_trans->engineer_id          = $user->id;
                            $iss_engineer_trans->spare_part_id        = $spare_part_id[$key];
                            $iss_engineer_trans->description          = 'Supplied spare part for dsr';
                            $iss_engineer_trans->transaction_date     = date('Y-m-d');
                            $iss_engineer_trans->stock_out            = $request->spare_part_quantity[$key];
                            if ($iss_engineer_trans->save()) {
                                $json_arr['status']  = true;
                                $json_arr['message'] = 'Successfully updated complaint details';
                                // $json_arr['iss_engineer_trans'] = $iss_engineer_trans;
                            } else {
                                $json_arr['status']  = false;
                                $json_arr['message'] = 'Please fix the error and try again';
                            }
    
                            $dsr_transaction                          = new DailyServiceReportTransaction();
                            $dsr_transaction->daily_service_report_id = $dsr->id;
                            $dsr_transaction->spare_part_id           = $spare_part_id[$key];
                            $dsr_transaction->spare_part_quantity     = $request->spare_part_quantity[$key];
    
                            $stock_in_hand = IssueEngineerTransaction::where('status', 1)->where([['engineer_id', Auth::user()->id], ['spare_part_id', $spare_part_id]])->sum('stock_in');
    
                            $stock_out = IssueEngineerTransaction::where('status', 1)->where([['engineer_id', Auth::user()->id], ['spare_part_id', $spare_part_id]])->sum('stock_out');
    
                            // dd($stock_in_hand );
    
                            $dsr_transaction->spare_part_stock_in_hand = $stock_in_hand - $stock_out;
    
                            $dsr_transaction->spare_part_taken_back          = $request->spare_part_taken_back[$key];
                            $dsr_transaction->spare_part_taken_back_quantity = $request->spare_part_taken_back_quantity[$key];
                            $dsr_transaction->unit_price_free                = $request->unit_price_free[$key];
                            $dsr_transaction->unit_price_chargeable          = $request->unit_price_chargeable[$key];
    
                            if ($request->labour_free[$key] != null) {
                                $dsr_transaction->labour_free = $request->labour_free[$key];
                            } else {
                                $dsr_transaction->labour_free = 0;
                            }
    
                            if ($dsr_transaction->save()) {
                                $json_arr['status']  = true;
                                $json_arr['message'] = 'Successfully updated complaint details';
                                // $json_arr['iss_engineer_trans'] = $iss_engineer_trans;
                            } else {
                                $json_arr['status']  = false;
                                $json_arr['message'] = 'Please fix the error and try again';
                            }
                        }
                    }
                }
                return response()->json($json_arr);
            }else{
                return response()->json([
                    'status' => false,
                    "message"   => "Please fix the error and try again"
                ]);
            }
        } catch (\Throwable $th) {
            \Log::error($th);
            return response()->json([
                'status' => false,
                "message"   => "Please fix the error and try again"
            ]);
        }
    }


    public function closedComplaint()
    {

        $user = JWTAuth::parseToken()->toUser(); 
        // dd($user);
        if($user ){ 

        $closed_comp = Complaint::with('client', 'group','product','comp_master','user')->where('assigned_to',$user->id)->where('complaint_status',3)->orderBy('complaint_entry_date', 'DESC')->where('status',1)->get();

        return response()->json([
            'status' => true, 
            'data'=> [
                'closed_comp' => $closed_comp
                ]
            ]);
             }else{
                return response()->json([
                'success' => false
            ]);
        }
    }

    public function getAllComplaint()
    {
        $user = JWTAuth::parseToken()->toUser(); 
        // dd($user);
        if($user ){

            $all_comp = Complaint::with('client', 'group','product','comp_master','user')
            // ->where('assigned_to',$user->id)
            ->where(function($query) use ($user){
                return $query->where('assigned_to', $user->id)
                    ->orWhereHas("assigned_engineers", function($query_sub) use ($user){
                        return $query_sub->where("engineer_id", $user->id);
                    });
            })
            ->orderBy('complaint_entry_date', 'DESC')
            ->where('status',1)
            ->get();

            return response()->json([
            'status' => true, 
            'data'=> [
                'all_comp' => $all_comp
                ]
            ]);
             }else{
                return response()->json([
                'success' => false
            ]);
        }

    }


    public function addNewBreakDown()
    {
        $user = JWTAuth::parseToken()->toUser(); 
        //dd($user);
        if($user ){

        $json_arr = array();

        $today_date = date('d-m-Y');
        $json_arr['status'] = true;
        $json_arr['today_date'] = $today_date;

        $assigned_comp = Complaint::with(['client', 'group','product','comp_master','user', "assigned_engineers" => function($select_query){
            return $select_query->select("complaint_id", "engineer_id", "created_at");
        }])
            // ->where('assigned_to',$user->id)
            ->where('complaint_status','!=',3)
            ->orderBy('complaint_entry_date', 'DESC')
            ->where(function($query) use ($user){
                return $query->where('assigned_to', $user->id)
                    ->orWhereHas("assigned_engineers", function($query_sub) use ($user){
                        return $query_sub->where("engineer_id", $user->id);
                    });
            })
            ->where('status',1)
            ->get();
            if($assigned_comp){
                $assigned_comp->map(function($item){
                    $item->complaint_assigned_date = ($item->assigned_engineers->count() ? $item->assigned_engineers->first()->created_at->format("Y-m-d") : dateFormat($item->complaint_entry_date, "Y-m-d"));
                    // return $item;
                });
            }
        //dd($assigned_comp);

        if ($assigned_comp->count()) {
            $json_arr['status'] = true;
            $json_arr['assigned_comp'] = $assigned_comp;

            $client = [];
            foreach ($assigned_comp as $key => $value) {         
                $client_names = Client::select('id')
                    ->where('id',$value->client_id)
                    ->where('status',1)
                    ->get()
                    ->toArray();
                if (isset($client_names)) {                
                    foreach ($client_names as $key => $client_name) {
                        $client[] = $client_name['id'];
                    }
                }else{
                    $json_arr['status'] = false;
                    $json_arr['assigned_products'] = [];
                }
            }
            
                
            if (isset($client)) {                
                $assigned_products = AssignProductToClient::with('client','product')
                    ->whereIn('client_id',$client)
                    ->where('status',1)
                    ->get();
                $json_arr['status'] = true;
                $json_arr['assigned_products'] = $assigned_products;
                
            }else{
                $json_arr['status'] = false;
                $json_arr['assigned_products'] = [];
            }
        }else{
            $json_arr['status'] = false;
            $json_arr['assigned_comp'] = [];
            $json_arr['assigned_products'] = [];
        }


        $assigned_spare_parts = SparePartMaster::with('spare_part_transaction','user')->where('engineer_id',$user->id)->where('trans_type','=','iss')->where('status',1)->get();

        $all_sp_prts = [];

        if (isset($assigned_spare_parts)) {
            

            foreach($assigned_spare_parts as $key => $value) {
                    $all_spare_parts = SparePartTransaction::where('spare_part_master_id',$value->id)->where('status',1)->get();
                   
                    $all_spare_parts_to_array = SparePartTransaction::with('spare_part')->where('spare_part_master_id',$value->id)->where('status',1)->get()->toArray();


                    foreach ($all_spare_parts_to_array as $key1 => $value1) {
                        array_push($all_sp_prts, $value1);
                        // $all_sp_prts[$value1->spare_parts_id]  = $value1;
                    }

              }

            $json_arr['status'] = true;
            $json_arr['assigned_spare_parts'] = $all_sp_prts;

        }else{
            $json_arr['status'] = false;
            $json_arr['assigned_spare_parts'] = [];
        }
        if(!$assigned_comp->count()){
            $json_arr['status'] = false;
        }
        if(!$json_arr['status']){
            $json_arr['message'] = "No Records found.";
        }

        return response()->json($json_arr);
        }else{
                return response()->json([
                'status' => false
            ]);
        }
    }


}
