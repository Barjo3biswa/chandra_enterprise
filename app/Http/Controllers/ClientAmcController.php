<?php

namespace App\Http\Controllers;

use App\Models\Assign\AmcAssignedToEngineers;
use Illuminate\Http\Request;
use Session,DB,Crypt,Validator,Excel,Auth,Redirect;
use App\Models\Assign\AssignEngineer;
use App\Models\Dsr\DailyServiceReport;
use View;

use App\Models\RosterMaster, App\Models\ClientAmcMaster, App\Models\ClientAmcTransaction, App\Models\Zone, App\Models\Client, App\Models\Assign\AssignProductToClient, App\Models\ClientAmcProduct, App\Models\AmcBillRaise;
use Log;

class ClientAmcController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dump(getDuration(25));
        // dd("halt here");
        $clients = Client::where('status',1)->get();
        $c_group_by = Client::where('status',1)->groupBy('name')->get();
        $rosters = RosterMaster::where('status',1)->get();
        $client_amcs = ClientAmcMaster::with('client.zone','roster')->where('status',1);
        $zones = Zone::whereStatus(1)->pluck("name", "id")->toArray();
        if ($request->client_id) {

            $client_names = Client::select('id')->where('name','like','%'.$request->client_id.'%')->where('status',1)->get()->toArray();
                
            $clients = [];
            foreach ($client_names as $key => $client_name) {
                array_push($clients, $client_name['id']);
            }

            foreach ($client_name as $key => $value) {
               
                $client_amcs = $client_amcs->whereIn('client_id',$clients);
                
            }
     
        }

        if ($request->financial_year) {
           $client_amcs=  $client_amcs->where("financial_year","like",'%'.$request->financial_year.'%');
        }

        if ($request->roster_id) {
           $client_amcs=  $client_amcs->where("roster_id","like",'%'.$request->roster_id.'%');
        }

        if ($request->amc_start_date) {
           $client_amcs=  $client_amcs->where("amc_start_date","like",'%'.$request->amc_start_date.'%');
        }

        if ($request->amc_end_date) {
           $client_amcs=  $client_amcs->where("amc_end_date","like",'%'.$request->amc_end_date.'%');
        }
        $client_amcs->when(request("zone_id"), function($query){
            return $query->whereHas("client", function($sub_query){
                return $sub_query->whereHas("zone", function($sub_sub_query){
                    return $sub_sub_query->where("id", request("zone_id"));
                });
            });
        });
        $client_amcs = $client_amcs->orderBy('id','desc')->get();
        // dd($client_amcs);
         
        return view('admin.amc.index',compact('client_amcs','clients','c_group_by','rosters', 'zones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // dd("ok");
        $zones = Zone::where('status',1)->get();
        foreach ($zones as $key => $value) {
            $clients = Client::where('zone_id',$value->id)->where('status',1)->groupBy('name')->get();
        }


        $rosters = RosterMaster::where('status',1)->get();
        
        return view('admin.amc.create',compact('zones','clients','rosters'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $zone_id = $request->zone_id;
        $roster_id = $request->roster_id;
        $amc_duration = $request->amc_duration;
        $amc_demand = $request->amc_demand;
     
        DB::beginTransaction();
        try{
            if($request->input('amc_start_date') != ''){
                    $req_date = $request->input('amc_start_date');
                    $tdr = str_replace("/", "-", $req_date);
                    $new_mnf_dt = date('Y-m-d',strtotime($tdr));
                }
            else
                $new_mnf_dt = " ";

            $amc_start_date = $new_mnf_dt;

            // $amc_end_date = date('Y-m-d', strtotime("+".$amc_duration."month", strtotime($amc_start_date)));

            $amc_end_date = date('Y-m-d', strtotime("+".$amc_duration."month", strtotime($amc_start_date)));
            $amc_end_date = date('Y-m-d', strtotime("-1 day", strtotime($amc_end_date)));
            // dd($amc_end_date);

            $validator = Validator::make($request->all(), ClientAmcMaster::$rules);

            if ($validator->fails()) return  Redirect::back()->withErrors($validator)->withInput();

            $clientAmcMaster = new ClientAmcMaster();

            $client_name = $request->client_id;
            $branch = $request->branch;
            $client_id = Client::where('branch_name',$branch)->where('name',$client_name)->first()->id;
            $clientAmcMaster->client_id = $client_id;

            $clientAmcMaster->roster_id = $roster_id;
            $clientAmcMaster->amc_start_date = $amc_start_date;
            $clientAmcMaster->amc_end_date = $amc_end_date;


            $c = count($request->amc_rqst_date)-1;

            if($request->amc_rqst_date[$c] != ''){
                    $req_date_new = $request->amc_rqst_date[$c];
                    $tdr_new = str_replace("/", "-", $req_date_new);
                    $new_mnf_dt_new = date('Y-m-d',strtotime($tdr_new));
                }
            else
                $new_mnf_dt_new = " ";

            $amc_rqst_date[$c] = $new_mnf_dt_new;

            // $c = count($amc_rqst_date)-1;
        
            // dd($amc_rqst_date[$c]);

            // $clientAmcMaster->amc_end_date = $amc_rqst_date[$c];

            // dd($amc_start_date);

        
            $financial_years = getFinacialDate($amc_start_date ,true);

            $clientAmcMaster->financial_year = $financial_years;

            $clientAmcMaster->amc_duration = $amc_duration;

            if ($amc_demand != null) {
                $clientAmcMaster->amc_amount = $amc_demand;
            }else{
                $clientAmcMaster->amc_amount = 0;
            }

            // $clientAmcMaster->amc_amount = $amc_demand;

            // $clientAmcMaster->amc_bill_no = $request->amc_bill_no; 

            // if($request->input('amc_bill_date') != ''){
            //         $req_date_bill = $request->input('amc_bill_date');
            //         $tdr_bill = str_replace("/", "-", $req_date_bill);
            //         $new_mnf_dt_bill = date('Y-m-d',strtotime($tdr_bill));
            //     }
            // else
            //     $new_mnf_dt_bill = " ";

            // $amc_bill_date = $new_mnf_dt_bill;

            // $clientAmcMaster->amc_bill_date = $amc_bill_date;



            $clientAmcMaster->save();

            $validator = Validator::make($request->all(), ClientAmcProduct::$rules);

            if ($validator->fails()) return  Redirect::back()->withErrors($validator)->withInput();

            if ($request->product_detail) {
                foreach ($request->product_detail as $key1 => $value1) {
                    $client_amc_product = new ClientAmcProduct();
                    $client_amc_product->client_amc_masters_id = $clientAmcMaster->id;
                    $client_amc_product->product_id = $request->product_detail[$key1];
                    $client_amc_product->save();
                }
            }

            $validator = Validator::make($request->all(), ClientAmcTransaction::$rules);

            if ($validator->fails()) return  Redirect::back()->withErrors($validator)->withInput();

            if ($request->amc_rqst_date) {
                $flag=0;
                foreach ($request->amc_rqst_date as $key => $value) {
                    $clientAmcMasterTransaction = new ClientAmcTransaction();
                    $clientAmcMasterTransaction->client_amc_masters_id = $clientAmcMaster->id;
                    if($request->amc_rqst_date[$key] != ''){
                        $req_date1 = $request->amc_rqst_date[$key];
                        $tdr1 = str_replace("/", "-", $req_date1);
                        $new_mnf_dt1 = date('Y-m-d',strtotime($tdr1));
                    }
                    else{
                        $new_mnf_dt1 = " ";
                    }
                           
                        $amc_rqst_date[$key] = $new_mnf_dt1;
                        $clientAmcMasterTransaction->amc_rqst_date = $amc_rqst_date[$key];
                        $amc_mnth = date("F",strtotime($amc_rqst_date[$key]));
                        $amc_yr = date("Y",strtotime($amc_rqst_date[$key]));              
                        $clientAmcMasterTransaction->amc_month = $amc_mnth;
                        $clientAmcMasterTransaction->amc_year = $amc_yr;
                        // $amc_fin_year = date("m",strtotime($amc_rqst_date[$key]));
                        // $financial_years = getFinacialDate($amc_rqst_date[$key] ,true);
                        // $clientAmcMasterTransaction->financial_year = $financial_years;
                        // $clientAmcMasterTransaction->amc_demand = $amc_demand;
                        $today = date('Y-m-d');
                        $clientAmcMasterTransaction->amc_demand_date = $today;
                        if ($request->amc_demand_collected != null) {
                            $clientAmcMasterTransaction->amc_demand_collected = $request->amc_demand_collected[$key];
                        }else{
                            $clientAmcMasterTransaction->amc_demand_collected = 0;
                        }        
                        $clientAmcMasterTransaction->amc_demand_collected_date = $request->amc_demand_collected_date[$key];
                        $clientAmcMasterTransaction->amc_status = $clientAmcMaster->status;
                        $clientAmcMasterTransaction->amc_done_on = $today;
                        $clientAmcMasterTransaction->remarks = $request->remarks[$key];
                        $clientAmcMasterTransaction->amc_transaction_remarks = $request->remarks[$key];
                        $clientAmcMasterTransaction->save();

                        $status = 0;
                        if( $flag==0){
                            $status = 1;
                            $flag = 1 ;
                        }
                        // dd($client_id,$zone_id);
                        $engineer = AssignEngineer::/* where('client_id',$client_id)-> */where('zone_id',$zone_id)->where('status',1)->first();
                        if($engineer==null){
                            return redirect()->back()->with('error','No Engineer is assigned to the client of that Zone, Assign First');
                        }
                        // dd($client_id,$zone_id);
                        $data = [
                            'client_amc_master_id' => $clientAmcMasterTransaction->client_amc_masters_id,
                            'client_amc_trans_id' => $clientAmcMasterTransaction->id,
                            "engineer_id"   => $engineer->engineer_id,
                            "remark"        => "Auto Assigned",
                            "status"        => $status,
                        ];
                        // dd($data);
                        AmcAssignedToEngineers::create($data);
                }

                
                // dd($engineer);
            }
            DB::commit();
        }catch(\Exception $e){
            Session::flash('error','Something Went Wrong');
            DB::rollback();
        }

        Session::flash('success','Successfully added client AMC details');
        return redirect()->route('view-all-client-amc');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $r_id = Crypt::decrypt($id);
        $amc_detail = ClientAmcMaster::with('client','roster','amc_bill', 'assigned_engineers.engineer')->where('id',$r_id)
        // ->whereHas("assigned_engineers", function($sub_query){
        //     return $sub_query->whereHas("engineer", function($sub_sub_query){
        //         return $sub_sub_query->where("status", "!=", 0);
        //     });
        // })
        ->where('status',1)->first();
        $client_amc_product = ClientAmcProduct::with('product')->where('client_amc_masters_id',$amc_detail->id)->where('status',1)->get();

        $amc_transaction_details = ClientAmcTransaction::where('client_amc_masters_id',$amc_detail->id)->where('status',1)->get();

        $assigned_engineers_zone = AssignEngineer::with('zone')->where('status', 1)->get();
        $all_zones               = [];
        foreach ($assigned_engineers_zone as $key => $value) {
            array_push($all_zones, $value['zone_id']);
        }
        
        $zones              = Zone::whereIn('id', $all_zones)->where('status', 1)->get();
        $client_amc_product = ClientAmcProduct::with('product')
            ->where('client_amc_masters_id', $amc_detail->id)->where('status', 1)
            ->whereHas("product", function($query){
                return $query->where("status", 1);
            })
            ->get();
        $amc_transaction_details = ClientAmcTransaction::where('client_amc_masters_id', $amc_detail->id)
            ->where('status', 1)
            ->get();
        $all_engineers_belongs_to_client_zone = AssignEngineer::with(["user" => function($select_engineers){
            return $select_engineers->select(["id", "first_name", "middle_name", "last_name", "emp_code"]);
        }])->whereHas("zone", function($query) use ($amc_detail){
            return $query->where("zone_id", $amc_detail->client->zone_id);
        })->whereHas("user", function($query){
            return $query->where("status", "!=", 0);
        })
        ->where("client_id",  $amc_detail->client_id)
        ->groupBy("engineer_id")
        ->get();


        return view('admin.amc.show',compact('amc_detail','amc_transaction_details','client_amc_product','assigned_engineers_zone', 'zones', "all_engineers_belongs_to_client_zone"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $r_id = Crypt::decrypt($id);
        $amc_detail = ClientAmcMaster::with('client','roster','amc_master_product')->where('id',$r_id)->where('status',1)->first();

        $client_amc_product = ClientAmcProduct::with('product')
        ->where('client_amc_masters_id',$amc_detail->id)
        // ->whereHas("product", function($query){
        //     return $query->where('status', 1);
        // })
        ->where('status',1)
        ->get();

        $amc_transaction_details = ClientAmcTransaction::where('client_amc_masters_id',$amc_detail->id)->where('status',1)->where('status',1)->get();

        $zones = Zone::where('status',1)->get();
        foreach ($zones as $key => $value) {
            $clients = Client::where('zone_id',$value->id)->where('status',1)->groupBy('name')->get();
        }


        $rosters = RosterMaster::where('status',1)->get();

        $all_clients = Client::where('status',1)->get();
      
        return view('admin.amc.edit',compact('amc_detail','amc_transaction_details','zones','clients','rosters','all_clients','client_amc_product'));
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
        $zone_id = $request->zone_id;
        $roster_id = $request->roster_id;
        $amc_duration = $request->amc_duration;
        $amc_demand = $request->amc_demand;
     

        if($request->input('amc_start_date') != ''){
                $req_date = $request->input('amc_start_date');
                $tdr = str_replace("/", "-", $req_date);
                $new_mnf_dt = date('Y-m-d',strtotime($tdr));
            }
        else
            $new_mnf_dt = " ";

        $amc_start_date = $new_mnf_dt;

        $amc_end_date = date('Y-m-d', strtotime("+".$amc_duration."month", strtotime($amc_start_date)));

        $validator = Validator::make($request->all(), ClientAmcMaster::$rules);

        if ($validator->fails()) return  Redirect::back()->withErrors($validator)->withInput();

        $r_id = Crypt::decrypt($id);
        $clientAmcMaster = ClientAmcMaster::with('client','roster')->where('id',$r_id)->where('status',1)->first();

        $client_name = $request->client_id;
        $branch = $request->branch;

        $client_id = Client::where('branch_name',$branch)->where('name',$client_name)->first()->id;
        // dd($client_id);

        $clientAmcMaster->client_id = $client_id;

        $clientAmcMaster->roster_id = $roster_id;
        $clientAmcMaster->amc_start_date = $amc_start_date;
        $clientAmcMaster->amc_end_date = $amc_end_date;

        $c = count($request->amc_rqst_date)-1;

        if($request->amc_rqst_date[$c] != ''){
                $req_date_new = $request->amc_rqst_date[$c];
                $tdr_new = str_replace("/", "-", $req_date_new);
                $new_mnf_dt_new = date('Y-m-d',strtotime($tdr_new));
            }
        else
            $new_mnf_dt_new = " ";

        $amc_rqst_date[$c] = $new_mnf_dt_new;

        // $c = count($amc_rqst_date)-1;
    
        // dd($amc_rqst_date[$c]);

        // $clientAmcMaster->amc_end_date = $amc_rqst_date[$c];

        // dd($amc_start_date);

     
        $financial_years = getFinacialDate($amc_start_date ,true);

        $clientAmcMaster->financial_year = $financial_years;

        $clientAmcMaster->amc_duration = $amc_duration;

        if ($amc_demand != null) {
            $clientAmcMaster->amc_amount = $amc_demand;
        }else{
            $clientAmcMaster->amc_amount = 0;
        }

        // $clientAmcMaster->amc_amount = $amc_demand; 

        // $clientAmcMaster->amc_bill_no = $request->amc_bill_no; 

        // if($request->input('amc_bill_date') != ''){
        //         $req_date_bill = $request->input('amc_bill_date');
        //         $tdr_bill = str_replace("/", "-", $req_date_bill);
        //         $new_mnf_dt_bill = date('Y-m-d',strtotime($tdr_bill));
        //     }
        // else
        //     $new_mnf_dt_bill = " ";

        // $amc_bill_date = $new_mnf_dt_bill;

        // $clientAmcMaster->amc_bill_date = $amc_bill_date;

        $clientAmcMaster->save();

        $validator = Validator::make($request->all(), ClientAmcProduct::$rules);

        if ($validator->fails()) return  Redirect::back()->withErrors($validator)->withInput();

        $old_client_amc_product = ClientAmcProduct::where('client_amc_masters_id',$clientAmcMaster->id)->get();
        foreach ($old_client_amc_product as $key2 => $value2) {
                   $value2->status = 0;
                   $value2->save();
               }


        if ($request->product_detail) {
            foreach ($request->product_detail as $key1 => $value1) {
                $client_amc_product = new ClientAmcProduct();
                $client_amc_product->client_amc_masters_id = $clientAmcMaster->id;
                $client_amc_product->product_id = $request->product_detail[$key1];
                $client_amc_product->save();
            }
        }

        $validator = Validator::make($request->all(), ClientAmcTransaction::$rules);

        if ($validator->fails()) return  Redirect::back()->withErrors($validator)->withInput();

         $old_clientAmcMasterTransaction = ClientAmcTransaction::where('client_amc_masters_id',$clientAmcMaster->id)->get();
               foreach ($old_clientAmcMasterTransaction as $key1 => $value1) {
                   $value1->status = 0;
                   $value1->save();
               }


        if ($request->amc_rqst_date) {

            foreach ($request->amc_rqst_date as $key => $value) {


               $clientAmcMasterTransaction = new ClientAmcTransaction();
               $clientAmcMasterTransaction->client_amc_masters_id = $clientAmcMaster->id;

               if($request->amc_rqst_date[$key] != ''){
                $req_date1 = $request->amc_rqst_date[$key];
                $tdr1 = str_replace("/", "-", $req_date1);
                $new_mnf_dt1 = date('Y-m-d',strtotime($tdr1));
                    }
                else
                    $new_mnf_dt1 = " ";

                $amc_rqst_date[$key] = $new_mnf_dt1;

               $clientAmcMasterTransaction->amc_rqst_date = $amc_rqst_date[$key];

               $amc_mnth = date("F",strtotime($amc_rqst_date[$key]));
               $amc_yr = date("Y",strtotime($amc_rqst_date[$key]));

               
               $clientAmcMasterTransaction->amc_month = $amc_mnth;

               $clientAmcMasterTransaction->amc_year = $amc_yr;


               // $amc_fin_year = date("m",strtotime($amc_rqst_date[$key]));


               // $financial_years = getFinacialDate($amc_rqst_date[$key] ,true);

            
               // $clientAmcMasterTransaction->financial_year = $financial_years;


               // $clientAmcMasterTransaction->amc_demand = $amc_demand;

               $today = date('Y-m-d');
               $clientAmcMasterTransaction->amc_demand_date = $today;
               if ($request->amc_demand_collected != null) {
                   $clientAmcMasterTransaction->amc_demand_collected = $request->amc_demand_collected[$key];
               }else{
                $clientAmcMasterTransaction->amc_demand_collected = 0;
               }
               $clientAmcMasterTransaction->amc_demand_collected_date = $request->amc_demand_collected_date[$key];
               $clientAmcMasterTransaction->amc_status = $clientAmcMaster->status;
               $clientAmcMasterTransaction->amc_done_on = $today;
               $clientAmcMasterTransaction->remarks = $request->remarks[$key];
               $clientAmcMasterTransaction->amc_transaction_remarks = $request->remarks[$key];

               $clientAmcMasterTransaction->save();

            }
        }

        Session::flash('success','Successfully Updated client AMC details');
        return redirect()->route('view-all-client-amc');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $r_id = Crypt::decrypt($id);
        $clientAmcMaster = ClientAmcMaster::with('client','roster')->where('id',$r_id)->where('status',1)->first();
        $clientAmcMaster->status = 0;
        $clientAmcMaster->save();

        $client_amc_product = ClientAmcProduct::where('client_amc_masters_id',$clientAmcMaster->id)->update(['status' => 0]);

        $clientAmcMasterTransaction = ClientAmcTransaction::where('client_amc_masters_id',$clientAmcMaster->id)->update(['status' => 0]);

        Session::flash('success','Successfully deleted client AMC details');
        return redirect()->route('view-all-client-amc');

    }

    public function zoneWiseClientDetails(Request $request)
    {
        $zone_id = $request->zone_id;

        if ($zone_id) {
            $clients = Client::where('zone_id',$zone_id)->where('status',1)->groupBy('name')->get();
            // $rosters = RosterMaster::where('status',1)->get();

            // dd($clients);
            // foreach ($clients as $key => $value) {
            //     $all_assigned_clients = AssignProductToClient::where('client_id',$value->id)->where('status',1)->get();
            // }

            // dd($all_assigned_clients);
            return response()->json($clients);
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

    // public function getClientAmcDetails(Request $request)
    // {
    //     $amc_start_date = $request->amc_start_date;
    //     $roster_master_id = $request->roster_id;

    //     $amc_duration = $request->amc_duration;


    //     $roster_id = RosterMaster::where('id',$roster_master_id)->where('status',1)->first()->roster_count;
        
    //     if ($roster_id && $amc_start_date && $amc_duration) {

    //         $end = date("Y-m-d", strtotime(date("Y-m-d", strtotime($amc_start_date)) . " + 1 month"));

         
    //         return response()->json(array(
    //             'roster_id' => $roster_id,
    //             'amc_start_date' => $amc_start_date,
    //             'amc_duration' => $amc_duration,
    //             'end' => $end
    //         ));
    //     }
   
    // }


    public function export(Request $request)
    {
        //below function is modified. and the latest function.
        return $this->export_as_chandra($request);
        $client_amc = ClientAmcMaster::with('client','roster','amc_master_transaction','amc_master_product','amc_bill')->where('status',1)->orderBy('id','desc')->get();

        try{
            Excel::create('ClientAMCDetails '.date('dmyHis'), function( $excel) use($client_amc){
                $excel->sheet('Client-AMC-Details ', function($sheet) use($client_amc){
                    $sheet->setTitle('Client-AMC-Details');

                    $sheet->cells('A1:Z1', function($cells) {
                        $cells->setFontWeight('bold');
                    });
                  
                    $arr = [];
                    $counter = 1;
                    foreach($client_amc->chunk(500) as $res):
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
  
                            // $arr[$counter]['Product']                           = '';
                            // $arr[$counter]['Product Sl No']                        = '';

                            $arr[$counter]['AMC Start Date']                  = dateFormat($v->amc_start_date);
                            $arr[$counter]['AMC End Date']                        = dateFormat($v->amc_end_date);

                            $arr[$counter]['AMC Duration']                        = getDuration($v->amc_duration);

                            $arr[$counter]['Product']                           = '';
                            $arr[$counter]['Product Sl No']                        = '';

                            $temp_counter = $counter;
                            // Products
                            $key1 = 0;
                            foreach ($v->amc_master_product as $key1 => $value1){
                                 if($key1 > 0){
                                    // blank all other field
                                    $arr[$temp_counter]['Sl No']                           = '';
                                    $arr[$temp_counter]['Client Name']                            = '';
                                    $arr[$temp_counter]['Branch Name']              ='';
                                                        
                                    if($v->client->region_id != null){
                                        $arr[$temp_counter]['Region Name']               = '';
                                    }else{
                                        $arr[$temp_counter]['Region Name']               = '';
                                    }

                                    $arr[$temp_counter]['Zone Name']                     = '
                                    ';
          
                                    

                                    $arr[$temp_counter]['AMC Start Date']                  = '';
                                    $arr[$temp_counter]['AMC End Date']                        = '';

                                    $arr[$temp_counter]['AMC Duration']                        = '';


                                    // $arr[$counter]['Bill Name']                         = '';
                                    // $arr[$counter]['Bill No']                         = '';
                                }

                                $arr[$temp_counter]['Product']                           = $value1->product->name;
                                $arr[$temp_counter]['Product Sl No']                        = $value1->product->serial_no;

                                if (isset($v->amc_master_product[$key1])) {
                                

                                   unset($v->amc_master_product[$key1]);
                                }

                                $temp_counter++ ;
                            }

                            // Transactions
                            // $trans_counter = $counter;
                            $key = 0;
                            foreach ($v->amc_master_transaction as $key => $value) {
                                if($key > 0){
                                    // blank all other field
                                    $arr[$counter]['Sl No']                           = '';
                                    $arr[$counter]['Client Name']                            = '';
                                    $arr[$counter]['Branch Name']              ='';
                                                        
                                    if($v->client->region_id != null){
                                        $arr[$counter]['Region Name']               = '';
                                    }else{
                                        $arr[$counter]['Region Name']               = '';
                                    }

                                    $arr[$counter]['Zone Name']                     = '
                                    ';
          
                                    // $arr[$counter]['Product']                           = '';
                                    // $arr[$counter]['Product Sl No']                        = '';

                                    $arr[$counter]['AMC Start Date']                  = '';
                                    $arr[$counter]['AMC End Date']                        = '';

                                    $arr[$counter]['AMC Duration']                        = '';
                                    $arr[$counter]['Product']                           = '';
                                    $arr[$counter]['Product Sl No']                         ='';


                                    // $arr[$counter]['Bill Name']                         = '';
                                    // $arr[$counter]['Bill No']                         = '';
                                }
                                // Check type monthly/quaterly/half-yearly/yearly
                                $arr[$counter]['Type']                          = $v->roster->roster_name;

                                if($v->roster_id == 1){
                                    // monthly
                                    ##################################################################
                                    $arr[$counter]['Period']                            = ($key +1).orginal_suffix($key +1).' '.' Month';
                                    ##################################################################
                                }else if($v->roster_id == 2){
                                    // quaterly
                                    ##################################################################
                                    $arr[$counter]['Period']                            =  ($key +1).orginal_suffix($key +1).' '.' Quater';
                                    ##################################################################
                                }
                                else if($v->roster_id == 3){
                                    // half-yearly
                                    ##################################################################
                                    $arr[$counter]['Period']                            =  ($key +1).orginal_suffix($key +1).' '.' Half year';
                                   
                                    ##################################################################
                                }
                                else if($v->roster_id == 4){
                                    // yearly
                                    ##################################################################
                                    $arr[$counter]['Period']                            = ' Yearly';
                                    ##################################################################
                                }
                                

                                $arr[$counter]['Collected Date']                  = dateFormat($value->amc_demand_collected_date);
                                $arr[$counter]['Collected Amount']                = $value->amc_demand_collected;
                                $arr[$counter]['Remarks']                         = $value->remarks;

                                if (isset($v->amc_master_transaction[$key])) {

                                    // dump($arr);
                                    unset($v->amc_master_transaction[$key]);
                                }
  
                                $counter ++;
                            }
                           $max_row = (($key > $key1) ? $key : $key1)+1;
                            for ($i=($counter-$max_row); $i < $counter; $i++) { 
                                $arr[$i]['Bill Name']                         = "";
                                $arr[$i]['Bill No']                         = "";
                                $arr[$i]['Bill Date From']                         = "";
                                $arr[$i]['Bill Date To']                         = "";
                                $arr[$i]['Bill Date']                         = "";
                                $arr[$i]['Bill Amount']                         = "";
                                $arr[$i]['Bill Paid On Date']                         = "";
                                $arr[$i]['Bill Amount Paid']                         = "";
                                $arr[$i]['Last Bill Followed By']                         = "";
                                $arr[$i]['Bill Followed Date']                         = "";
                                $arr[$i]['Bill Remarks']                         = "";
                            }
                            // Amc bills
                            $total_bill_row = $v->amc_bill->count();
                            foreach ($v->amc_bill as $key2 => $value2) {
                                if($key2 >= $max_row){
                                    $arr[$counter]['Sl No']                           = '';
                                    $arr[$counter]['Client Name']                       = '';
                                    $arr[$counter]['Branch Name']                       = '';
                                                        
                                    if($v->client->region_id != null){
                                        $arr[$counter]['Region Name']               = '';
                                    }else{
                                        $arr[$counter]['Region Name']               = '';
                                    }

                                    $arr[$counter]['Zone Name']                     = '';
           
                                    $arr[$counter]['AMC Start Date']                  = '';
                                    $arr[$counter]['AMC End Date']                        = '';

                                    $arr[$counter]['AMC Duration']                        = '';
                                    $arr[$counter]['Product']                           = '';
                                    $arr[$counter]['Product Sl No']                         ='';


                                    $arr[$counter]['Type']                          = '';

                                    if($v->roster_id == 1){
                                         $arr[$counter]['Period']                            = '';
                                    }else if($v->roster_id == 2){
                                         $arr[$counter]['Period']                            = '';
                                    }
                                    else if($v->roster_id == 3){
                                        $arr[$counter]['Period']                            = '';
                                    }
                                    else if($v->roster_id == 4){
                                        $arr[$counter]['Period']                            = '';
                                    }
                                    

                                    $arr[$counter]['Collected Date']                  = '';
                                    $arr[$counter]['Collected Amount']                = '';
                                    $arr[$counter]['Remarks']                         = '';
                                    $arr[$counter]['Bill Name']                       = $value2->bill_name;
                                    $arr[$counter]['Bill No']                         = $value2->bill_no;

                                    $arr[$i]['Bill Date From']                         = dateFormat($value2->bill_from_date);
                                    $arr[$i]['Bill Date To']                         = dateFormat($value2->bill_to_date);
                                    $arr[$i]['Bill Date']                         = dateFormat($value2->bill_date);
                                    $arr[$i]['Bill Amount']                         = $value2->bill_amount;
                                    $arr[$i]['Bill Paid On Date']                         = dateFormat($value2->paid_on_date);
                                    $arr[$i]['Bill Amount Paid']                         = $value2->amount_paid;
                                    $arr[$i]['Last Bill Followed By']                         = ucwords($value2->user->first_name.' '.$value2->user->middle_name.' '.$value2->user->last_name);
                                    $arr[$i]['Bill Followed Date']                         = dateFormat($value2->last_follow_up_date);
                                    $arr[$i]['Bill Remarks']                         = $value2->bill_remarks;
                                    $counter ++;
                                }else{

                                    // dump("replaced foreach ".(($counter + $key2) - $max_row));
                                    $arr[($counter + $key2) - $max_row]['Bill Name']                         = $value2->bill_name;
                                    $arr[($counter + $key2) - $max_row]['Bill No']                         = $value2->bill_no;
                                    $arr[($counter + $key2) - $max_row]['Bill Date From']                         = dateFormat($value2->bill_from_date);
                                    $arr[($counter + $key2) - $max_row]['Bill Date To']                         = dateFormat($value2->bill_to_date);
                                    $arr[($counter + $key2) - $max_row]['Bill Date']                         = dateFormat($value2->bill_date);
                                    $arr[($counter + $key2) - $max_row]['Bill Amount']                         = $value2->bill_amount;
                                    $arr[($counter + $key2) - $max_row]['Bill Paid On Date']                         = dateFormat($value2->paid_on_date);
                                    $arr[($counter + $key2) - $max_row]['Bill Amount Paid']                         = $value2->amount_paid;
                                    $arr[($counter + $key2) - $max_row]['Last Bill Followed By']                         = ucwords($value2->user->first_name.' '.$value2->user->middle_name.' '.$value2->user->last_name);
                                    $arr[($counter + $key2) - $max_row]['Bill Followed Date']                         = dateFormat($value2->last_follow_up_date);
                                    $arr[($counter + $key2) - $max_row]['Bill Remarks']                         = $value2->bill_remarks;

                                }

                                if (isset($v->amc_bill[$key2])) {
                                  
                                    unset($v->amc_bill[$key2]);
                                }
  

                            }
                        }
                  
                     endforeach;
                     // unset($arr[sizeof($arr)]);
                     // dump(sizeof($arr));
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
        return Redirect::route('view-all-client-amc');
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



    public function getAssignedProductDetails(Request $request)
    {
        $client_name = $request->client_id;
        $branch = $request->branch;
        $client_id = Client::where('name',$client_name)->where('branch_name',$branch)->whereStatus(1)->first()->id;
        Log::info(Client::where('name',$client_name)->where('branch_name',$branch)->get());
        // dd($client_id);

        if ($client_id) {
            $product_details = AssignProductToClient::with('client','product','company')->where('client_id',$client_id)->where('status',1)->get();
        }
        // dd($product_details);
        return response()->json($product_details);
    }

    public function raiseBill($id)
    {
        $r_id = Crypt::decrypt($id);
        $clientAmcMaster = ClientAmcMaster::with('client','roster','amc_bill')->where('id',$r_id)->where('status',1)->first();
        return view('admin.amc.bill.create',compact('clientAmcMaster'));
    }

    public function raiseBillUpdate(Request $request, $id)
    {
        $r_id = Crypt::decrypt($id);
        $clientAmcMaster = ClientAmcMaster::with('client','roster','amc_bill')->where('id',$r_id)->where('status',1)->first();
        $amc_bill = new AmcBillRaise();
        $amc_bill->client_amc_masters_id = $clientAmcMaster->id ;
        $amc_bill->bill_name = $request->bill_name ;
        $amc_bill->bill_no = $request->bill_no ;


        if($request->input('bill_from_date') != ''){
                $req_date = $request->input('bill_from_date');
                $tdr = str_replace("/", "-", $req_date);
                $new_mnf_dt = date('Y-m-d',strtotime($tdr));
            }
        else
            $new_mnf_dt = " ";

        $bill_from_date = $new_mnf_dt;
        $amc_bill->bill_from_date = $bill_from_date ;

        if($request->input('bill_to_date') != ''){
                $req_date1 = $request->input('bill_to_date');
                $tdr1 = str_replace("/", "-", $req_date1);
                $new_mnf_dt1 = date('Y-m-d',strtotime($tdr1));
            }
        else
            $new_mnf_dt1 = " ";

        $bill_to_date = $new_mnf_dt1;
        $amc_bill->bill_to_date = $bill_to_date ;

        if($request->input('bill_date') != ''){
                $req_date2 = $request->input('bill_date');
                $tdr2 = str_replace("/", "-", $req_date2);
                $new_mnf_dt2 = date('Y-m-d',strtotime($tdr2));
            }
        else
            $new_mnf_dt2 = " ";

        $bill_date = $new_mnf_dt2;
        $amc_bill->bill_date = $bill_date ;

        $amc_bill->bill_amount = $request->bill_amount ;
        $amc_bill->last_follow_up_by = Auth::user()->id ;
        $amc_bill->bill_remarks = $request->bill_remarks ;

        //$amc_bill->last_follow_up_date = date('Y-m-d') ;

        $amc_bill->save();

        Session::flash('success','Successfully saved client amc bill details');
        return Redirect::route('view-all-client-amc');

    }

    public function raiseBillEdit($id)
    {
        $bill_id = Crypt::decrypt($id);
        $amc_bill = AmcBillRaise::where('id',$bill_id)->first();
        return view('admin.amc.bill.edit',compact('amc_bill'));
    }

    public function raiseBillEditUpdate(Request $request, $id)
    {
        $bill_id = Crypt::decrypt($id);
        $amc_bill = AmcBillRaise::where('id',$bill_id)->where('status',1)->first();
        $amc_bill->client_amc_masters_id = $request->client_amc_masters_id ;
        $amc_bill->bill_name = $request->bill_name ;
        $amc_bill->bill_no = $request->bill_no ;


        if($request->input('bill_from_date') != ''){
                $req_date = $request->input('bill_from_date');
                $tdr = str_replace("/", "-", $req_date);
                $new_mnf_dt = date('Y-m-d',strtotime($tdr));
            }
        else
            $new_mnf_dt = " ";

        $bill_from_date = $new_mnf_dt;
        $amc_bill->bill_from_date = $bill_from_date ;

        if($request->input('bill_to_date') != ''){
                $req_date1 = $request->input('bill_to_date');
                $tdr1 = str_replace("/", "-", $req_date1);
                $new_mnf_dt1 = date('Y-m-d',strtotime($tdr1));
            }
        else
            $new_mnf_dt1 = " ";

        $bill_to_date = $new_mnf_dt1;
        $amc_bill->bill_to_date = $bill_to_date ;

        if($request->input('bill_date') != ''){
                $req_date2 = $request->input('bill_date');
                $tdr2 = str_replace("/", "-", $req_date2);
                $new_mnf_dt2 = date('Y-m-d',strtotime($tdr2));
            }
        else
            $new_mnf_dt2 = " ";

        $bill_date = $new_mnf_dt2;
        $amc_bill->bill_date = $bill_date ;

        $amc_bill->bill_amount = $request->bill_amount ;
        $amc_bill->last_follow_up_by = Auth::user()->id ;
        $amc_bill->bill_remarks = $request->bill_remarks ;

        //$amc_bill->last_follow_up_date = date('Y-m-d') ;

        $amc_bill->save();

        Session::flash('success','Successfully updated client amc bill details');
        return Redirect::route('show-client-amc',Crypt::encrypt($amc_bill->client_amc_masters_id)); 
    }

    public function raiseBillDelete($id)
    {
        $bill_id = Crypt::decrypt($id);
        $amc_bill = AmcBillRaise::where('id',$bill_id)->where('status',1)->first();
        $amc_bill->status = 0;
        $amc_bill->save();

        Session::flash('success','Successfully deleted client amc bill details');
        return Redirect::route('show-client-amc',Crypt::encrypt($amc_bill->client_amc_masters_id));
    }

    public function raiseBillEditPayment($id)
    {
        $bill_id = Crypt::decrypt($id);
        $amc_bill = AmcBillRaise::where('id',$bill_id)->where('status',1)->first();
        return view('admin.amc.bill.payment-edit',compact('amc_bill'));
    }

    public function raiseBillEditPaymentUpdate(Request $request,$id)
    {
        $bill_id = Crypt::decrypt($id);
        $amc_bill = AmcBillRaise::where('id',$bill_id)->where('status',1)->first();

        if($request->input('paid_on_date') != ''){
                $req_date = $request->input('paid_on_date');
                $tdr = str_replace("/", "-", $req_date);
                $new_mnf_dt = date('Y-m-d',strtotime($tdr));
            }
        else
            $new_mnf_dt = " ";

        $paid_on_date = $new_mnf_dt;
        $amc_bill->paid_on_date = $paid_on_date;

        $amc_bill->amount_paid = $request->amount_paid;
        $amc_bill->last_follow_up_by = Auth::user()->id;
        $amc_bill->last_follow_up_remarks = $request->last_follow_up_remarks;
        $amc_bill->last_follow_up_date = date('Y-m-d');
        $amc_bill->save();

        Session::flash('success','Successfully deleted client amc bill details');
        return Redirect::route('raise-client-amc-bill-payment-details',Crypt::encrypt($amc_bill->id));
    }

    public function raiseBillPaymentDetails($id)
    {
        $bill_id = Crypt::decrypt($id);
        $amc_bill = AmcBillRaise::where('id',$bill_id)->where('status',1)->first();

        $amc_detail = ClientAmcMaster::with('client','roster','amc_bill','amc_master_product','amc_master_transaction')->where('id',$amc_bill->client_amc_masters_id)->where('status',1)->first();
        return view('admin.amc.bill.show-payment-details',compact('amc_bill','amc_detail'));
    
    }

    public function assignAmcToEngineer(Request $request, $encrypted_id)
    {
        try {
            $id = Crypt::decrypt($encrypted_id);
            $amc_detail = ClientAmcMaster::with(["assigned_engineers.engineer", "client" => function($select_client_field){
                return $select_client_field->select("zone_id", "id", "name", "branch_name");
            }])
            /*>whereHas("assigned_engineers", function($sub_query){
                return $sub_query->whereHas("engineer", function($sub_sub_query){
                    return $sub_sub_query->where("status", "!=", 0);
                });
            }) */
            ->find($id);
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with("error", "Whoops! Something went wrong. try again later.");
        }
        $assigned_engineers_zone = AssignEngineer::with('zone')->where('status', 1)->get();
        $all_zones               = [];
        foreach ($assigned_engineers_zone as $key => $value) {
            array_push($all_zones, $value['zone_id']);
        }
        
        $zones              = Zone::whereIn('id', $all_zones)->where('status', 1)->get();
        $client_amc_product = ClientAmcProduct::with('product')
            ->where('client_amc_masters_id', $amc_detail->id)->where('status', 1)
            ->get();
        $amc_transaction_details = ClientAmcTransaction::where('client_amc_masters_id', $amc_detail->id)
            ->where('status', 1)
            ->get();
        $all_engineers_belongs_to_client_zone = AssignEngineer::with(["user" => function($select_engineers){
            return $select_engineers->select(["id", "first_name", "middle_name", "last_name", "emp_code"]);
        }])->whereHas("zone", function($query) use ($amc_detail){
            return $query->where("zone_id", $amc_detail->client->zone_id);
        })->whereHas("user", function($query){
            return $query->where("status", "!=", 0);
        })
        ->where("client_id",  $amc_detail->client_id)
        ->groupBy("engineer_id")
        ->get();
        return View::make('admin.amc.assign_engineer', compact('amc_detail', 'amc_transaction_details', 'client_amc_product', 'assigned_engineers_zone', 'zones', "all_engineers_belongs_to_client_zone"));
        // return view('admin.amc.assign_engineer', compact('amc_detail', 'amc_transaction_details', 'client_amc_product', 'assigned_engineers_zone', 'zones'));
    }

    public function assignAmcToEngineerPost(Request $request, $encrypted_id)
    {
        $rules = AmcAssignedToEngineers::$rules;
        $data = [
            "engineer_id"   => $request->get("assigned_to"),
            "remark"        => $request->get("transaction_remarks")
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()){
            return redirect()
                ->back()
                ->withInput($request->all())
                ->withErrors($validator)
                ->with("error", "Whoops! Looks like you have missed something.");
        }
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
            $amc_details  = ClientAmcMaster::find($decrypted_id);
            $amc_details->assigned_engineers()->create($data);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return redirect()
                ->back()
                ->with("error", "Whoops! Something went wrong, try again later.");
        }
        return redirect()
                ->route("view-all-client-amc")
                ->with('success', "Engineer Successfully assigned.");
    }
    

    public function export_as_chandra(Request $request)
    {
        // DB::listen(function($query){
        //     dump($query->sql);
        // });
        if($request->get("financial_years")){
            $date = substr($request->get("financial_years"), 0, 4)."-04-01";
            $financial_year = getFinacialDate($date, false);
        }else{
            $financial_year = getFinacialDate(date("Y-m-d"), false);
        }
        $assigned_engineers = AssignEngineer::with(["user" => function($user_select){
            return $user_select->select(["id","first_name", "middle_name", "last_name"]);
        }, 
        "zone" => function($zone_select){
            return $zone_select->select(["id", "name"]);
        },
        "client"    => function($client_select){
            return $client_select->select(["id","zone_id", "region_id", "name", 'branch_name', "status", "isAssignedToEngineer", "isAssigned"]);
        },
        "client.assigned_products" => function($assigned_products_select){
            return $assigned_products_select->select([
                "client_id",
                "company_id",
                "product_id",
                "date_of_install",
                "status",
            ]);
        },
        "client.assigned_products.product" => function($products_select){
            return $products_select->select([
                "id",
                "name",
                "date_of_purchase",
                "serial_no",
                "brand",
                "model_no",
                "status",
                "group_id",
            ]);
        },
        "client.assigned_products.product.brand" => function($brand_select){
            return $brand_select->select([
                "id",
                "name",
                "status",
            ]);
        },
            "client.amc_active.amc_master_transaction", "client.amc_active.amc_master_product"
        ])
            // ->whereHas("client", function($query){
            //     return $query->Has("amc_active", ">", 0);
            // })
            // ->whereHas("user", function($user_query){
            //     return $user_query->where("id", "=", "31");
            // })
            // query for debug
/*             ->where("engineer_id", 54)
            ->where('client_id', 722) */
            // end of debug query

            ->where('status', 1)
            ->get();
        $assigned_engineers = $assigned_engineers->sortBy(function($item){
            return (String)strtolower(str_replace(" ","", $item->zone->name));
        }, SORT_NATURAL );
        $assigned_engineers_grouped = $assigned_engineers->groupBy(function($item){
            return  (String)strtolower(str_replace(" ","", $item->zone->name));
        });
        // dd($assigned_engineers_grouped->toArray());
        // $assigned_engineers_grouped = $assigned_engineers->groupBy(function($item){
        //     return $item->engineer_id;
        // });
        // dd($assigned_engineers_grouped->toArray());
/*         $dsrs = DailyServiceReport::get();
        $dsr_grouped = $dsrs->groupBy([
            function($item){
                return $item->entry_by;
            },
            function($item){
                return $item->client_id;
            }])
        ->values()->all(); */
        $dsr_grouped = $this->quarterWiseProductAmc($financial_year);
        // return $assigned_engineers_grouped;
        // return "working.";
        Excel::create('Chandra PM Quarter '.date('dmyHis'), function( $excel) use($assigned_engineers_grouped, $dsr_grouped, $financial_year){
            $export_array = [];
            foreach ($assigned_engineers_grouped as $zone_name => $assigneds_grouped_zonewise) {
                if($assigneds_grouped_zonewise){
                    $assigneds_grouped_zonewise = $assigneds_grouped_zonewise->sortBy("client.name", SORT_NATURAL);
                    $assigneds_grouped_zonewise = $assigneds_grouped_zonewise->sortBy("user.first_name", SORT_NATURAL);
                    $assigned_engineers_grouped_engineer_wise = $assigneds_grouped_zonewise->groupBy("user.first_name");
                    // dump($assigneds_grouped_zonewise->toArray());
                    // dd($assigned_engineers_grouped_engineer_wise->toArray());
                    foreach($assigned_engineers_grouped_engineer_wise as $engineer_name => $assigneds){
                        $counter = 1;
                        if($assigneds){
                            foreach ($assigneds as $assigned) {
                                foreach ($assigned->client->assigned_products as $product_index => $product) {
                                    $data_array = [];
                                    // multiple product available so first product data and amc data need to show hence 
                                    // 0 index is first datga
                                    // if($product_index == 0){
                                    //     $data_array = [
                                    //         "SL NO"            => $counter,
                                    //         "Bank Name"        => $assigned->client->name,
                                    //         "Branch Name"      => $assigned->client->branch_name,
                                    //         "Product"          => $product->product->name,
                                    //         "Date of Inst."    => $product->date_of_install ?? "--",
                                    //         "BRAND"            => $product->product->brand->name ?? "--",
                                    //         "MODEL"            => $product->product->model_no ?? "--",
                                    //         "No. of Machine"   => $assigned->client->assigned_products->count() ?? 0,
                                    //         "New M/c Sl No. 1" => $product->product->serial_no ?? "--",
                                    //         "New M/c Sl No. 2" => "",
                                    //         "Contact No."      => "",
                                    //     ];
                                    // }else{
                                    //     $data_array = [
                                    //         "SL NO"            => $counter,
                                    //         "Bank Name"        => "",
                                    //         "Branch Name"      => $assigned->client->branch_name,
                                    //         "Product"          => $product->product->name,
                                    //         "Date of Inst."    => $product->date_of_install ?? "--",
                                    //         "BRAND"            => $product->product->brand->name ?? "--",
                                    //         "MODEL"            => $product->product->model_no ?? "--",
                                    //         "No. of Machine"   => "",
                                    //         "New M/c Sl No. 1" => $product->product->serial_no ?? "--",
                                    //         "New M/c Sl No. 2" => "",
                                    //         "Contact No."      => "",
                                    //     ];
                                    // }
                                    // foreach(range(1, 4) as $index => $number){
                                    //     // if($number == 1){
                                    //         $data = $this->returnQuarterData($dsr_grouped, $assigned->user->id, $assigned->client->id, (4 - $index));
                                    //         $data_array = array_merge($data_array, $this->generateQuarterData($data, (4 - $index)));
                                    //     // }elseif($number == 2){
                                    //     //     $data = $this->returnQuarterData($dsr_grouped, $assigned->user->id, $assigned->client->id, 3);
                                    //     //     $data_array = array_merge($data_array, $this->generateQuarterData($data, 3));
                                    //     //    /*  $data_array["ENG Name {$number}qtr"] = "";
                                    //     //     $data_array["SCR No {$number}qtr"]   = "";
                                    //     //     $data_array["Oct".date("y")]   = "";
                                    //     //     $data_array["Nov".date("y")]   = "";
                                    //     //     $data_array["Dec".date("y")]   = ""; */
                                    //     // }elseif($number == 3){
                                    //     //     $data = $this->returnQuarterData($dsr_grouped, $assigned->user->id, $assigned->client->id, 2);
                                    //     //     $data_array = array_merge($data_array, $this->generateQuarterData($data, 2));
                                    //     //     /* $data_array["ENG Name {$number}qtr"] = "";
                                    //     //     $data_array["SCR No {$number}qtr"]   = "";
                                    //     //     $data_array["July".date("y")]   = "";
                                    //     //     $data_array["Aug".date("y")]   = "";
                                    //     //     $data_array["Sept".date("y")]   = ""; */
                                    //     // }elseif($number == 4){
                                    //     //     $data = $this->returnQuarterData($dsr_grouped, $assigned->user->id, $assigned->client->id, 1);
                                    //     //     $data_array = array_merge($data_array, $this->generateQuarterData($data, 1));
                                    //     //    /*  $data_array["ENG Name {$number}qtr"] = "";
                                    //     //     $data_array["SCR No {$number}qtr"]   = "";
                                    //     //     $data_array["Apr".date("y")]   = "";
                                    //     //     $data_array["May".date("y")]   = "";
                                    //     //     $data_array["Jun".date("y")]   = ""; */
                                    //     // }
                                    // }
                                    // $export_array[] = $data_array;
                                    // $counter++;
                                }
                                // return view("admin.reports.amc.amc-reports")->with(["assigneds" => $assigneds, "dsr_grouped" => $dsr_grouped, "financial_year" => $financial_year]);
                                
                            }
                            $sheet_name = $assigned->zone->name." (".$assigned->user->full_name().")";
                                $excel->sheet($sheet_name, function($sheet) use($export_array, $sheet_name, $assigneds, $dsr_grouped, $financial_year){
                                    //Generating Header with Qtr and sub names
                                    // two header names
                                    /* $heading_array = [];
                                    foreach(range(0, 1) as $key => $val){
                                        $heading_array[$key] = [
                                            "SL NO"            => "",
                                            "Bank Name"        => "",
                                            "Branch Name"      => "",
                                            "Product"          => "",
                                            "Date of Inst."    => "",
                                            "BRAND"            => "",
                                            "MODEL"            => "",
                                            "No. of Machine"   => "",
                                            "New M/c Sl No. 1" => "",
                                            "New M/c Sl No. 2" => "",
                                            "Contact No."      => "",
                                        ];
                                                                
                                        foreach(range(1, 4) as $index => $number){
                                            // first time generate only header 1st qtr, 2nd qtr,
                                            // next time generate sub header, eng name, scr no, date, month, 
                                            if($key == 0){
                                                $data = $this->generateQuarterData([], (4 - $index));
                                                foreach($data as $k => $v){
                                                    $data[$k]   = (4-$index)." qtr";
                                                }
                                                $heading_array[$key] = array_merge($heading_array[$key], $data);
                                            }else{
                                                $heading_array[$key] = array_merge($heading_array[$key], $this->generateQuarterData([], (4 - $index)));
                                            }
                                        }
                                    }
                                    $heading_array = array_values($heading_array);
                                    $heading_array = array_merge($heading_array, $export_array); */


                                    $sheet->setTitle($sheet_name);
                                    $sheet->setStyle(array(
                                        'font' => array(
                                            'name'      =>  'Calibri',
                                            'size'      =>  10,
                                            'bold'      =>  false
                                        )
                                    ));
                    
                                    $sheet->cells('A1:AE1', function($cells) {
                                        $cells->setFontWeight('bold');
                                    });
                                    $sheet->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                                    $sheet->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3);
                                    $sheet->loadView("admin.reports.amc.amc-reports", ["assigneds" => $assigneds, "dsr_grouped" => $dsr_grouped, "financial_year" => $financial_year]);
                                    // $sheet->fromArray([], null, 'A1', false, true);
                                    // dd("HALT HERE");
                                });
                                
                                $export_array = [];
                        }
                        //end of $assigneds check
                    }
                    //end for assigned_engineers_grouped_engineer_wise foreach
                }
                //end of $assigneds_grouped_zonewise check
            }
            //end of $assigned_engineers_grouped foreach
            // $this->setExcelHeader($excel);
        })->export('xlsx');
        // excel

    }
    private function quarterWiseProductAmc($date_range = [])
    {
        $client_amcs = DailyServiceReport::whereRaw("DATE(entry_datetime) BETWEEN ? and ? ", $date_range)
            ->with(['dsr_transaction', "dsr_products.product", 'engineer'])
            ->where("maintenance_type", "=", 2)
            ->where("status", "=", 1)
            ->select(["scr_no", "contact_person_details", "client_id", "entry_datetime", "call_receive_date"])
            ->get();
            
        $client_amcs_grouped = $client_amcs->groupBy([function($item){
            return $item->entry_by;
        },
        function($item){
            return $item->client_id;
        },
        function($item){
            return date("m", strtotime($item->entry_datetime));
        }]);
        unset($client_amcs);
        return $client_amcs_grouped;
    }
    private function returnQuarterData($dsr_grouped, $engineer_id, $client_id, $quarter)
    {
        $months = $this->generateMonthFromQuarter($quarter);
        if(!isset($dsr_grouped[$engineer_id])){
            return [];
        }
        if(!isset($dsr_grouped[$engineer_id][$client_id])){
            return [];
        }
        foreach($months as $month){
            if(isset($dsr_grouped[$engineer_id][$client_id][$month])){
                return $dsr_grouped[$engineer_id][$client_id][$month];
            }
        }
        return [];
    }
    private function generateMonthFromQuarter ($quarter){
        // first quarter
        if($quarter == 1){
            return ["04", "05", "06"];
        }elseif($quarter == 2){
            return ["07", "08", "09"];
        }elseif($quarter == 3){
            return ["10", "11", "12"];
        }elseif($quarter == 4){
            return ["01", "02", "03"];
        }

    }
    // function used for genearet quarter data by
    // comparing date month and dsr on same date.
    private function generateQuarterData($data, $quarter)
    {
        $data_array = [];
        $months = $this->generateMonthFromQuarter($quarter);
        $data_array["ENG Name {$quarter}qtr"] = "";
        $data_array["SCR No {$quarter}qtr"]   = "";
        foreach($months as $month ){
            $month_name = date('M', strtotime("2019-".$month."-01"));
            if($data){
                $data_array["ENG Name {$quarter}qtr"] = $data[0]->engineer->full_name();
                $data_array["SCR No {$quarter}qtr"]   = $data[0]->scr_no;
                if(dateFormat($data[0]->call_attend_date, "m") == $month){
                    $data_array["$month_name".date("y")]   = dateFormat($data[0]->call_attend_date, "d-m-Y");
                }else{
                    $data_array["$month_name".date("y")]   = "";
                }
            }else{
                $data_array["$month_name".date("y")]   = "";
            }
        }
        return $data_array;
    }

    public function AutomaticAssign(){
        $client_amc_trans = ClientAmcTransaction::where(['amc_month'=>date('F'),'amc_year'=>date('Y')])
                                                ->where(['status'=>1,'engineer_status'=>0])
                                                ->whereHas("assigned_engineers", function($user_query){
                                                    return $user_query->where("status",0);
                                                })
                                                ->get();
        foreach($client_amc_trans as $trans){
            $client_id = $trans->client_master->client_id;
            $zone_id = $trans->client_master->client->zone_id;
            $engg_id = $trans->assigned_engineers->engineer_id;
            $engineer = AssignEngineer::where('client_id',$client_id)->where('zone_id',$zone_id)->where('status',1)->first();
            if($engineer!==null && $engineer->engineer_id == $engg_id){
                $trans->assigned_engineers->update(['engineer_id'=>$engineer->engineer_id,'status'=>1]);
            }else{
                $trans->assigned_engineers->update(['status'=>1]);
            }
        }
        dd($client_amc_trans);
    }
}
