<?php

namespace App\Http\Controllers\REST;

use App\Http\Controllers\Controller;
use App\Models\Assign\AmcAssignedToEngineers;
use App\Models\Assign\AssignEngineer;
use App\Models\Client;
use App\Models\ClientAmcMaster;
use App\Models\ClientAmcProduct;
use App\Models\ClientAmcTransaction;
use App\Models\Dsr\DailyServiceReport;
use App\Models\Dsr\DailyServiceReportProduct;
use App\Models\Dsr\DailyServiceReportTransaction;
use App\Models\IssueEngineer;
use App\Models\IssueEngineerTransaction;
use App\Models\Product;
use App\Models\SparePartMaster;
use App\Models\SparePartTransaction;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use DB;

class AmcController extends Controller
{
/**
 * Display a listing of the resource.
 *
 * @return \Illuminate\Http\Response
 */
    public function index()
    {
        $user = JWTAuth::parseToken()->toUser();
        // dd($user);
        if ($user) {
            $json_arr = array();
            $ass_engg = AssignEngineer::with('client', 'user', 'zone')->where('engineer_id', Auth::user()->id)->where('status', 1)->get()->toArray();

            $amcs = [];
            if (isset($ass_engg)) {

                foreach ($ass_engg as $key => $clnt_amc) {
                    array_push($amcs, $clnt_amc['client_id']);
                }

                //dd($amcs);

                foreach ($amcs as $key => $value) {

                    $client_amc1 = ClientAmcMaster::with('client', 'roster', 'amc_master_transaction', 'amc_master_product')->whereIn('client_id', $amcs)->where('status', 1)->get()->toArray();

                    $client_amc = ClientAmcMaster::with('client', 'roster', 'amc_master_transaction', 'amc_master_product')->whereIn('client_id', $amcs)->where('status', 1)->get();

                    if ($client_amc != null) {
                        $json_arr['status']     = true;
                        $json_arr['client_amc'] = $client_amc;
                    } else {
                        $json_arr['status']     = false;
                        $json_arr['client_amc'] = [];
                    }

                    $trans = [];
                    foreach ($client_amc1 as $key => $clnt_trns) {
                        array_push($trans, $clnt_trns['id']);
                    }

                    foreach ($clnt_amc as $key => $value) {

                        $amc_trans = ClientAmcTransaction::with('client_master')->whereIn('client_amc_masters_id', $trans)->where('status', 1)->get();

                        if (isset($amc_trans)) {
                            $json_arr['status']    = true;
                            $json_arr['amc_trans'] = $amc_trans;
                        } else {
                            $json_arr['status']    = false;
                            $json_arr['amc_trans'] = [];
                        }

                        $amc_products = ClientAmcProduct::with('product')->whereIn('client_amc_masters_id', $trans)->where('status', 1)->get();

                        if (isset($amc_products)) {
                            $json_arr['status']       = true;
                            $json_arr['amc_products'] = $amc_products;
                        } else {
                            $json_arr['status']       = false;
                            $json_arr['amc_products'] = [];
                        }

                    }

                }
            } else {
                $json_arr['status']       = false;
                $json_arr['client_amc']   = [];
                $json_arr['amc_trans']    = [];
                $json_arr['amc_products'] = [];
            }

            // return response()->json([
            // 'status' => true,
            // 'data'=> [
            //     'client_amc' => $client_amc,
            //     'amc_trans' => $amc_trans,
            //     'amc_products' => $amc_products
            //     ]
            // ]);
            //  }
            return response()->json($json_arr);} else {
            return response()->json([
                'status' => false,

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
        \Log::info("Test log message");
        $user = JWTAuth::parseToken()->toUser();
        /* $rules = [
            "scr_no"    => "required|unique:daily_service_reports,scr_no"
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json([
                "status"    => false,
                "message"   => "Scr No Already Exists."
            ]);
        } */
        // return $request->all();
        \Log::info(json_encode($request->all()));
        if ($user) {

            $json_arr         = array();
            $today            = date("Y-m-d H:i:s");
            $maintenance_type = $request->maintenance_type;
            $client_name      = $request->client_id;
            $branch           = $request->branch;
            $client_id        = Client::where('branch_name', $branch)->where('name', $client_name)->first()->id;

            $dsr = new DailyServiceReport();
            DB::beginTransaction();
            try {

                $client_master = ClientAmcMaster::where('client_id',$client_id)->where("status", 1)->first();
                $transaction = $client_master->amc_master_transaction->where('engineer_status',0)->first();
                ClientAmcTransaction::where('id',$transaction->id)->update([
                                                                          'engineer_status' => 1,
                                                                          'engineer_id'    => auth()->id(),
                                                                        ]);

                $products = $request->products;
                if ($maintenance_type == 2) {
                    $dsr->client_id = $client_id;    
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
    
                    if ($request->input('call_receive_date_amc') != '') {
                        $req_date   = $request->input('call_receive_date_amc');
                        $tdr        = str_replace("/", "-", $req_date);
                        $new_mnf_dt = date('Y-m-d', strtotime($tdr));
                    } else {
                        $new_mnf_dt = " ";
                    }
    
                    $dsr->call_receive_date = $new_mnf_dt;
    
                    if ($request->input('call_attend_date_amc') != '') {
                        $req_date1   = $request->input('call_attend_date_amc');
                        $tdr1        = str_replace("/", "-", $req_date1);
                        $new_mnf_dt1 = date('Y-m-d', strtotime($tdr1));
                    } else {
                        $new_mnf_dt1 = " ";
                    }
    
                    $dsr->call_attend_date = $new_mnf_dt1;
                    $dsr->scr_no           = $request->get("scr_no");
                    // dsr products implemented
                    /* $dsr->nature_of_complaint_by_customer = $request->nature_of_complaint_by_customer_amc;
                    $dsr->fault_observation_by_engineer   = $request->fault_observation_by_engineer_amc;
                    $dsr->action_taken_by_engineer        = $request->action_taken_by_engineer_amc;
                    $dsr->remarks                         = $request->remarks_amc;
                    $dsr->product_id                      = $request->product_id_amc;
    
                    if ($request->product_id_amc != '') {
                        $group_id      = Product::where('id', $request->product_id_amc)->where('status', 1)->first()->group_id;
                        $dsr->group_id = $group_id;
                    }
    
                    $dsr->model_no  = $request->model_no_amc;
                    $dsr->serial_no = $request->serial_no_amc; */
    
                    $dsr->entry_datetime = $today;
                    $dsr->entry_by       = $user->id;
    
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
                        $json_arr['status']  = true;
                        $json_arr['message'] = 'Successfully submitted amc details';
                        //$json_arr['dsr'] = $dsr;
                    } else {
                        // dd('error');
                        $json_arr['status']  = false;
                        $json_arr['message'] = 'Please fix the error and try again';
                        // $json_arr['dsr'] = [];
                    }
    
                    $spare_part_id_amc = $request->spare_part_id_amc;
    
                    if ($spare_part_id_amc) {
                        foreach ($request->spare_part_id_amc as $key1 => $value1) {
    
                            if (!empty($value1)) {
    
                                $sp_master                      = new SparePartMaster();
                                $sp_master->engineer_id         = $user->id;
                                $sp_master->dsr_id              = $dsr->id;
                                $sp_master->date_of_transaction = date('Y-m-d');
                                $sp_master->trans_type          = 'sup';
                                if ($sp_master->save()) {
                                    $json_arr['status']  = true;
                                    $json_arr['message'] = 'Successfully submitted amc details';
                                    //$json_arr['sp_master'] = $sp_master;
                                } else {
                                    $json_arr['status']  = false;
                                    $json_arr['message'] = 'Please fix the error and try again';
                                    //$json_arr['sp_master'] = [];
                                }
    
                                $sp_master_trans                       = new SparePartTransaction();
                                $sp_master_trans->spare_part_master_id = $sp_master->id;
                                $sp_master_trans->spare_parts_id       = $request->spare_part_id_amc[$key1];
                                $sp_master_trans->description          = 'Supplied spare part for dsr';
                                $sp_master_trans->transaction_date     = date('Y-m-d');
                                $sp_master_trans->transaction_type     = 'sup';
                                $sp_master_trans->supplied_quantity    = $request->spare_part_quantity_amc[$key1];
                                $sp_master_trans->last_transaction_by  = $user->id;
                                if ($sp_master_trans->save()) {
                                    $json_arr['status']  = true;
                                    $json_arr['message'] = 'Successfully submitted amc details';
                                    //$json_arr['sp_master_trans'] = $sp_master_trans;
                                } else {
                                    $json_arr['status']  = false;
                                    $json_arr['message'] = 'Please fix the error and try again';
                                    //$json_arr['sp_master_trans'] = [];
                                }
    
                                $iss_engineer                       = new IssueEngineer();
                                $iss_engineer->spare_part_master_id = $sp_master->id;
                                $iss_engineer->engineer_id          = $user->id;
                                $iss_engineer->spare_part_id        = $request->spare_part_id_amc[$key1];
                                if ($iss_engineer->save()) {
                                    $json_arr['status']  = true;
                                    $json_arr['message'] = 'Successfully submitted amc details';
                                    //$json_arr['iss_engineer'] = $iss_engineer;
                                } else {
                                    $json_arr['status']  = false;
                                    $json_arr['message'] = 'Please fix the error and try again';
                                    //$json_arr['iss_engineer'] = [];
                                }
    
                                $iss_engineer_trans                       = new IssueEngineerTransaction();
                                $iss_engineer_trans->engineer_sp_trans_id = $sp_master_trans->id;
                                $iss_engineer_trans->spare_part_master_id = $sp_master->id;
                                $iss_engineer_trans->engineer_id          = $user->id;
                                $iss_engineer_trans->spare_part_id        = $request->spare_part_id_amc[$key1];
                                $iss_engineer_trans->description          = 'Supplied spare part for dsr';
                                $iss_engineer_trans->transaction_date     = date('Y-m-d');
                                $iss_engineer_trans->stock_out            = $request->spare_part_quantity_amc[$key1];
                                if ($iss_engineer_trans->save()) {
                                    $json_arr['status']  = true;
                                    $json_arr['message'] = 'Successfully submitted amc details';
                                    //$json_arr['iss_engineer_trans'] = $iss_engineer_trans;
                                } else {
                                    $json_arr['status']  = false;
                                    $json_arr['message'] = 'Successfully submitted amc details';
                                    // $json_arr['iss_engineer_trans'] = [];
                                }
    
                                $dsr_transaction                          = new DailyServiceReportTransaction();
                                $dsr_transaction->daily_service_report_id = $dsr->id;
                                $dsr_transaction->spare_part_id           = $request->spare_part_id_amc[$key1];
                                $dsr_transaction->spare_part_quantity     = $request->spare_part_quantity_amc[$key1];
    
                                $stock_in_hand = IssueEngineerTransaction::where('status', 1)->where([['engineer_id', Auth::user()->id], ['spare_part_id', $spare_part_id_amc]])->sum('stock_in');
    
                                $stock_out = IssueEngineerTransaction::where('status', 1)->where([['engineer_id', Auth::user()->id], ['spare_part_id', $spare_part_id_amc]])->sum('stock_out');
    
                                $dsr_transaction->spare_part_stock_in_hand = $stock_in_hand - $stock_out;
    
                                $dsr_transaction->spare_part_taken_back          = $request->spare_part_taken_back_amc[$key1];
                                $dsr_transaction->spare_part_taken_back_quantity = $request->spare_part_taken_back_quantity_amc[$key1];
    
                                $dsr_transaction->unit_price_free       = $request->unit_price_free_amc[$key1];
                                $dsr_transaction->unit_price_chargeable = $request->unit_price_chargeable_amc[$key1];
    
                                if ($request->labour_free_amc[$key1] != null) {
                                    $dsr_transaction->labour_free = $request->labour_free_amc[$key1];
                                } else {
                                    $dsr_transaction->labour_free = 0;
                                }
    
                                if ($dsr_transaction->save()) {
                                    $json_arr['status']  = true;
                                    $json_arr['message'] = 'Successfully submitted amc details';
                                    // $json_arr['dsr_transaction'] = $dsr_transaction;
                                } else {
                                    $json_arr['status']  = false;
                                    $json_arr['message'] = 'Please fix the error and try again';
                                    //$json_arr['dsr_transaction'] = [];
                                }
    
                            } //end of !empty($value1)
    
                        } // end of $request->spare_part_id_amc
                    } // end of if spare_part_id_amc
    
                } //end of maintenance type 2    
            } catch (\Throwable $th) {
                \Log::error($th);
                DB::rollback();
                return response()->json([
                    'success' => false,
                    "message"   => "AMC not submitted.",
                    "error" =>  $th->getMessage()
                ]);
            }
            DB::commit();
            \Log::info(json_encode($json_arr));
            return response()->json($json_arr);} else {
            return response()->json([
                'success' => false,
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
        $user = JWTAuth::parseToken()->toUser();
        if ($user) {
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

            $json_arr = array();

            // $dsr_id = Crypt::decrypt($id);
            $dsr = DailyServiceReport::with('dsr_transaction', 'client', 'product', 'engineer', 'complaint')->where('id', $id)->where('status', 1)->first();
            $dsr->scr_no           = $request->get("scr_no");
            $today            = date("Y-m-d H:i:s");
            $maintenance_type = $request->maintenance_type;
            $client_name      = $request->client_id;
            $branch           = $request->branch;
            $client_id        = Client::where('branch_name', $branch)->where('name', $client_name)->first()->id;

            // Preventive Maintenance
            if ($maintenance_type == 2) {
                $dsr->client_id = $client_id;

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

                if ($request->input('call_receive_date_amc') != '') {
                    $req_date   = $request->input('call_receive_date_amc');
                    $tdr        = str_replace("/", "-", $req_date);
                    $new_mnf_dt = date('Y-m-d', strtotime($tdr));
                } else {
                    $new_mnf_dt = " ";
                }

                $dsr->call_receive_date = $new_mnf_dt;

                if ($request->input('call_attend_date_amc') != '') {
                    $req_date1   = $request->input('call_attend_date_amc');
                    $tdr1        = str_replace("/", "-", $req_date1);
                    $new_mnf_dt1 = date('Y-m-d', strtotime($tdr1));
                } else {
                    $new_mnf_dt1 = " ";
                }

                $dsr->call_attend_date = $new_mnf_dt1;

                /* $dsr->nature_of_complaint_by_customer = $request->nature_of_complaint_by_customer_amc;
                $dsr->fault_observation_by_engineer   = $request->fault_observation_by_engineer_amc;
                $dsr->action_taken_by_engineer        = $request->action_taken_by_engineer_amc;
                $dsr->remarks                         = $request->remarks_amc;
                $dsr->product_id                      = $request->product_id_amc;

                if ($request->product_id_amc != '') {
                    $group_id      = Product::where('id', $request->product_id_amc)->where('status', 1)->first()->group_id;
                    $dsr->group_id = $group_id;
                }

                $dsr->model_no  = $request->model_no_amc;
                $dsr->serial_no = $request->serial_no_amc; */

                $dsr->entry_datetime = $today;
                $dsr->entry_by       = $user->id;

                $products            = $request->products;
                if ($dsr->save()) {
                    $json_arr['status']  = true;
                    $json_arr['message'] = 'Successfully updated amc details';
                    // $json_arr['dsr_transaction'] = $dsr_transaction;
                    $dsr->dsr_products()->delete();
                    foreach ($products as $product) {
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
                } else {
                    $json_arr['status']  = false;
                    $json_arr['message'] = 'Please fix the error and try again';
                    //$json_arr['dsr_transaction'] = [];
                }

                $sSpare_master = SparePartMaster::where('dsr_id', $dsr->id)->where('status', 1)->get();

                foreach ($sSpare_master as $key => $value) {
                    $spare_master = SparePartMaster::where('dsr_id', $dsr->id)->where('status', 1)->update(['status' => 0]);

                    $sp_trans = SparePartTransaction::where('spare_part_master_id', $value->id)->where('status', 1)->update(['status' => 0]);

                    // dd($sp_trans);

                    $iIss_eng = IssueEngineer::where('spare_part_master_id', $value->id)->where('status', 1)->get();

                    foreach ($iIss_eng as $key1 => $value1) {
                        $iss_eng_trans = IssueEngineerTransaction::where('engineer_sp_trans_id', $value1->id)->where('spare_part_master_id', $value->id)->where('status', 1)->update(['status' => 0]);
                    }

                    $iss_eng = IssueEngineer::where('spare_part_master_id', $value->id)->where('status', 1)->update(['status' => 0]);

                    $dsr_trans = DailyServiceReportTransaction::where('daily_service_report_id', $dsr->id)->where('status', 1)->update(['status' => 0]);

                }

                $spare_part_id_amc = $request->spare_part_id_amc;
                // dd($request->spare_part_id);

                foreach ($request->spare_part_id_amc as $key1 => $value1) {
                    // dd($request->spare_part_quantity_amc);

                    if (!empty($value1)) {

                        $sp_master                      = new SparePartMaster();
                        $sp_master->engineer_id         = Auth::user()->id;
                        $sp_master->dsr_id              = $dsr->id;
                        $sp_master->date_of_transaction = date('Y-m-d');
                        $sp_master->trans_type          = 'sup';
                        if ($sp_master->save()) {
                            $json_arr['status']  = true;
                            $json_arr['message'] = 'Successfully updated amc details';
                            // $json_arr['dsr_transaction'] = $dsr_transaction;
                        } else {
                            $json_arr['status']  = false;
                            $json_arr['message'] = 'Please fix the error and try again';
                            //$json_arr['dsr_transaction'] = [];
                        }

                        $sp_master_trans                       = new SparePartTransaction();
                        $sp_master_trans->spare_part_master_id = $sp_master->id;
                        $sp_master_trans->spare_parts_id       = $request->spare_part_id_amc[$key1];
                        $sp_master_trans->description          = 'Supplied spare part for dsr';
                        $sp_master_trans->transaction_date     = date('Y-m-d');
                        $sp_master_trans->transaction_type     = 'sup';
                        $sp_master_trans->supplied_quantity    = $request->spare_part_quantity_amc[$key1];
                        $sp_master_trans->last_transaction_by  = Auth::user()->id;
                        if ($sp_master_trans->save()) {
                            $json_arr['status']  = true;
                            $json_arr['message'] = 'Successfully updated amc details';
                            // $json_arr['dsr_transaction'] = $dsr_transaction;
                        } else {
                            $json_arr['status']  = false;
                            $json_arr['message'] = 'Please fix the error and try again';
                            //$json_arr['dsr_transaction'] = [];
                        }

                        $iss_engineer                       = new IssueEngineer();
                        $iss_engineer->spare_part_master_id = $sp_master->id;
                        $iss_engineer->engineer_id          = Auth::user()->id;
                        $iss_engineer->spare_part_id        = $request->spare_part_id_amc[$key1];
                        if ($iss_engineer->save()) {
                            $json_arr['status']  = true;
                            $json_arr['message'] = 'Successfully updated amc details';
                            // $json_arr['dsr_transaction'] = $dsr_transaction;
                        } else {
                            $json_arr['status']  = false;
                            $json_arr['message'] = 'Please fix the error and try again';
                            //$json_arr['dsr_transaction'] = [];
                        }

                        $iss_engineer_trans                       = new IssueEngineerTransaction();
                        $iss_engineer_trans->engineer_sp_trans_id = $sp_master_trans->id;
                        $iss_engineer_trans->spare_part_master_id = $sp_master->id;
                        $iss_engineer_trans->engineer_id          = Auth::user()->id;
                        $iss_engineer_trans->spare_part_id        = $request->spare_part_id_amc[$key1];
                        $iss_engineer_trans->description          = 'Supplied spare part for dsr';
                        $iss_engineer_trans->transaction_date     = date('Y-m-d');
                        $iss_engineer_trans->stock_out            = $request->spare_part_quantity_amc[$key1];
                        if ($iss_engineer_trans->save()) {
                            $json_arr['status']  = true;
                            $json_arr['message'] = 'Successfully updated amc details';
                            // $json_arr['dsr_transaction'] = $dsr_transaction;
                        } else {
                            $json_arr['status']  = false;
                            $json_arr['message'] = 'Please fix the error and try again';
                            //$json_arr['dsr_transaction'] = [];
                        }

                        $dsr_transaction                          = new DailyServiceReportTransaction();
                        $dsr_transaction->daily_service_report_id = $dsr->id;
                        $dsr_transaction->spare_part_id           = $request->spare_part_id_amc[$key1];
                        $dsr_transaction->spare_part_quantity     = $request->spare_part_quantity_amc[$key1];

                        $stock_in_hand = IssueEngineerTransaction::where('status', 1)->where([['engineer_id', Auth::user()->id], ['spare_part_id', $spare_part_id_amc]])->sum('stock_in');

                        $stock_out = IssueEngineerTransaction::where('status', 1)->where([['engineer_id', Auth::user()->id], ['spare_part_id', $spare_part_id_amc]])->sum('stock_out');

                        // dd($stock_in_hand );

                        $dsr_transaction->spare_part_stock_in_hand = $stock_in_hand - $stock_out;

                        $dsr_transaction->spare_part_taken_back          = $request->spare_part_taken_back_amc[$key1];
                        $dsr_transaction->spare_part_taken_back_quantity = $request->spare_part_taken_back_quantity_amc[$key1];

                        $dsr_transaction->unit_price_free       = $request->unit_price_free_amc[$key1];
                        $dsr_transaction->unit_price_chargeable = $request->unit_price_chargeable_amc[$key1];

                        if ($request->labour_free_amc[$key1] != null) {
                            $dsr_transaction->labour_free = $request->labour_free_amc[$key1];
                        } else {
                            $dsr_transaction->labour_free = 0;
                        }

                        if ($dsr_transaction->save()) {
                            $json_arr['status']  = true;
                            $json_arr['message'] = 'Successfully updated amc details';
                            // $json_arr['dsr_transaction'] = $dsr_transaction;
                        } else {
                            $json_arr['status']  = false;
                            $json_arr['message'] = 'Please fix the error and try again';
                            //$json_arr['dsr_transaction'] = [];
                        }
                    }
                }
            }

            return response()->json($json_arr);} else {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    public function allMonthlyAmc(Request $request)
    {

        $user         = JWTAuth::parseToken()->toUser();
        $today        = date('F');
        $current_year = date('Y');

        if ($user) {

            $json_arr               = array();
            $today_date             = date('d-m-Y');
            $json_arr['today_date'] = $today_date;
            $last_dsr_report        = DailyServiceReport::with('dsr_transaction', 'client', 'product', 'engineer', 'complaint')
                ->where('entry_by', $user->id)
                ->where('status', 1)
                ->orderBy('entry_datetime', 'desc')
                ->first();

            if (isset($last_dsr_report)) {
                $json_arr['status']          = true;
                $json_arr['last_dsr_report'] = $last_dsr_report;
            } else {
                $json_arr['status']          = false;
                $json_arr['last_dsr_report'] = [];
            }

            $assigned_spare_parts = SparePartMaster::with('spare_part_transaction', 'user', 'spare_part', 'spare_part_transaction.spare_part')->where('engineer_id', $user->id)->where('trans_type', '=', 'iss')->where('status', 1)->get();

            $all_sp_prts = [];
            if (isset($assigned_spare_parts)) {

                foreach ($assigned_spare_parts as $key => $value) {
                    $all_spare_parts = SparePartTransaction::where('spare_part_master_id', $value->id)->where('status', 1)->get();
                    // dd($all_spare_parts);

                    $all_spare_parts_to_array = SparePartTransaction::with('spare_part')->where('spare_part_master_id', $value->id)->where('status', 1)->get()->toArray();

                    foreach ($all_spare_parts_to_array as $key1 => $value1) {
                        array_push($all_sp_prts, $value1);
                        // $all_sp_prts[$value1->spare_parts_id]  = $value1;

                    }

                    // dd($all_sp_prts);

                    // dd($stock_in_hand);

                }
                $json_arr['status']               = true;
                $json_arr['assigned_spare_parts'] = $all_sp_prts;
            } else {
                $json_arr['status']               = true;
                $json_arr['assigned_spare_parts'] = [];
            }
            $current_month_amc = monthlyAMC()
                ->get();
            if($current_month_amc->count()){
                $json_arr['status']             = true;
                $json_arr['monthly_amc_list']   = $current_month_amc;
                $json_arr['client_master']      = [];
                $json_arr['client']             = [];
                $json_arr['amc_master_product'] = [];
                $json_arr['product']            = [];
            }else{
                $json_arr['status']             = false;
                $json_arr['monthly_amc_list']   = [];
                $json_arr['client_master']      = [];
                $json_arr['client']             = [];
                $json_arr['amc_master_product'] = [];
                $json_arr['product']            = [];
            }

            // return response()->json([
            //         'status' => true,
            //         'data'=> [
            //             'monthly_amc_list' => $monthly_amc_list,
            //             'last_dsr_report' =>$last_dsr_report,
            //             'today_date' => $today_date,
            //             'assigned_spare_parts' => $all_sp_prts
            //             ]
            //         ]);
            //          }
            if (isset($json_arr["status"]) && !$json_arr["status"]) {
                $json_arr["message"] = "No data found for preventive maintenance.";
            }
            return response()->json($json_arr);} else {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    public function upcomingAMC()
    {
        $user             = JWTAuth::parseToken()->toUser();
        $all_amc_upcoming = collect();
        try {
            $all_amc_upcoming = ClientAmcTransaction::with([
                'client_master', 'client_master.client'
            ])->whereHas("client_master", function($client_master_query) use ($user){
                return $client_master_query->where(function($where_query) use ($user){
                    $where_query->whereIn("client_id", function($query) use ($user){
                        return $query->select("client_id")
                            ->from("assign_engineers")
                            ->where("engineer_id", auth()->id())
                            ->where("status", 1);
                    })
                    ->orWhereIn("id", function($select_ids) use ($user){
                        return $select_ids->from("amc_assigned_to_engineers")
                            ->select("client_amc_master_id")->where("engineer_id", auth()->id());
                    });
                });
            })
            ->where("status", 11)
            // ->where('engineer_status',)
            ->get();
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json([
                "status"  => false,
                "message" => "Whoops! Something went wrong.",
                "data"    => $all_amc_upcoming,
            ]);
        }
        return response()->json([
            "status"  => ($all_amc_upcoming->count() > 0 ? true : false),
            "message" => $all_amc_upcoming->count() . " Records found.",
            "data"    => $all_amc_upcoming,
        ]);
    }
}
