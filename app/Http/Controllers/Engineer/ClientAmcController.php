<?php

namespace App\Http\Controllers\Engineer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth,Session,DB,Crypt,Validator,Excel,Redirect;

use App\Models\Client, App\Models\ClientAmcMaster, App\Models\ClientAmcTransaction, App\Models\Assign\AssignEngineer, App\Models\ClientAmcProduct, App\Models\AmcBillRaise;

class ClientAmcController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

            $ass_engg = AssignEngineer::with('client','user','zone')->where('engineer_id',Auth::user()->id)->where('status',1)->get()->toArray();

            $amcs   = [];
            $trans  = [];
            foreach ($ass_engg as $key => $clnt_amc) {
                array_push($amcs, $clnt_amc['client_id']);
            }
            /* $amc_assigned_manually = AmcAssignedToEngineers::where('engineer_id', Auth::user()->id)->get();
            if ($amc_assigned_manually->count()) {
                foreach ($amc_assigned_manually as $key => $amcs_assigned) {
                    array_push($trans, $amcs_assigned['client_amc_master_id']);
                }
            } */
            $user = Auth::user();
            $assigned_amc = ClientAmcMaster::whereHas("assigned_engineers", function($query) use ($user){
                return $query->where("engineer_id", $user->id);
            })
            ->get();
                // dd($amcs);

                // foreach ($amcs as $key => $value) {
                   
                    $client_amc1 = ClientAmcMaster::with('client','roster','amc_master_transaction')->whereIn('client_id',$amcs)->where('status',1)->get()->toArray();

                    $client_amc = ClientAmcMaster::with('client','roster','amc_master_transaction','amc_master_product')->whereIn('client_id',$amcs)->where('status',1)->orderBy('id','desc')->get();

                    foreach ($client_amc1 as $key => $clnt_trns) {
                        array_push($trans, $clnt_trns['id']);
                    }

                    foreach ($clnt_amc as $key => $value) {

                        $amc_trans = ClientAmcTransaction::with('client_master')->whereIn('client_amc_masters_id',$trans)->where('status',1)->get();

                        // $amc_products = ClientAmcProduct::with('product')->whereIn('client_amc_masters_id',$trans)->where('status',1)->get();
                       
                    }

                    
                // }
                if($client_amc){
                    $client_amc = $client_amc->merge($assigned_amc)->all();
                }else{
                    $client_amc =$assigned_amc;
                }
        // dd($amc_products);
        // $all_amc_upcoming = ClientAmcTransaction::with('client_master.client')->where(function($query) use ($user){
        //     return $query->whereHas("client_master", function($sub_query) use ($user){
        //         return $sub_query->whereIn("client_id", function($select_ids) use ($user){
        //             return $select_ids->from("assign_engineers")
        //                 ->select("client_id")->where("engineer_id", $user->id)
        //                 ->where("status", 1);
        //         })->orWhereIn("id", function($select_ids) use ($user){
        //             return $select_ids->from("amc_assigned_to_engineers")
        //                 ->select("client_amc_master_id")->where("engineer_id", $user->id);
        //         });
        //     });
        // })
        // ->where('status', 1)->get();
        /*$all_amc_upcoming = ClientAmcMaster::with(["roster", "client"])->whereHas("amc_master_transaction", function($amc_trans_query) use ($user){
            return $amc_trans_query->where(function($query) use ($user){
                return $query->whereHas("client_master", function($sub_query) use ($user){
                    return $sub_query->whereIn("client_id", function($select_ids) use ($user){
                        return $select_ids->from("assign_engineers")
                            ->select("client_id")->where("engineer_id", $user->id)
                            ->where("status", 1);
                    })->orWhereIn("id", function($select_ids) use ($user){
                        return $select_ids->from("amc_assigned_to_engineers")
                            ->select("client_amc_master_id")->where("engineer_id", $user->id);
                    });
                });
            })
            ->where('status', 1);
        })
        ->get(); */
        $all_amc_upcoming = ClientAmcMaster::with(["roster", "client"])
        ->where(function($query) use ($user){
            return $query->orWhereHas("client", function($sub_query) use ($user){
                return $sub_query->whereIn("client_id", function($select_ids) use ($user){
                    return $select_ids->from("assign_engineers")
                        ->select("client_id")
                        ->where("engineer_id", $user->id)
                        ->where("status", 1);
                });
            })->orWhereIn("id", function($select_ids) use ($user){
                return $select_ids->from("amc_assigned_to_engineers")
                    ->select("client_amc_master_id")
                    ->where("engineer_id", $user->id);
            });
        })->whereHas("amc_master_transaction", function($query){
            return $query->where("amc_month", date("F"))
                ->where("amc_year", date("Y"))
                ->where("status", 1);
        })
        ->get();
        
        // return view('engineer.amc.index',compact('client_amc'));
        return view('engineer.amc.index')->with(['client_amc' => $all_amc_upcoming]);
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
        $amc_id = Crypt::decrypt($id);
        $amc_detail = ClientAmcMaster::with('client','roster','amc_master_transaction','amc_master_product','amc_bill')->where('id',$amc_id)->where('status',1)->first();

        return view('engineer.amc.show',compact('amc_detail'));
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
        $ass_engg = AssignEngineer::with('client','user','zone')->where('engineer_id',Auth::user()->id)->where('status',1)->get()->toArray();

                $amcs = [];
                foreach ($ass_engg as $key => $clnt_amc) {
                    array_push($amcs, $clnt_amc['client_id']);
                }

                // dd($amcs);

                foreach ($amcs as $key => $value) {
                   
                    $client_amc1 = ClientAmcMaster::with('client','roster','amc_master_transaction','amc_master_product','amc_bill')->whereIn('client_id',$amcs)->where('status',1)->get()->toArray();

                    $client_amc = ClientAmcMaster::with('client','roster','amc_master_transaction','amc_master_product','amc_bill')->whereIn('client_id',$amcs)->where('status',1)->get();

                   
                    $trans = [];
                    foreach ($client_amc1 as $key => $clnt_trns) {
                        array_push($trans, $clnt_trns['id']);
                    }

                    // foreach ($amcs as $key => $value) {

                        $amc_trans = ClientAmcTransaction::with('client_master')->whereIn('client_amc_masters_id',$trans)->where('status',1)->get();

                        $amc_products = ClientAmcProduct::with('product')->whereIn('client_amc_masters_id',$trans)->where('status',1)->get();
                       
                    // }

                    
                }

                // dd($client_amc);

        try{
            Excel::create('ClientAMCDetails '.date('dmyHis'), function( $excel) use($client_amc, $amc_trans, $amc_products){
                $excel->sheet('Client-AMC-Details ', function($sheet) use($client_amc, $amc_trans, $amc_products){
                  $sheet->setTitle('Client-AMC-Details');

                  $sheet->cells('A1:AC1', function($cells) {
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
                                $arr[$i]['Bill Amount Paid']                         = "";
                                $arr[$i]['Bill Paid On Date']                         = "";
                                $arr[$i]['Bill Amount Paid Remarks']                         = "";
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

                                    $arr[$i]['Bill Amount Paid']                         = $value2->amount_paid;

                                    if ($value2->paid_on_date != null) {
                                        $arr[$i]['Paid On Date']                         = dateFormat($value2->paid_on_date);
                                     }else{
                                        $arr[$i]['Paid On Date']                         = '';
                                     }

                                    $arr[$i]['Bill Amount Paid Remarks']                         = $value2->last_follow_up_remarks;

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
                                    $arr[($counter + $key2) - $max_row]['Bill Amount Paid']                         = $value2->amount_paid;

                                    if ($value2->paid_on_date != null) {
                                        $arr[($counter + $key2) - $max_row]['Bill Paid On Date']                         = dateFormat($value2->paid_on_date);
                                     }else{
                                        $arr[($counter + $key2) - $max_row]['Bill Paid On Date']                         = '';
                                    }
                                    
                                    $arr[($counter + $key2) - $max_row]['Bill Amount Paid Remarks']                         = $value2->last_follow_up_remarks;

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
        return Redirect::route('view-all-assigned-clients-amc');
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


    public function raiseBillEdit($id)
    {
        $bill_id = Crypt::decrypt($id);
        $amc_bill = AmcBillRaise::where('id',$bill_id)->first();
        return view('engineer.amc.bill.edit',compact('amc_bill'));
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
        return Redirect::route('amc-bill-payment-details',Crypt::encrypt($amc_bill->id));
    }

    public function raiseBillPaymentDetails($id)
    {
        $bill_id = Crypt::decrypt($id);
        $amc_bill = AmcBillRaise::where('id',$bill_id)->where('status',1)->first();

        $amc_detail = ClientAmcMaster::with('client','roster','amc_bill','amc_master_product','amc_master_transaction')->where('id',$amc_bill->client_amc_masters_id)->where('status',1)->first();
        return view('engineer.amc.bill.show-payment-details',compact('amc_bill','amc_detail'));
    
    }

    public function raiseBillEditPayment($id)
    {
        $bill_id = Crypt::decrypt($id);
        $amc_bill = AmcBillRaise::where('id',$bill_id)->where('status',1)->first();
        return view('engineer.amc.bill.create-payment',compact('amc_bill'));
    }

    public function raiseBillDelete($id)
    {
        $bill_id = Crypt::decrypt($id);
        $amc_bill = AmcBillRaise::where('id',$bill_id)->where('status',1)->first();

        $amc_bill->paid_on_date = '';

        $amc_bill->amount_paid = '';
        $amc_bill->last_follow_up_by = Auth::user()->id;
        $amc_bill->last_follow_up_remarks = '';
        $amc_bill->last_follow_up_date = '';
        $amc_bill->save();

        Session::flash('success','Successfully deleted client amc bill payment details');
        return Redirect::route('amc-bill-payment-details',Crypt::encrypt($amc_bill->client_amc_masters_id));
    }


}
