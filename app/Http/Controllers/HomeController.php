<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User, App\Models\Company, App\Models\Product, App\Models\Client, App\Models\Complaint, App\Models\Assign\AssignEngineer, App\Models\ClientAmcMaster, App\Models\Dsr\DailyServiceReport, App\Models\ClientAmcTransaction;
use Auth, DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tot_company = Company::where('status',1)->count();
        $tot_products = Product::where('status',1)->count();
        $tot_users = User::where('status',1)->count();
        $tot_clients = Client::where('status',1)->count();
        $tot_complaints = Complaint::where('status',1)->count();
        $tot_pendind_complaints = Complaint::where('status',1)->where('complaint_status','!=',3)->count();
        $tot_closed_complaints = Complaint::where('status',1)->where('complaint_status',3)->count();
        
        $engg_tot_assigned_complaints = Complaint::where('assigned_to',Auth::user()->id)->where('complaint_status','!=',3)->where('status',1)->count();

        $engg_tot_closed_complaints = Complaint::where('assigned_to',Auth::user()->id)->where('complaint_status',3)->where('status',1)->count();

        $engg_tot_complaints = Complaint::where('assigned_to',Auth::user()->id)->where('status',1)->count();

        $tot_assigned_complaints = Complaint::where('status',1)->where('assigned_to','!=',null)->count();

        $tot_assigned_clients = AssignEngineer::where('engineer_id',Auth::user()->id)->where('status',1)->count();

        $tot_dsr = DailyServiceReport::where('entry_by',Auth::user()->id)->count();

      
        $ass_engg = AssignEngineer::where('engineer_id',Auth::user()->id)->where('status',1)->get()->toArray();

                $amcs = [];
                foreach ($ass_engg as $key => $clnt_amc) {
                    array_push($amcs, $clnt_amc['client_id']);
                }

                // dd($amcs);

                foreach ($amcs as $key => $value) {
                   
                    $client_amc1 = ClientAmcMaster::with('client','roster','amc_master_transaction')->whereIn('client_id',$amcs)->where('status',1)->get()->toArray();

                    $client_amc = ClientAmcMaster::with('client','roster','amc_master_transaction')->whereIn('client_id',$amcs)->where('status',1)->get();

                    $tot_client_amc = ClientAmcMaster::with('client','roster','amc_master_transaction')->whereIn('client_id',$amcs)->where('status',1)->count();


                    $trans = [];
                    foreach ($client_amc1 as $key => $clnt_trns) {
                        array_push($trans, $clnt_trns['id']);
                    }

                    //foreach ($client_amc as $key => $value) {

                        $amc_trans = ClientAmcTransaction::whereIn('client_amc_masters_id',$trans)->where('status',1)->get()->toArray();

                        $amc_trans1 = ClientAmcTransaction::whereIn('client_amc_masters_id',$trans)->where('status',1)->get();

                        $amc_month = [];

                        // $monthly_amcs = [];

                        foreach ($amc_trans as $key => $value1) {

                            array_push($amc_month, date('F',strtotime($value1['amc_month'])));

                            $today[] = date('F');

                            if($amc_month == $today)
                            {
   
                              $monthly_amcs = ClientAmcTransaction::with('client_master')->whereIn('client_amc_masters_id',$trans)->whereIn('amc_month',$amc_month)->where('status',1)->get();
                                 
                            }
                        }
                       
                    //}

                    
                }
        return view('home',compact('tot_company','tot_products','tot_users','tot_clients','tot_complaints','engg_tot_assigned_complaints','tot_pendind_complaints','tot_closed_complaints','tot_assigned_complaints','tot_amc','tot_assigned_clients','client_amc','tot_client_amc','tot_dsr','monthly_amcs','engg_tot_closed_complaints','engg_tot_complaints','client_master'));
    }

    public function testing(){
        $test = 0;
        $rev_client = DB::table('uploaded_tests')->get();
        foreach($rev_client as $key=> $rev_cl){
            $bank_name = $rev_cl->bank_name;
            $branch_name = $rev_cl->branch_name;
            $count = Client::where('status',1)->where('name',$bank_name)->where('branch_name',$branch_name)->get();
            // $count = Client::where('status',1)->where('name', 'LIKE', '%' .$bank_name. '%')->where('branch_name', 'LIKE', '%' .$branch_name. '%')->get();
            // dump($count->count());
            
            if($count->count()>0){
                $test =$test+1;
                // update_remark = 'one_record_found'
                foreach($count as $check){
                    $amc_check = ClientAmcMaster::where('client_id',$check->id)->get();
                    $complant = Complaint::where('client_id',$check->id)->get();
                    if($amc_check->count() ==0 || $complant->count() == 0){
                        $test =$test;
                    }else{
                        // $test =$test+1;
                        // dump($check->id);
                    }
                    
                }
            }
        }

        dd($test);
    }
}
