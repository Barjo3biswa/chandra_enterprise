<?php

namespace App\Http\Controllers\REST;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;

use Auth,Session,DB,Crypt,Validator,Excel;

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
        $user = JWTAuth::parseToken()->toUser(); 
        // dd($user);
        if($user ){
        $json_arr = array();

        $engg_bill_follow = EngineerBillFollowUp::with('client_bill','engineer','client_bill.client')->where('engineer_id',$user->id)->where('status',1)->orderBy('id','desc')->get();

        if ($engg_bill_follow) {
            $json_arr['status'] = true;
            $json_arr['engg_bill_follow'] = $engg_bill_follow;
        }else{
            $json_arr['status'] = false;
            $json_arr['engg_bill_follow'] = [];
        }

        return response()->json($json_arr);}else{
          return response()->json([
            'status' => false,

          ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = JWTAuth::parseToken()->toUser(); 
        // dd($user);
        if($user ){
        $json_arr = array();

        $assgn_engg = AssignEngineer::with('client')->where('engineer_id',$user->id)->where('status',1)->get();
        $assign_clients = [];
        foreach ($assgn_engg as $key => $assgn_eng) {
            array_push($assign_clients, $assgn_eng['client_id']);
        }

        $bill_clients = ClientBill::with('client')->whereIn('client_id',$assign_clients)->where('status',1)->get();

        if ($bill_clients) {
           $json_arr['status'] = true;
           $json_arr['bill_clients'] = $bill_clients; 
        }else{
            $json_arr['status'] = false;
            $json_arr['bill_clients'] = [];
        }
        

        return response()->json($json_arr);}else{
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
        $user = JWTAuth::parseToken()->toUser(); 
        // dd($user);
        if($user ){
        $json_arr = array();

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

        $engg_bill_follow = new EngineerBillFollowUp();
        $engg_bill_follow->client_bill_id = $client_bill_id;
        $engg_bill_follow->client_id = $client_id;
        $engg_bill_follow->engineer_id = $user->id;
        $engg_bill_follow->next_pay_by_date = $next_pay_by_date;
        $engg_bill_follow->follow_up_entry_date = date('Y-m-d');
        $engg_bill_follow->bill_status = $bill_status;
        $engg_bill_follow->bill_remarks = $bill_remarks;

        if ($engg_bill_follow->save()) {
            $json_arr['status'] = true;
            $json_arr['message'] = 'Successfully added outstanding bill details';
            // $json_arr['engg_bill_follow'] = $engg_bill_follow;
        }
        else{
            $json_arr['status'] = false;
            $json_arr['message'] = 'Please fix the error and try again';
            // $json_arr['engg_bill_follow'] = [];
        }

        return response()->json($json_arr);}else{
          return response()->json([
            'status' => false,

          ]);
        }

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
}
