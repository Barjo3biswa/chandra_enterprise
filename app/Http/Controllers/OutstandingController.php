<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Session,DB,Crypt,Validator,Excel,Auth, Redirect;

use App\Models\Outstanding\ClientBill, App\Models\Outstanding\ClientBillTransaction, App\Models\Outstanding\EngineerBillFollowUp, App\Models\Client, App\Models\Company, App\Models\Group, App\Models\Assign\AssignEngineer, App\User;

class OutstandingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client_bill = ClientBill::with('bill_transaction','engg_bill_follow','client','company')->where('status',1)->orderBy('id','desc')->get();
        return view('admin.outstanding.index',compact('client_bill'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::where('status',1)->groupBy('name')->get();
        $companies = Company::where('status',1)->get();
        $groups = Group::where('status',1)->get();
        return view('admin.outstanding.create',compact('clients','companies','groups'));
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
        // dd($post);

        $client_name = $request->client_id;
        $branch = $request->branch;
        $client_id = Client::where('name',$client_name)->where('branch_name',$branch)->where('status',1)->first()->id;
        $company_id = $request->company_id;
        $group_id = $request->group_id;
        $bill_no = $request->bill_no;

        if($request->input('bill_date') != ''){
                $req_date = $request->input('bill_date');
                $tdr = str_replace("/", "-", $req_date);
                $new_mnf_dt = date('Y-m-d',strtotime($tdr));
            }
        else
            $new_mnf_dt = " ";

        $bill_date = $new_mnf_dt;

        $bill_amount = $request->bill_amount;

        if($request->input('pay_by_date') != ''){
                $req_date1 = $request->input('pay_by_date');
                $tdr1 = str_replace("/", "-", $req_date1);
                $new_mnf_dt1 = date('Y-m-d',strtotime($tdr1));
            }
        else
            $new_mnf_dt1 = " ";

        $pay_by_date = $new_mnf_dt1;

        $validator = Validator::make($post, ClientBill::$rules);

        if ($validator->fails()) return  Redirect::back()->withErrors($validator)->withInput();

        $client_bill = new ClientBill();
        $client_bill->client_id = $client_id;
        $client_bill->company_id = $company_id;
        $client_bill->group_id = $group_id;
        $client_bill->bill_no = $bill_no;
        $client_bill->bill_date = $bill_date;
        $client_bill->bill_amount = $bill_amount;
        $client_bill->pay_by_date = $pay_by_date;
        $client_bill->bill_entry_date  = date('Y-m-d H:i:s');
        $client_bill->save();

        // dd($client_bill); die();

        $product_name = $request->product_name;

        if ($product_name) {
            foreach ($product_name as $key => $value) {

                $client_bill_trans = new ClientBillTransaction();
                
                $client_bill_trans->client_bill_id = $client_bill->id;
                $client_bill_trans->product_name = $product_name[$key];
                $client_bill_trans->product_quantity = $request->product_quantity[$key];
                $client_bill_trans->product_price = $request->product_price[$key];
                $client_bill_trans->save();

            }
        }

        Session::flash('success','Successfully added outstanding bill details');
        return redirect()->route('view-all-client-outstanding-bill');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client_bill_id = Crypt::decrypt($id);

        $client_bill = ClientBill::with('bill_transaction','engg_bill_follow','client','company','group')->where('id',$client_bill_id)->where('status',1)->first();

       
        // $next_pay_by_date = ClientBillTransaction::where('client_bill_id',$client_bill->id)->where('follow_up',1)->where('status',1)->orderBy('id','desc')->first();           
       
        //dd($next_pay_by_date);
        return view('admin.outstanding.show',compact('client_bill'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client_bill_id = Crypt::decrypt($id);

        $client_bill = ClientBill::with('bill_transaction','engg_bill_follow','client','company','group')->where('id',$client_bill_id)->where('status',1)->first();
        $clients = Client::where('status',1)->groupBy('name')->get();
        $companies = Company::where('status',1)->get();
        $groups = Group::where('status',1)->get();

        $client_bill_transactions = ClientBillTransaction::where('client_bill_id',$client_bill_id)->where('follow_up',0)->where('status',1)->get();

        return view('admin.outstanding.edit',compact('client_bill','clients','companies','groups','client_bill_transactions'));
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
        $client_bill_id = Crypt::decrypt($id);
 
        $client_name = $request->client_id;
        $branch = $request->branch;
        $client_id = Client::where('name',$client_name)->where('branch_name',$branch)->where('status',1)->first()->id;
        $company_id = $request->company_id;
        $group_id = $request->group_id;
        $bill_no = $request->bill_no;

        if($request->input('bill_date') != ''){
                $req_date = $request->input('bill_date');
                $tdr = str_replace("/", "-", $req_date);
                $new_mnf_dt = date('Y-m-d',strtotime($tdr));
            }
        else
            $new_mnf_dt = " ";

        $bill_date = $new_mnf_dt;

        $bill_amount = $request->bill_amount;

        if($request->input('pay_by_date') != ''){
                $req_date1 = $request->input('pay_by_date');
                $tdr1 = str_replace("/", "-", $req_date1);
                $new_mnf_dt1 = date('Y-m-d',strtotime($tdr1));
            }
        else
            $new_mnf_dt1 = " ";

        $pay_by_date = $new_mnf_dt1;

        $validator = Validator::make($post, ClientBill::$rules);

        if ($validator->fails()) return  Redirect::back()->withErrors($validator)->withInput();
      
        $client_bill = ClientBill::with('bill_transaction','engg_bill_follow','client','company','group')->where('id',$client_bill_id)->where('status',1)->first();

        $client_bill->client_id = $client_id;
        $client_bill->company_id = $company_id;
        $client_bill->group_id = $group_id;
        $client_bill->bill_no = $bill_no;
        $client_bill->bill_date = $bill_date;
        $client_bill->bill_amount = $bill_amount;
        $client_bill->pay_by_date = $pay_by_date;
        $client_bill->bill_entry_date  = date('Y-m-d H:i:s');
        $client_bill->save();

        $c_bill_trans = ClientBillTransaction::where('client_bill_id',$client_bill_id)->where('follow_up',0)->where('status',1)->update(['status'=> 0]);

        $product_name = $request->product_name;

        if ($product_name) {
            foreach ($product_name as $key => $value) {

                $client_bill_trans = new ClientBillTransaction();
                
                $client_bill_trans->client_bill_id = $client_bill->id;
                $client_bill_trans->product_name = $product_name[$key];
                $client_bill_trans->product_quantity = $request->product_quantity[$key];
                $client_bill_trans->product_price = $request->product_price[$key];
                $client_bill_trans->save();

            }
        }

        Session::flash('success','Successfully updated outstanding bill details');
        return redirect()->route('view-all-client-outstanding-bill');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client_bill_id = Crypt::decrypt($id);
        // dd($client_bill_id);

        $client_bill = ClientBill::with('bill_transaction','engg_bill_follow','client','company','group')->where('id',$client_bill_id)->where('status',1)->first();
        $client_bill->status = 0;
        $client_bill->save();

        $c_bill_trans = ClientBillTransaction::where('client_bill_id',$client_bill_id)->where('follow_up',0)->where('status',1)->update(['status'=> 0]);

        Session::flash('success','Successfully deleted outstanding bill details');
        return redirect()->route('view-all-client-outstanding-bill');

    }

    public function getFollowupDetail($id)
    {
       $c_bill_id = Crypt::decrypt($id);

       $clients = Client::where('status',1)->groupBy('name')->get();

        // $c_bill_follow = EngineerBillFollowUp::with('client')->where('client_bill_id',$c_bill_id)->where('status',1)->first();

        $c_bill_follow = ClientBill::with('bill_transaction','engg_bill_follow','client','company')->where('id',$c_bill_id)->where('status',1)->first();
        return view('admin.outstanding.update-followup',compact('c_bill_follow','clients')); 
    }

    public function updateFollowupDetail(Request $request, $client_bill_id)
    {
        $c_bill_id = Crypt::decrypt($client_bill_id);
        // $post = $request->all();
        // dd($post);
        $client_bill = ClientBill::where('id',$c_bill_id)->where('status',1)->first();
        $client_bill->bill_status = $request->bill_status;
        $client_bill->save();

        $client_bill_trans = new ClientBillTransaction();
        $client_bill_trans->client_bill_id = $client_bill->id;
        $client_bill_trans->follow_up = 1;
        $client_bill_trans->follow_up_by = Auth::user()->id;
        $client_bill_trans->follow_up_entry_date = date('Y-m-d');

        if($request->input('next_pay_by_date') != ''){
                $req_date = $request->input('next_pay_by_date');
                $tdr = str_replace("/", "-", $req_date);
                $new_mnf_dt = date('Y-m-d',strtotime($tdr));
            }
        else
            $new_mnf_dt = " ";

        $next_pay_by_date = $new_mnf_dt;


        $client_bill_trans->next_pay_by_date = $next_pay_by_date;


        $client_bill_trans->bill_remarks = $request->bill_remarks;
        $client_bill_trans->save();

        Session::flash('success','Successfully updated outstanding bill followup details');
        return redirect()->route('view-all-client-outstanding-bill');

    }

    public function export(Request $request)
    {
        $client_bill = ClientBill::with('bill_transaction','engg_bill_follow','client','company','group')->where('status',1)->get();

        try{
            Excel::create('OutstandingDetails '.date('dmyHis'), function( $excel) use($client_bill){
                $excel->sheet('Outstanding-Details ', function($sheet) use($client_bill){
                  $sheet->setTitle('Outstanding-Details');

                  $sheet->cells('A1:O1', function($cells) {
                    $cells->setFontWeight('bold');
                  });
                  
                  $arr = [];
                  $counter = 1;

                    foreach($client_bill->chunk(500) as $res):

                        foreach( $res as $k => $v) {
                            // dd($v);
                            $arr[$counter]['Sl No']                   = $k+1;
                            $arr[$counter]['Client Name']                     = ucwords($v->client->name);
                            $arr[$counter]['Client Branch Name']                     = ucwords($v->client->branch_name);
                            $arr[$counter]['Zone Name']                     = ucwords($v->client->zone->name);
                            $arr[$counter]['Region Name']                     = ucwords($v->client->region->name);
                            $arr[$counter]['Group Name']                     = ucwords($v->group->name);
                            $arr[$counter]['Company Name']                     = ucwords($v->company->name);
                            $arr[$counter]['Bill No']                     = ucwords($v->bill_no);
                            $arr[$counter]['Bill Amount']                     = $v->bill_amount;

                            if ($v->bill_date != "0000-00-00") {
                                $arr[$counter]['Bill Date']                    = dateFormat($v->bill_date);
                            }else{
                                $arr[$counter]['Bill Date']                 = '';
                            }

                            if ($v->pay_by_date != "0000-00-00") {
                                $arr[$counter]['Bill Pay By Date']                    = dateFormat($v->pay_by_date);
                            }else{
                                $arr[$counter]['Bill Pay By Date']                 = '';
                            }

                          
                            // Outstanding Product Details

                            foreach ($v->bill_transaction as $key1 => $value1){
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
        return redirect()->route('view-all-client-outstanding-bill');
    }

    public function getBranchName(Request $request)
    {
       $client_id = $request->input('client_id');
       if($client_id){

        $branchname = Client::where('name',$client_id)->where('status',1)->get();
        return response()->json($branchname);

       }
        
    }

    public function engineerIndex(Request $request)
    {
        $users = User::where('status',1)->get();
        $clients = Client::where('status',1)->groupBy('name')->get();
        $all_clients = Client::where('status',1)->get();
     

        $engg_bill_follow = EngineerBillFollowUp::with('client_bill','engineer','client')->where('status',1);

        if ($request->engineer_id) {
           $engg_bill_follow =  $engg_bill_follow->where("engineer_id","like",'%'.$request->engineer_id.'%');
        }

        if ($request->bill_no) {
           $engg_bill_follow =  $engg_bill_follow->where("client_bill_id","like",'%'.$request->bill_no.'%');
        }

        $next_pay_by_date = date('Y-m-d', strtotime($request->next_pay_by_date));

        if ($request->next_pay_by_date) {
           $engg_bill_follow =  $engg_bill_follow->where("next_pay_by_date","like",'%'.$next_pay_by_date.'%');
        }

        if ($request->client_id) {
            $client_names = Client::where('name','like','%'.$request->client_id.'%')->where('status',1)->get()->toArray(); 
                $clients_id = [];
                foreach ($client_names as $key => $client_name) {
                    array_push($clients_id, $client_name['id']);
                }
            $engg_bill_follow = $engg_bill_follow->whereIn('client_id',$clients_id);            
        }

        if ($request->branch) {
            $branch_names = Client::select('id')->where('branch_name','like','%'.$request->branch.'%')->where('status',1)->get()->toArray();        
                $branchs = [];
                foreach ($branch_names as $key => $branch_name) {
                    array_push($branchs, $branch_name['id']);
                }
                $engg_bill_follow = $engg_bill_follow->whereIn('client_id',$branchs);
        }

        $engg_bill_follow = $engg_bill_follow->orderBy('id','desc')->get();

        // dd($engg_bill_follow);

        if ($engg_bill_follow->count()) {
              foreach ($engg_bill_follow as $key => $value) {
                $data = $value->client_bill_id;

                // $all_client_bills = ClientBill::where('id',$value->client_bill_id)->where('status',1)->get();
            }  
        }

        // dd($data);
        return view('admin.outstanding.engineer-bill-follow',compact('engg_bill_follow','users','clients','all_clients','all_client_bills'));
    }

    public function engineerShow($id)
    {
       $engg_client_bill_id = Crypt::decrypt($id); 
       $engg_bill_follow = EngineerBillFollowUp::with('client_bill','engineer','client')->where('id',$engg_client_bill_id)->where('status',1)->first();
       return view('admin.outstanding.engineer-bill-follow-show',compact('engg_bill_follow'));
    }


}
