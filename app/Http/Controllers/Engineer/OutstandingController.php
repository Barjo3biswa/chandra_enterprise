<?php

namespace App\Http\Controllers\Engineer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth,Session,DB,Crypt,Validator,Excel, Redirect;

use App\Models\Outstanding\ClientBill, App\Models\Outstanding\ClientBillTransaction, App\Models\Outstanding\EngineerBillFollowUp, App\Models\Client, App\Models\Company, App\Models\Group, App\Models\Assign\AssignEngineer;

class OutstandingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $engg_bill_follow = EngineerBillFollowUp::with('client_bill','engineer')->where('engineer_id',Auth::user()->id)->where('status',1)->orderBy('id','desc')->get();
        return view('engineer.outstanding.index',compact('engg_bill_follow'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $assgn_engg = AssignEngineer::with('client')->where('engineer_id',Auth::user()->id)->where('status',1)->get();
        $assign_clients = [];
        foreach ($assgn_engg as $key => $assgn_eng) {
            array_push($assign_clients, $assgn_eng['client_id']);
        }

        $bill_clients = ClientBill::with('client')->whereIn('client_id',$assign_clients)->where('status',1)->get();
        return view('engineer.outstanding.create',compact('bill_clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = $request->all();

        $client_id = $request->client_id;
        $client_bill_id = $request->client_bill_id;

        if($request->input('next_pay_by_date') != ''){
                $req_date = $request->input('next_pay_by_date');
                $tdr = str_replace("/", "-", $req_date);
                $new_mnf_dt = date('Y-m-d',strtotime($tdr));
            }
        else
            $new_mnf_dt = " ";

        $next_pay_by_date = $new_mnf_dt;

        $bill_status = $request->bill_status;
        $bill_remarks = $request->bill_remarks;

        $validator = Validator::make($post, EngineerBillFollowUp::$rules);

        if ($validator->fails()) return  Redirect::back()->withErrors($validator)->withInput();
 

        $engg_bill_follow = new EngineerBillFollowUp();
        $engg_bill_follow->client_bill_id = $client_bill_id;
        $engg_bill_follow->client_id = $client_id;
        $engg_bill_follow->engineer_id = Auth::user()->id;
        $engg_bill_follow->next_pay_by_date = $next_pay_by_date;
        $engg_bill_follow->follow_up_entry_date = date('Y-m-d');
        $engg_bill_follow->bill_status = $bill_status;
        $engg_bill_follow->bill_remarks = $bill_remarks;

        $engg_bill_follow->save();

        Session::flash('success','Successfully added outstanding bill details');
        return redirect()->route('all-bill-outstanding-details');
  
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $c_bill_follow_id = Crypt::decrypt($id);
        $engg_bill_follow = EngineerBillFollowUp::with('client','client_bill','engineer')->where('id',$c_bill_follow_id)->where('status',1)->first();

        return view('engineer.outstanding.show',compact('engg_bill_follow'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $c_bill_follow_id = Crypt::decrypt($id);

        $assgn_engg = AssignEngineer::with('client')->where('engineer_id',Auth::user()->id)->where('status',1)->get();
        $assign_clients = [];
        foreach ($assgn_engg as $key => $assgn_eng) {
            array_push($assign_clients, $assgn_eng['client_id']);
        }

        $bill_clients = ClientBill::with('client')->whereIn('client_id',$assign_clients)->where('status',1)->get();
      
        $c_bill_follow = EngineerBillFollowUp::with('client')->where('id',$c_bill_follow_id)->where('status',1)->first();
        // dd($c_bill_follow);
        return view('engineer.outstanding.edit',compact('c_bill_follow','bill_clients')); 
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
        $post = $request->all();
        $c_bill_follow_id = Crypt::decrypt($id);

        $client_id = $request->client_id;
        $client_bill_id = $request->client_bill_id;

        if($request->input('next_pay_by_date') != ''){
                $req_date = $request->input('next_pay_by_date');
                $tdr = str_replace("/", "-", $req_date);
                $new_mnf_dt = date('Y-m-d',strtotime($tdr));
            }
        else
            $new_mnf_dt = " ";

        $next_pay_by_date = $new_mnf_dt;

        $bill_status = $request->bill_status;
        $bill_remarks = $request->bill_remarks;

        $validator = Validator::make($post, EngineerBillFollowUp::$rules);

        if ($validator->fails()) return  Redirect::back()->withErrors($validator)->withInput();

        $engg_bill_follow = EngineerBillFollowUp::with('client')->where('id',$c_bill_follow_id)->where('status',1)->first();

        $engg_bill_follow->client_bill_id = $client_bill_id;
        $engg_bill_follow->client_id = $client_id;
        $engg_bill_follow->engineer_id = Auth::user()->id;
        $engg_bill_follow->next_pay_by_date = $next_pay_by_date;
        $engg_bill_follow->follow_up_entry_date = date('Y-m-d');
        $engg_bill_follow->bill_status = $bill_status;
        $engg_bill_follow->bill_remarks = $bill_remarks;

        $engg_bill_follow->save();

        Session::flash('success','Successfully updated outstanding bill details');
        return redirect()->route('all-bill-outstanding-details');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $c_bill_follow_id = Crypt::decrypt($id);
        $engg_bill_follow = EngineerBillFollowUp::with('client')->where('id',$c_bill_follow_id)->where('status',1)->first();
        $engg_bill_follow->status = 0;
        $engg_bill_follow->save();
        Session::flash('success','Successfully deleted outstanding bill details');
        return redirect()->route('all-bill-outstanding-details');
    }

    public function getBranchName(Request $request)
    {
       $client_id = $request->input('client_id');
       if($client_id){

        $branchname = Client::where('id',$client_id)->where('status',1)->get();

        $client_bill_id = ClientBill::where('client_id',$client_id)->where('status',1)->get();


        return response()->json(array(
            'branchname' => $branchname,
            'client_bill_id' => $client_bill_id,
        ));

       }
        
    }

    public function export(Request $request)
    {
        
        $engg_bill_follow = EngineerBillFollowUp::with('client_bill','engineer','client_bill.bill_transaction')->where('engineer_id',Auth::user()->id)->where('status',1)->orderBy('id','desc')->get();

        try{
            Excel::create('OutstandingDetails '.date('dmyHis'), function( $excel) use($engg_bill_follow){
                $excel->sheet('Outstanding-Details ', function($sheet) use($engg_bill_follow){
                  $sheet->setTitle('Outstanding-Details');

                  $sheet->cells('A1:Z1', function($cells) {
                    $cells->setFontWeight('bold');
                  });
                  
                  $arr = [];
                  $counter = 1;

                    foreach($engg_bill_follow->chunk(500) as $res):

                        foreach( $res as $k => $v) {
                            // dd($v);
                            $arr[$counter]['Sl No']                   = $k+1;
                            $arr[$counter]['Client Name']                     = ucwords($v->client_bill->client->name);
                            $arr[$counter]['Client Branch Name']                     = ucwords($v->client_bill->client->branch_name);
                            $arr[$counter]['Zone Name']                     = ucwords($v->client_bill->client->zone->name);
                            $arr[$counter]['Region Name']                     = ucwords($v->client_bill->client->region->name);
                            $arr[$counter]['Group Name']                     = ucwords($v->client_bill->group->name);
                            $arr[$counter]['Company Name']                     = ucwords($v->client_bill->company->name);
                            $arr[$counter]['Bill No']                     = ucwords($v->client_bill->bill_no);
                            $arr[$counter]['Bill Amount']                     = $v->client_bill->bill_amount;

                            if ($v->bill_date != "0000-00-00") {
                                $arr[$counter]['Bill Date']                    = dateFormat($v->client_bill->bill_date);
                            }else{
                                $arr[$counter]['Bill Date']                 = '';
                            }

                            if ($v->pay_by_date != "0000-00-00") {
                                $arr[$counter]['Bill Pay By Date']                    = dateFormat($v->client_bill->pay_by_date);
                            }else{
                                $arr[$counter]['Bill Pay By Date']                 = '';
                            }

                            $arr[$counter]['Next Pay By Date']                     = dateFormat($v->next_pay_by_date);
                            $arr[$counter]['Bill Follow Up Add Date']                     = dateFormat($v->follow_up_entry_date);
                            if ($v->bill_status == 1) {
                                $arr[$counter]['Bill Status']                     = 'Yet to clear payment';
                            }else if($v->bill_status == 2){
                                $arr[$counter]['Bill Status']                     = 'Cleared payment';
                            }else{
                                $arr[$counter]['Bill Status']                     = '';
                            }

                            $arr[$counter]['Bill Remarks']                     = $v->bill_remarks;
 
                            // Outstanding Product Details

                            foreach ($v->client_bill->bill_transaction as $key1 => $value1){
                                if($key1 > 0){
                                    $arr[$counter]['Sl No']                   = '';
                                    $arr[$counter]['Client Name']                     = '';
                                    $arr[$counter]['Client Branch Name']                     = '';
                                    $arr[$counter]['Zone Name']                     = '';
                                    $arr[$counter]['Region Name']                     = '';
                                    $arr[$counter]['Group Name']                     = '';
                                    $arr[$counter]['Company Name']                     = '';
                                    $arr[$counter]['Bill No']                     = '';
                                    $arr[$counter]['Bill Amount']                     = '';

                                    if ($v->bill_date != "0000-00-00") {
                                        $arr[$counter]['Bill Date']                    = '';
                                    }else{
                                        $arr[$counter]['Bill Date']                 = '';
                                    }

                                    if ($v->pay_by_date != "0000-00-00") {
                                        $arr[$counter]['Bill Pay By Date']                    = '';
                                    }else{
                                        $arr[$counter]['Bill Pay By Date']                 = '';
                                    }

                                    $arr[$counter]['Next Pay By Date']                     = '';
                                    $arr[$counter]['Bill Follow Up Add Date']                     = '';
                                    if ($v->bill_status == 1) {
                                        $arr[$counter]['Bill Status']                     = '';
                                    }else if($v->bill_status == 2){
                                        $arr[$counter]['Bill Status']                     = '';
                                    }else{
                                        $arr[$counter]['Bill Status']                     = '';
                                    }
                                    $arr[$counter]['Bill Remarks']                     = '';
                                }

                                    $arr[$counter]['Product Name']                 = $value1->product_name;
                                    $arr[$counter]['Product Quantity']                 = $value1->product_quantity;
                                    $arr[$counter]['Product Price']                 = $value1->product_price;
                                           
                                $counter ++;
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

        Session::flash('success','Successfully exported outstanding bill details');
        return redirect()->route('all-bill-outstanding-details');
    }

}
