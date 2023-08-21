<?php

namespace App\Http\Controllers\Engineer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth,Session,DB,Crypt,Validator,Excel;
use App\Models\Dsr\DailyServiceReport, App\Models\Dsr\DailyServiceReportTransaction, App\Models\Client, App\Models\ClientAmcMaster, App\Models\Complaint, App\Models\Assign\AssignProductToClient, App\Models\Product, App\Models\SparePartMaster, App\Models\SparePartTransaction, App\Models\SparePart, App\Models\ComplaintTransaction, App\Models\IssueEngineer, App\Models\IssueEngineerTransaction, App\Models\Assign\AssignEngineer, App\Models\ClientAmcProduct;

class DailyServiceReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dsr_reports = DailyServiceReport::with('dsr_transaction','client','product','engineer','complaint')->where('entry_by',Auth::user()->id)->where('status',1)->orderBy('id','desc')->get();
        return view('engineer.dsr.index',compact('dsr_reports'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
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
        if ($client_id != "" && $branch != "") {
            $client_contact_person_details = Client::where('name',$client_id)->where('branch_name',$branch)->where('status',1)->first();
        }

        $assgn_eng = AssignEngineer::where('engineer_id',Auth::user()->id)->where('status',1)->get();
        foreach ($assgn_eng as $key => $value) {
          $clients = Client::where('id',$value->client_id)->where('status',1)->get();
        }
     
        $unique_clients = $assgn_eng->unique("client_id")->values()->all();

        $dsr_report = DailyServiceReport::with('dsr_transaction','client','product','engineer','complaint')->where('entry_by',Auth::user()->id)->where('status',1)->orderBy('entry_datetime','desc')->first();
        $user_assigned_complaints = Complaint::where('assigned_to',Auth::user()->id)->where('complaint_status','!=',3)->where('status',1)->get();

        $assigned_products = AssignProductToClient::with('client','product')->where('status',1)->get();

        $assigned_spare_parts = SparePartMaster::with('spare_part_transaction','user')->where('engineer_id',Auth::user()->id)->where('trans_type','=','iss')->where('status',1)->get();

        $all_sp_prts = [];
        if ($assigned_spare_parts) {

            foreach($assigned_spare_parts as $key => $value) {
                    $all_spare_parts = SparePartTransaction::where('spare_part_master_id',$value->id)->where('status',1)->get();
                  
                    $all_spare_parts_to_array = SparePartTransaction::with('spare_part')->where('spare_part_master_id',$value->id)->where('status',1)->get();


                    foreach ($all_spare_parts_to_array as $key1 => $value1) {
                        // array_push($all_sp_prts, $value1['spare_parts_id']);
                        $all_sp_prts[$value1->spare_parts_id]  = $value1;
                    }

              }
          }
      
      
        return view('engineer.dsr.create',compact('assgn_eng','clients','dsr_report', 'client_id','branch','unique_clients','maintenance_type','user_assigned_complaints','assigned_products','assigned_spare_parts','client_contact_person_details','all_spare_parts','all_sp_prts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $data = $request->all();
        // dd($data);
        // $spare_part_taken_back = $request->spare_part_taken_back;
        // dd($spare_part_taken_back);

        $today = date("Y-m-d H:i:s");
        $maintenance_type = $request->maintenance_type;
        $client_name = $request->client_id;
        $branch = $request->branch;
        $client_id = Client::where('branch_name',$branch)->where('name',$client_name)->first()->id;
        // dd($client_id);
        

        $dsr = new DailyServiceReport();
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
            }
            else
            $new_mnf_dt1 = " ";

           $dsr->call_attend_date =  $new_mnf_dt1;

           $dsr->complaint_id = $request->complaint_id;

           $complaint_no = Complaint::where('id',$request->complaint_id)->where('status',1)->first()->complaint_no;
           
           $dsr->complaint_no = $complaint_no;
           $dsr->complaint_status = $request->complaint_status;

           $dsr->nature_of_complaint_by_customer = $request->nature_of_complaint_by_customer;
           $dsr->fault_observation_by_engineer = $request->fault_observation_by_engineer;
           $dsr->action_taken_by_engineer = $request->action_taken_by_engineer;
           $dsr->remarks = $request->remarks;
           $dsr->product_id = $request->product_id;

           if ($request->product_id != '') {
               $group_id = Product::where('id',$request->product_id)->where('status',1)->first()->group_id;
               $dsr->group_id = $group_id;
           }
           $dsr->model_no = $request->model_no;
           $dsr->serial_no = $request->serial_no;
 
           $dsr->entry_datetime = $today;
           $dsr->entry_by = Auth::user()->id;
           $dsr->save();

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
           $complaint->last_remarks_by = Auth::user()->id;
           $complaint->save();

           $comp_trans_last_status = ComplaintTransaction::where('complaint_id',$complaint->id)->where('status',1)->update(['status' => 0]);

           $comp_transaction = new ComplaintTransaction();
           $comp_transaction->complaint_id = $complaint->id;
           $comp_transaction->transaction_date = $today;
           $comp_transaction->transaction_by = $complaint->last_remarks_by;
           $comp_transaction->remarks = $complaint->last_updated_remarks;
           $comp_transaction->transaction_remarks = $dsr->remarks;
           $comp_transaction->save();
        
           $spare_part_id = $request->spare_part_id;

           foreach ($request->spare_part_id as $key => $value) {
            if(!empty($value)){
               


               $sp_master = new SparePartMaster();
               $sp_master->engineer_id = Auth::user()->id;
               $sp_master->dsr_id = $dsr->id;
               $sp_master->date_of_transaction = date('Y-m-d');
               $sp_master->trans_type = 'sup';
               $sp_master->save();


               $sp_master_trans = new SparePartTransaction();
               $sp_master_trans->spare_part_master_id = $sp_master->id;
               $sp_master_trans->spare_parts_id = $spare_part_id[$key];
               $sp_master_trans->description = 'Supplied spare part for dsr';
               $sp_master_trans->transaction_date = date('Y-m-d');
               $sp_master_trans->transaction_type = 'sup';
               $sp_master_trans->supplied_quantity = $request->spare_part_quantity[$key];
               $sp_master_trans->last_transaction_by = Auth::user()->id;
               $sp_master_trans->save();

               $iss_engineer = new IssueEngineer();
               $iss_engineer->spare_part_master_id = $sp_master->id;
               $iss_engineer->engineer_id = Auth::user()->id;
               $iss_engineer->spare_part_id = $spare_part_id[$key];
               // $iss_engineer->stock_in_hand = ;
               $iss_engineer->save();

               $iss_engineer_trans = new IssueEngineerTransaction();
               $iss_engineer_trans->engineer_sp_trans_id = $sp_master_trans->id;
               $iss_engineer_trans->spare_part_master_id = $sp_master->id;
               $iss_engineer_trans->engineer_id = Auth::user()->id;
               $iss_engineer_trans->spare_part_id = $spare_part_id[$key];
               $iss_engineer_trans->description = 'Supplied spare part for dsr';
               $iss_engineer_trans->transaction_date = date('Y-m-d');
               $iss_engineer_trans->stock_out = $request->spare_part_quantity[$key];
               $iss_engineer_trans->save();


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

               $dsr_transaction->save();

           }
           }

        
        }
         if ($maintenance_type == 2) {
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

           if($request->input('call_receive_date_amc') != ''){
                $req_date = $request->input('call_receive_date_amc');
                $tdr = str_replace("/", "-", $req_date);
                $new_mnf_dt = date('Y-m-d',strtotime($tdr));
            }
            else
            $new_mnf_dt = " ";

           $dsr->call_receive_date =  $new_mnf_dt;


           if($request->input('call_attend_date_amc') != ''){
                $req_date1 = $request->input('call_attend_date_amc');
                $tdr1 = str_replace("/", "-", $req_date1);
                $new_mnf_dt1 = date('Y-m-d',strtotime($tdr1));
            }
            else
            $new_mnf_dt1 = " ";

           $dsr->call_attend_date =  $new_mnf_dt1;

           $dsr->nature_of_complaint_by_customer = $request->nature_of_complaint_by_customer_amc;
           $dsr->fault_observation_by_engineer = $request->fault_observation_by_engineer_amc;
           $dsr->action_taken_by_engineer = $request->action_taken_by_engineer_amc;
           $dsr->remarks = $request->remarks_amc;
           $dsr->product_id = $request->product_id_amc;

           if ($request->product_id_amc != '') {
               $group_id = Product::where('id',$request->product_id_amc)->where('status',1)->first()->group_id;
               $dsr->group_id = $group_id;
           }
           
           $dsr->model_no = $request->model_no_amc;
           $dsr->serial_no = $request->serial_no_amc;
         
           $dsr->entry_datetime = $today;
           $dsr->entry_by = Auth::user()->id;
           $dsr->save();

           $spare_part_id_amc = $request->spare_part_id_amc;
           // dd($request->spare_part_id);

           foreach ($request->spare_part_id_amc as $key1 => $value1) {
            // dd($request->spare_part_quantity_amc);
            
            if(!empty($value1)){
             
               $sp_master = new SparePartMaster();
               $sp_master->engineer_id = Auth::user()->id;
               $sp_master->dsr_id = $dsr->id;
               $sp_master->date_of_transaction = date('Y-m-d');
               $sp_master->trans_type = 'sup';
               $sp_master->save();


               $sp_master_trans = new SparePartTransaction();
               $sp_master_trans->spare_part_master_id = $sp_master->id;
               $sp_master_trans->spare_parts_id = $request->spare_part_id_amc[$key1];
               $sp_master_trans->description = 'Supplied spare part for dsr';
               $sp_master_trans->transaction_date = date('Y-m-d');
               $sp_master_trans->transaction_type = 'sup';
               $sp_master_trans->supplied_quantity = $request->spare_part_quantity_amc[$key1];
               $sp_master_trans->last_transaction_by = Auth::user()->id;
               $sp_master_trans->save();

               $iss_engineer = new IssueEngineer();
               $iss_engineer->spare_part_master_id = $sp_master->id;
               $iss_engineer->engineer_id = Auth::user()->id;
               $iss_engineer->spare_part_id = $request->spare_part_id_amc[$key1];
               // $iss_engineer->stock_in_hand = ;
               $iss_engineer->save();

               $iss_engineer_trans = new IssueEngineerTransaction();
               $iss_engineer_trans->engineer_sp_trans_id = $sp_master_trans->id;
               $iss_engineer_trans->spare_part_master_id = $sp_master->id;
               $iss_engineer_trans->engineer_id = Auth::user()->id;
               $iss_engineer_trans->spare_part_id = $request->spare_part_id_amc[$key1];
               $iss_engineer_trans->description = 'Supplied spare part for dsr';
               $iss_engineer_trans->transaction_date = date('Y-m-d');
               $iss_engineer_trans->stock_out = $request->spare_part_quantity_amc[$key1];
               $iss_engineer_trans->save();

               $dsr_transaction = new DailyServiceReportTransaction();
               $dsr_transaction->daily_service_report_id = $dsr->id;
               $dsr_transaction->spare_part_id = $request->spare_part_id_amc[$key1];
               $dsr_transaction->spare_part_quantity = $request->spare_part_quantity_amc[$key1];

               $stock_in_hand = IssueEngineerTransaction::where('status',1)->where([['engineer_id',Auth::user()->id],['spare_part_id',$spare_part_id_amc]])->sum('stock_in');

               $stock_out = IssueEngineerTransaction::where('status',1)->where([['engineer_id',Auth::user()->id],['spare_part_id',$spare_part_id_amc]])->sum('stock_out');

               // dd($stock_in_hand );

               $dsr_transaction->spare_part_stock_in_hand = $stock_in_hand - $stock_out;


               $dsr_transaction->spare_part_taken_back = $request->spare_part_taken_back_amc[$key1];
               $dsr_transaction->spare_part_taken_back_quantity = $request->spare_part_taken_back_quantity_amc[$key1];
               
               $dsr_transaction->unit_price_free = $request->unit_price_free_amc[$key1];
               $dsr_transaction->unit_price_chargeable = $request->unit_price_chargeable_amc[$key1];

               if ($request->labour_free_amc[$key1] != null) {
                   $dsr_transaction->labour_free = $request->labour_free_amc[$key1];
               }else{
                 $dsr_transaction->labour_free = 0;
               }

               $dsr_transaction->save();
           }
       }

        }

      Session::flash('success','You have successfully submitted daily service report');
      return view('engineer.dsr.confirm-submit',compact('maintenance_type','client_name','branch'));


        // return redirect()->route('view-all-daily-service-report');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dsr_id = Crypt::decrypt($id);
        $dsr = DailyServiceReport::with('dsr_transaction','client','product','engineer','complaint')->where('id',$dsr_id)->where('status',1)->first();
        return view('engineer.dsr.show',compact('dsr'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dsr_id = Crypt::decrypt($id);
        $dsr = DailyServiceReport::with('dsr_transaction','client','product','engineer','complaint')->where('id',$dsr_id)->where('status',1)->first();
        // dd($dsr);

       
        // $client_contact_person_details = Client::where('name',$dsr->client->name)->where('branch_name',$dsr->client->branch_name)->where('status',1)->first();
            
       $clients = [];
       $clients_id = [];
        $assgn_eng = AssignEngineer::where('engineer_id',Auth::user()->id)->where('status',1)->get();
        foreach ($assgn_eng as $key => $value) {
          $new_clients = Client::where('id',$value->client_id)->where('status',1)->get();
          foreach ($new_clients as $key => $singleClient) {
            $clients[] = $singleClient;
            array_push($clients_id, $singleClient['id']);
          }
        }
     
         
        $unique_clients = $assgn_eng->unique("client_id")->values()->all();

       
        $dsr_report = DailyServiceReport::with('dsr_transaction','client','product','engineer','complaint')->where('entry_by',Auth::user()->id)->where('status',1)->orderBy('entry_datetime','desc')->first();

        $user_assigned_complaints = Complaint::where('assigned_to',Auth::user()->id)->where('complaint_status','!=',3)->where('status',1)->get();

        $user_assigned_complaints1 = Complaint::where('assigned_to',Auth::user()->id)->where('status',1)->get();

        $assigned_products = AssignProductToClient::with('client','product')->whereIn('client_id',$clients_id)->where('status',1)->get();

        // dd($assigned_products);

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

          
                }
          }


            $amc_client_master = ClientAmcMaster::where('client_id',$dsr->client_id)->where('status',1)->get();
            foreach ($amc_client_master as $key => $value) {
              $amc_products = ClientAmcProduct::with('product')->where('client_amc_masters_id',$value->id)->where('status',1)->get();
            }

        return view('engineer.dsr.edit',compact('dsr','assgn_eng','clients','dsr_report', 'client_id','branch','unique_clients','maintenance_type','user_assigned_complaints','assigned_products','assigned_spare_parts','client_contact_person_details','all_spare_parts','amc_products','all_sp_prts','user_assigned_complaints1'));
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

        $dsr_id = Crypt::decrypt($id);
        $dsr = DailyServiceReport::with('dsr_transaction','client','product','engineer','complaint')->where('id',$dsr_id)->where('status',1)->first();

        // $data = $request->all();

        // dd($data);


        $today = date("Y-m-d H:i:s");
        $maintenance_type = $request->maintenance_type;
        $client_name = $request->client_id;
        $branch = $request->branch;
        $client_id = Client::where('branch_name',$branch)->where('name',$client_name)->first()->id;
        // dd($client_id);
       
       // Break down maintenance
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
            }
            else
            $new_mnf_dt1 = " ";

           $dsr->call_attend_date =  $new_mnf_dt1;

           $dsr->complaint_id = $request->complaint_id;

           $complaint_no = Complaint::where('id',$request->complaint_id)->where('status',1)->first()->complaint_no;
           
           $dsr->complaint_no = $complaint_no;
           $dsr->complaint_status = $request->complaint_status;

           $dsr->nature_of_complaint_by_customer = $request->nature_of_complaint_by_customer;
           $dsr->fault_observation_by_engineer = $request->fault_observation_by_engineer;
           $dsr->action_taken_by_engineer = $request->action_taken_by_engineer;
           $dsr->remarks = $request->remarks;
           $dsr->product_id = $request->product_id;

           if ($request->product_id != '') {
               $group_id = Product::where('id',$request->product_id)->where('status',1)->first()->group_id;
               $dsr->group_id = $group_id;
           }

           $dsr->model_no = $request->model_no;
           $dsr->serial_no = $request->serial_no;
 
           $dsr->entry_datetime = $today;
           $dsr->entry_by = Auth::user()->id;
           $dsr->save();


           $sSpare_master = SparePartMaster::where('dsr_id',$dsr->id)->where('status',1)->get();

           foreach ($sSpare_master as $key => $value) {
             $spare_master = SparePartMaster::where('dsr_id',$dsr->id)->where('status',1)->update(['status' => 0]);

             $sp_trans = SparePartTransaction::where('spare_part_master_id',$value->id)->where('status',1)->update(['status' => 0]);

           // dd($sp_trans);

             $iIss_eng = IssueEngineer::where('spare_part_master_id',$value->id)->where('status',1)->get();


             foreach ($iIss_eng as $key1 => $value1) {
               $iss_eng_trans = IssueEngineerTransaction::where('engineer_sp_trans_id',$value1->id)->where('spare_part_master_id',$value->id)->where('status',1)->update(['status' => 0]);
             }

             $iss_eng = IssueEngineer::where('spare_part_master_id',$value->id)->where('status',1)->update(['status' => 0]);

             $dsr_trans = DailyServiceReportTransaction::where('daily_service_report_id',$dsr->id)->where('status',1)->update(['status' => 0]);

       
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
           $complaint->last_remarks_by = Auth::user()->id;
           $complaint->save();

          
           $comp_transaction = ComplaintTransaction::where('complaint_id',$complaint->id)->where('status',1)->first();

           $comp_transaction->complaint_id = $complaint->id;
           $comp_transaction->transaction_date = $today;
           $comp_transaction->transaction_by = $complaint->last_remarks_by;
           $comp_transaction->remarks = $complaint->last_updated_remarks;
           $comp_transaction->transaction_remarks = $dsr->remarks;
           $comp_transaction->save();
        
           $spare_part_id = $request->spare_part_id;

           foreach ($request->spare_part_id as $key => $value) {
            if(!empty($value)){
             

               $sp_master = new SparePartMaster();
               $sp_master->engineer_id = Auth::user()->id;
               $sp_master->dsr_id = $dsr->id;
               $sp_master->date_of_transaction = date('Y-m-d');
               $sp_master->trans_type = 'sup';
               $sp_master->save();


               $sp_master_trans = new SparePartTransaction();
               $sp_master_trans->spare_part_master_id = $sp_master->id;
               $sp_master_trans->spare_parts_id = $spare_part_id[$key];
               $sp_master_trans->description = 'Supplied spare part for dsr';
               $sp_master_trans->transaction_date = date('Y-m-d');
               $sp_master_trans->transaction_type = 'sup';
               $sp_master_trans->supplied_quantity = $request->spare_part_quantity[$key];
               $sp_master_trans->last_transaction_by = Auth::user()->id;
               $sp_master_trans->save();

               $iss_engineer = new IssueEngineer();
               $iss_engineer->spare_part_master_id = $sp_master->id;
               $iss_engineer->engineer_id = Auth::user()->id;
               $iss_engineer->spare_part_id = $spare_part_id[$key];
               // $iss_engineer->stock_in_hand = ;
               $iss_engineer->save();

               $iss_engineer_trans = new IssueEngineerTransaction();
               $iss_engineer_trans->engineer_sp_trans_id = $sp_master_trans->id;
               $iss_engineer_trans->spare_part_master_id = $sp_master->id;
               $iss_engineer_trans->engineer_id = Auth::user()->id;
               $iss_engineer_trans->spare_part_id = $spare_part_id[$key];
               $iss_engineer_trans->description = 'Supplied spare part for dsr';
               $iss_engineer_trans->transaction_date = date('Y-m-d');
               $iss_engineer_trans->stock_out = $request->spare_part_quantity[$key];
               $iss_engineer_trans->save();


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

               $dsr_transaction->save();

           }
           }

        
        }

        // Preventive Maintenance
         if ($maintenance_type == 2) {
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

           if($request->input('call_receive_date_amc') != ''){
                $req_date = $request->input('call_receive_date_amc');
                $tdr = str_replace("/", "-", $req_date);
                $new_mnf_dt = date('Y-m-d',strtotime($tdr));
            }
            else
            $new_mnf_dt = " ";

           $dsr->call_receive_date =  $new_mnf_dt;


           if($request->input('call_attend_date_amc') != ''){
                $req_date1 = $request->input('call_attend_date_amc');
                $tdr1 = str_replace("/", "-", $req_date1);
                $new_mnf_dt1 = date('Y-m-d',strtotime($tdr1));
            }
            else
            $new_mnf_dt1 = " ";

           $dsr->call_attend_date =  $new_mnf_dt1;

           $dsr->nature_of_complaint_by_customer = $request->nature_of_complaint_by_customer_amc;
           $dsr->fault_observation_by_engineer = $request->fault_observation_by_engineer_amc;
           $dsr->action_taken_by_engineer = $request->action_taken_by_engineer_amc;
           $dsr->remarks = $request->remarks_amc;
           $dsr->product_id = $request->product_id_amc;

           if ($request->product_id_amc != '') {
               $group_id = Product::where('id',$request->product_id_amc)->where('status',1)->first()->group_id;
               $dsr->group_id = $group_id;
           }
           
           $dsr->model_no = $request->model_no_amc;
           $dsr->serial_no = $request->serial_no_amc;
         
           $dsr->entry_datetime = $today;
           $dsr->entry_by = Auth::user()->id;
           $dsr->save();

           $sSpare_master = SparePartMaster::where('dsr_id',$dsr->id)->where('status',1)->get();

           foreach ($sSpare_master as $key => $value) {
             $spare_master = SparePartMaster::where('dsr_id',$dsr->id)->where('status',1)->update(['status' => 0]);

             $sp_trans = SparePartTransaction::where('spare_part_master_id',$value->id)->where('status',1)->update(['status' => 0]);

           // dd($sp_trans);

             $iIss_eng = IssueEngineer::where('spare_part_master_id',$value->id)->where('status',1)->get();


             foreach ($iIss_eng as $key1 => $value1) {
               $iss_eng_trans = IssueEngineerTransaction::where('engineer_sp_trans_id',$value1->id)->where('spare_part_master_id',$value->id)->where('status',1)->update(['status' => 0]);
             }

             $iss_eng = IssueEngineer::where('spare_part_master_id',$value->id)->where('status',1)->update(['status' => 0]);

             $dsr_trans = DailyServiceReportTransaction::where('daily_service_report_id',$dsr->id)->where('status',1)->update(['status' => 0]);

       
           }

           $spare_part_id_amc = $request->spare_part_id_amc;
           // dd($request->spare_part_id);

           foreach ($request->spare_part_id_amc as $key1 => $value1) {
            // dd($request->spare_part_quantity_amc);
            
            if(!empty($value1)){
             
               $sp_master = new SparePartMaster();
               $sp_master->engineer_id = Auth::user()->id;
               $sp_master->dsr_id = $dsr->id;
               $sp_master->date_of_transaction = date('Y-m-d');
               $sp_master->trans_type = 'sup';
               $sp_master->save();


               $sp_master_trans = new SparePartTransaction();
               $sp_master_trans->spare_part_master_id = $sp_master->id;
               $sp_master_trans->spare_parts_id = $request->spare_part_id_amc[$key1];
               $sp_master_trans->description = 'Supplied spare part for dsr';
               $sp_master_trans->transaction_date = date('Y-m-d');
               $sp_master_trans->transaction_type = 'sup';
               $sp_master_trans->supplied_quantity = $request->spare_part_quantity_amc[$key1];
               $sp_master_trans->last_transaction_by = Auth::user()->id;
               $sp_master_trans->save();

               $iss_engineer = new IssueEngineer();
               $iss_engineer->spare_part_master_id = $sp_master->id;
               $iss_engineer->engineer_id = Auth::user()->id;
               $iss_engineer->spare_part_id = $request->spare_part_id_amc[$key1];
               // $iss_engineer->stock_in_hand = ;
               $iss_engineer->save();

               $iss_engineer_trans = new IssueEngineerTransaction();
               $iss_engineer_trans->engineer_sp_trans_id = $sp_master_trans->id;
               $iss_engineer_trans->spare_part_master_id = $sp_master->id;
               $iss_engineer_trans->engineer_id = Auth::user()->id;
               $iss_engineer_trans->spare_part_id = $request->spare_part_id_amc[$key1];
               $iss_engineer_trans->description = 'Supplied spare part for dsr';
               $iss_engineer_trans->transaction_date = date('Y-m-d');
               $iss_engineer_trans->stock_out = $request->spare_part_quantity_amc[$key1];
               $iss_engineer_trans->save();

               $dsr_transaction = new DailyServiceReportTransaction();
               $dsr_transaction->daily_service_report_id = $dsr->id;
               $dsr_transaction->spare_part_id = $request->spare_part_id_amc[$key1];
               $dsr_transaction->spare_part_quantity = $request->spare_part_quantity_amc[$key1];

               $stock_in_hand = IssueEngineerTransaction::where('status',1)->where([['engineer_id',Auth::user()->id],['spare_part_id',$spare_part_id_amc]])->sum('stock_in');

               $stock_out = IssueEngineerTransaction::where('status',1)->where([['engineer_id',Auth::user()->id],['spare_part_id',$spare_part_id_amc]])->sum('stock_out');

               // dd($stock_in_hand );

               $dsr_transaction->spare_part_stock_in_hand = $stock_in_hand - $stock_out;


               $dsr_transaction->spare_part_taken_back = $request->spare_part_taken_back_amc[$key1];
               $dsr_transaction->spare_part_taken_back_quantity = $request->spare_part_taken_back_quantity_amc[$key1];
               
               $dsr_transaction->unit_price_free = $request->unit_price_free_amc[$key1];
               $dsr_transaction->unit_price_chargeable = $request->unit_price_chargeable_amc[$key1];

               if ($request->labour_free_amc[$key1] != null) {
                   $dsr_transaction->labour_free = $request->labour_free_amc[$key1];
               }else{
                 $dsr_transaction->labour_free = 0;
               }

               $dsr_transaction->save();
           }
        }

      }

      Session::flash('success','You have successfully updated daily service report');
      // return view('engineer.dsr.confirm-submit',compact('maintenance_type','client_name','branch'));


       return redirect()->route('view-all-daily-service-report');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dsr_id = Crypt::decrypt($id);
        $today = date("Y-m-d H:i:s");
        $daily_service_report = DailyServiceReport::with('dsr_transaction','client','product','engineer','complaint')->where('id',$dsr_id)->where('status',1)->first();

        // $complaint = Complaint::where('id',$daily_service_report->complaint_id)->where('status',1)->first();


        $complaint_update = Complaint::where('id',$daily_service_report->complaint_id)->where('status',1)->first();
        $complaint_update->complaint_status = 1 ;
        $complaint_update->last_updated_remarks = 'DSR for this complaint has been deleted' ;
        $complaint_update->last_remarks_by = Auth::user()->id;
        $complaint_update->save() ;

        $comp_trans_last_status = ComplaintTransaction::where('complaint_id',$complaint_update->id)->where('status',1)->update(['status' => 0]);

         $comp_transaction = new ComplaintTransaction();
         $comp_transaction->complaint_id = $complaint_update->id;
         $comp_transaction->transaction_date = $today;
         $comp_transaction->transaction_by = $complaint_update->last_remarks_by;
         $comp_transaction->remarks = $complaint_update->last_updated_remarks;
         $comp_transaction->transaction_remarks = $daily_service_report->remarks;
         $comp_transaction->save();

        $sp_master = SparePartMaster::where('dsr_id',$daily_service_report->id)->where('status',1)->first();
        if (isset($sp_master)) {
            $sp_master->status = 0;
            $sp_master->save();

            $sp_master_trans = SparePartTransaction::where([['spare_part_master_id',$sp_master->id],['status',1]])->update(['status' => 0]);

            $iss_engineer = IssueEngineer::where([['spare_part_master_id',$sp_master->id],['status',1]])->update(['status' => 0]);

            $iss_engineer_trans = IssueEngineerTransaction::where([['spare_part_master_id',$sp_master->id],['status',1]])->update(['status' => 0]);
        }
    
        $dsr = DailyServiceReport::with('dsr_transaction','client','product','engineer','complaint')->where('id',$dsr_id)->where('status',1)->update(['status' => 0]);

        $dsr_trans = DailyServiceReportTransaction::where([['daily_service_report_id',$daily_service_report->id],['status',1]])->update(['status' => 0]);

        Session::flash('success','You have successfully deleted daily service report');
      // return view('engineer.dsr.confirm-submit',compact('maintenance_type','client_name','branch'));


       return redirect()->route('view-all-daily-service-report');
    }



    public function getComplaintOrAmcDetails(Request $request)
    {
        // dump("working");
        $client_name = $request->client_id;
        $branch = $request->branch;
        $json_arr = [];

        $client_id = Client::where('name',$client_name)->where('branch_name',$branch)->where('status',1)->first()->id;
        
        $maintenance_type = $request->maintenance_type;

        if($maintenance_type){


            $complaint_master_details = Complaint::where('assigned_to',Auth::user()->id)->where('status',1)->get();

            if (isset($complaint_master_details)) {
              $json_arr['complaint_master_details'] = $complaint_master_details;
            }else{
              $json_arr['complaint_master_details'] = [];
            }

            $client_names = Client::select('id')->where('name',$client_name)->where('branch_name',$branch)->where('status',1)->get()->toArray();
                // dd($client_name);
                $client = [];
                foreach ($client_names as $key => $client_name) {
                    array_push($client, $client_name['id']);
                }

                foreach ($client_name as $key => $value) {
                   
                    $assigned_products = AssignProductToClient::with('client','product')->whereIn('client_id',$client)->where('status',1)->get();
                    
                }

                if (isset($assigned_products)) {
                    $json_arr['assigned_products'] = $assigned_products;
                }else{
                    $json_arr['assigned_products'] = [];
                }

            $amc_client_master = ClientAmcMaster::where('client_id',$client_id)->where('status',1)->get();

            if ($amc_client_master->count()) {
              foreach ($amc_client_master as $key => $value) {
              $amc_products = ClientAmcProduct::with('product')->where('client_amc_masters_id',$value->id)->where('status',1)->get();
              }
            }
      
          if (isset($amc_products)) {
            $json_arr['amc_products'] = $amc_products;
          }else{
            $json_arr['amc_products'] = [];
          }

          $amc_details = ClientAmcMaster::where('status',1)->get();

          if (isset($amc_details)) {
            $json_arr['amc_details'] = $amc_details;
          }else{
            $json_arr['amc_details'] = [];
          }

          return response()->json($json_arr);
 
            // return response()->json(array(
            //     'complaint_master_details' => $complaint_master_details,
            //     'amc_details' => $amc_details,
            //     'amc_products' => $amc_products,
            //     'assigned_products' => $assigned_products
            // ),JSON_UNESCAPED_UNICODE);
        }
    }

    public function getBranchName(Request $request)
    {
       $client_id = $request->input('client_id');
       if($client_id){

        $branchname = Client::where('name',$client_id)->where('status',1)->get();

        return response()->json($branchname);

       }
        
    }

    public function getDsrProductDetails(Request $request)
    {
        $product_id = $request->product_id;
         if ($product_id) {
             $products = Product::where('id',$product_id)->where('status',1)->first();
             return response()->json($products);
         }
    }

    public function getContactPersonDetails(Request $request)
    {
        $branch = $request->input('branch');
        $client_name = $request->input('client_id');
        $maintenance_type = $request->maintenance_type;

        // $client_id = Client::where('name',$client_name)->where('branch_name',$branch)->where('status',1)->first()->id;


        if($branch){

        $c_person_details = Client::where('name',$client_name)->where('branch_name',$branch)->where('status',1)->get();


        $client_names = Client::select('id')->where('name',$client_name)->where('branch_name',$branch)->where('status',1)->get()->toArray();
                // dd($client_name);
                $client = [];
                foreach ($client_names as $key => $client_name) {
                    array_push($client, $client_name['id']);
                }

                foreach ($client_name as $key => $value) {
                   
                    $assigned_products = AssignProductToClient::with('client','product')->whereIn('client_id',$client)->where('status',1)->get();
                    
                }


            // $amc_client_master = ClientAmcMaster::where('client_id',$client_id)->where('status',1)->get();
            // foreach ($amc_client_master as $key => $value) {
            //   $amc_products = ClientAmcProduct::with('product')->where('client_amc_masters_id',$value->id)->where('status',1)->get();
            // }

        return response()->json(array(
                'c_person_details' => $c_person_details,
                'assigned_products' => $assigned_products,
                // 'amc_products' => $amc_products
        ));
    
       }

    }

    public function getMaintenanceComplaintDetails(Request $request)
    {
        $complaint_id = $request->complaint_id;

        if($complaint_id)
        {
            $comp_details = Complaint::where('id',$complaint_id)->where('status',1)->first();
            return response()->json($comp_details);
        }
    }

    public function getSparePartDetails(Request $request)
    {
        $spare_part_id = $request->spare_part_id;

        if ($spare_part_id) {
            $spare_part = SparePart::where('id',$spare_part_id)->where('status',1)->first();
            return response()->json($spare_part);
        }
    }

    public function printView($id)
    {
        $dsr_id = Crypt::decrypt($id);

        $dsr = DailyServiceReport::with('dsr_transaction','client','product','engineer','complaint')->where('id',$dsr_id)->where('status',1)->first();

        return view('engineer.dsr.print-view',compact('dsr'));
    }

    public function export(Request $request)
    {
      $dsr_reports = DailyServiceReport::with('dsr_transaction','client','product','engineer','complaint')->where('entry_by',Auth::user()->id)->where('status',1)->orderBy('id','desc')->get();

      try{
            Excel::create('ClientDsrDetails '.date('dmyHis'), function( $excel) use($dsr_reports){
                $excel->sheet('Client-DSR-Details ', function($sheet) use($dsr_reports){
                  $sheet->setTitle('Client-DSR-Details');

                  $sheet->cells('A1:BZ1', function($cells) {
                    $cells->setFontWeight('bold');
                  });
                  
                  $arr = [];
                  $counter = 1;
                    foreach($dsr_reports->chunk(500) as $res):
                        // dd($res);
                        foreach( $res as $k => $v) {
                            $arr[$counter]['Sl No']                           = $k+1;
                            $arr[$counter]['Client Name']                            = $v->client->name;
                            $arr[$counter]['Branch Name']
                                                                                = $v->client->branch_name;
                            if($v->client->region_id != null)
                            {
                                $arr[$counter]['Region Name']               = $v->client->region->name;
                            }else{
                                $arr[$counter]['Region Name']               = '';
                            }

                            $arr[$counter]['Zone Name']                     = $v->client->zone->name;
  
                            $arr[$counter]['Call Receive Date']                  = dateFormat($v->call_receive_date);
                            $arr[$counter]['Call Attend Date']                        = dateFormat($v->call_attend_date);
                            
                            if ($v->product_id) {
                              $arr[$counter]['Product']                           = $v->product->name;
                              $arr[$counter]['Product Group']                           = $v->product->group->name;
                              $arr[$counter]['Product Sl No']                        = $v->product->serial_no;
                              $arr[$counter]['Product Part No']                        = $v->product->part_no;
                              $arr[$counter]['Product Model No']                        = $v->product->model_no;
                            }else{
                              $arr[$counter]['Product']                           = '';
                              $arr[$counter]['Product Group']                           ='';
                              $arr[$counter]['Product Sl No']                        = '';
                              $arr[$counter]['Product Part No']                        = '';
                              $arr[$counter]['Product Model No']                        = '';
                            }
                           

                            $arr[$counter]['Contact Person Name']                           = $v->contact_person_name;
                            $arr[$counter]['Contact Person Ph No']                           = $v->contact_person_ph_no;

                            if ($v->maintenance_type == 1) {
                              $arr[$counter]['Maintenance Type']                           = 'Break Down';
                            }
                            if ($v->maintenance_type == 2) {
                              $arr[$counter]['Maintenance Type']                           = 'Preventive';
                            }

                            $arr[$counter]['Complaint No']                           = $v->complaint_no;

                            if ($v->complaint_status == 2) {
                              $arr[$counter]['Complaint Status']                           = 'Under Process';
                            }

                            if ($v->complaint_status == 3) {
                              $arr[$counter]['Complaint Status']                           = 'Closed';
                            }

                            if ($v->complaint_status == null) {
                              $arr[$counter]['Complaint Status']                           = '';
                            }

                            $arr[$counter]['Nature Of Complaint By Customer']                           = $v->nature_of_complaint_by_customer;

                            $arr[$counter]['Fault Observation By Engineer']                           = $v->fault_observation_by_engineer;

                            $arr[$counter]['Action Taken By Engineer']                           = $v->action_taken_by_engineer;

                            $arr[$counter]['Remarks']                           = $v->remarks;
                            
                            // Transactions
                            foreach ($v->dsr_transaction as $key1 => $value1){
                                                           
                              if($key1 > 0){

                                  // blank all other field
                                  $arr[$counter]['Sl No']                             = '';
                                  $arr[$counter]['Client Name']                       = '';
                                  $arr[$counter]['Branch Name']                       = '';
                                                                                      
                                  $arr[$counter]['Region Name']                       = '';
                               
                                  $arr[$counter]['Zone Name']                         = '';
        
                                  $arr[$counter]['Call Receive Date']                 = '';
                                  $arr[$counter]['Call Attend Date']                  = '';
       
                                  $arr[$counter]['Product']                           = '';
                                  $arr[$counter]['Product Group']                     = '';
                                  $arr[$counter]['Product Sl No']                     = '';
                                  $arr[$counter]['Product Part No']                   = '';
                                  $arr[$counter]['Product Model No']                  = '';

                                  $arr[$counter]['Contact Person Name']               = '';
                                  $arr[$counter]['Contact Person Ph No']              = '';

                                  $arr[$counter]['Maintenance Type']                  = '';
                                  
                                  $arr[$counter]['Complaint No']                      = '';

                                  $arr[$counter]['Complaint Status']                  = '';
                                

                                  $arr[$counter]['Nature Of Complaint By Customer']   = '';

                                  $arr[$counter]['Fault Observation By Engineer']     = '';

                                  $arr[$counter]['Action Taken By Engineer']          = '';

                                  $arr[$counter]['Remarks']                           = '';

                                }

                                $arr[$counter]['Spare Part Name']                           = $value1->spare_part->name;
                                $arr[$counter]['Spare Part No']                        = $value1->spare_part->part_no;
  
                                if ($value1->spare_part_quantity == 0) {
                                  $arr[$counter]['Supplied Spare Part Quantity']                        = '0';
                                }else{
                                  $arr[$counter]['Supplied Spare Part Quantity']                        = $value1->spare_part_quantity;
                                }

                                if ($value1->spare_part_taken_back == 1) {
                                  $arr[$counter]['Spare Part Taken Back']                           = 'Yes';
                                }
                                if ($value1->spare_part_taken_back == 0) {
                                  $arr[$counter]['Spare Part Taken Back']                           = 'No';
                                }

                                
                                if ($value1->spare_part_taken_back_quantity == 0) {
                                  $arr[$counter]['Spare Part Taken Back Quantity']                           = '0';
                                }else{
                                  $arr[$counter]['Spare Part Taken Back Quantity']                           = $value1->spare_part_taken_back_quantity;
                                }

                                if ($value1->unit_price_free == 0) {
                                   $arr[$counter]['Unit Price Free']                           = '0';
                                }else{
                                  $arr[$counter]['Unit Price Free']                           = $value1->unit_price_free;
                                }

                                if ($value1->unit_price_chargeable == 0) {
                                  $arr[$counter]['Unit Price Chargeable']                           = '0';
                                }else{
                                  $arr[$counter]['Unit Price Chargeable']                           = $value1->unit_price_chargeable;
                                }

                                if ($value1->labour_free == 1) {
                                  $arr[$counter]['Labour Charge']                           = 'Free';
                                }
                                if ($value1->labour_free == 0) {
                                  $arr[$counter]['Labour Charge']                           = 'Chargeable';
                                }

                              // Check transaction are in the same index print and remove from records

                                if (isset($v->dsr_transaction[$key1])) {

                                $arr[$counter]['Spare Part Name']                           = $value1->spare_part->name;
                                $arr[$counter]['Spare Part No']                        = $value1->spare_part->part_no;
  
                                if ($value1->spare_part_quantity == 0) {
                                  $arr[$counter]['Supplied Spare Part Quantity']                        = '0';
                                }else{
                                  $arr[$counter]['Supplied Spare Part Quantity']                        = $value1->spare_part_quantity;
                                }

                                if ($value1->spare_part_taken_back == 1) {
                                  $arr[$counter]['Spare Part Taken Back']                           = 'Yes';
                                }
                                if ($value1->spare_part_taken_back == 0) {
                                  $arr[$counter]['Spare Part Taken Back']                           = 'No';
                                }

                                
                                if ($value1->spare_part_taken_back_quantity == 0) {
                                  $arr[$counter]['Spare Part Taken Back Quantity']                           = '0';
                                }else{
                                  $arr[$counter]['Spare Part Taken Back Quantity']                           = $value1->spare_part_taken_back_quantity;
                                }

                                if ($value1->unit_price_free == 0) {
                                   $arr[$counter]['Unit Price Free']                           = '0';
                                }else{
                                  $arr[$counter]['Unit Price Free']                           = $value1->unit_price_free;
                                }

                                if ($value1->unit_price_chargeable == 0) {
                                  $arr[$counter]['Unit Price Chargeable']                           = '0';
                                }else{
                                  $arr[$counter]['Unit Price Chargeable']                           = $value1->unit_price_chargeable;
                                }

                                if ($value1->labour_free == 1) {
                                  $arr[$counter]['Labour Charge']                           = 'Free';
                                }
                                if ($value1->labour_free == 0) {
                                  $arr[$counter]['Labour Charge']                           = 'Chargeable';
                                }
                              
                                  unset($v->dsr_transaction[$key1]);
                                }

                            $counter++ ;
                              
                            }
                        }
                  
                     endforeach;

                     // dd($arr);
                    $sheet->fromArray($arr, null, 'A1', false, true);
                });
                $this->setExcelHeader($excel);
            })->download('xlsx');
        }
        catch(Exception $e)
        {
            Session::flash('error','Unable to export !');
            return Redirect::back();
        }

        Session::flash('success','Successfully exported client amc details');
        return Redirect::route('view-all-daily-service-report');
    }

    private function setExcelHeader(&$excel) {
        $excel->setCreator("Chandra Enterprise");
        $excel->setLastModifiedBy("Chandra Enterprise");
        $excel->setCompany("Web.Com (India) Pvt. Ltd.");
        $excel->setManager("Web.Com (India) Pvt. Ltd.");
        $excel->setSubject("Chandra Enterprise Excel Export");
        $excel->setKeywords("Chandra Enterprise Excel Export");
        return $excel;
    }

}
