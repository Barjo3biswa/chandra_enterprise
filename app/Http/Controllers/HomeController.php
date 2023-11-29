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
        $Zones=['Zone 01' => 1,'Zone 02' => 2,'Zone 03' => 3,'Zone 04' => 4,'Zone 05' => 5,'Zone 06' => 6,'Zone 07' => 7,'Zone 08' => 8,'Zone 09' => 9,'Zone 10' => 10,'Zone 11' => 11,'Zone 12' => 12,'Zone 13' => 13,'Zone 14' => 14,'Zone 15' => 15,'Zone 16' => 16,'Zone 17' => 17,'Zone 18' => 18,'Zone 19' => 19,'Zone 20' => 20,'Zone 21' => 21,'Zone 22' => 22,'Zone 23' => 23,'Zone 24' => 24,'Zone 25' => 28,'Zone 26' => 29,'Zone 27' => 30,'Zone 28' => 31,'Zone 29' => 32,'Zone 30' => 25,];
        $test = 0;
        $rev_client = DB::table('uploaded_tests')->get();
        foreach($rev_client as $key=> $rev_cl){
            $bank_name = $rev_cl->bank_name; 
            $branch_name = $rev_cl->branch_name;
            $client = Client::where('status',1)->where('name',$bank_name)->where('branch_name',$branch_name)->get();
            // $client = Client::where('status',1)->where('name', 'LIKE', '%' .$bank_name. '%')->where('branch_name', 'LIKE', '%' .$branch_name. '%')->get();
            // dump($client->count());
            
            // dump($Zones[$rev_cl->zone]);
            
            $count_val = $client->count();
            if($client->count()==0){
                $data = [
                    // 'status' => 0,
                    'update_remark' => 'no_record_found',
                ];
                $client = DB::table('uploaded_tests')->where('id',$rev_cl->id)->update($data);
            }
            else if($client->count()==1){
                $data = [
                    'zone_id' => $Zones[$rev_cl->zone],
                    'update_remark' => 'one_record_found',
                ];
                $client = Client::where('id',$client[0]->id)->update($data);
            }
            else if($count_val > 1){
                $flag=1;
                foreach($client as $key=>$check){
                    $amc_check = ClientAmcMaster::where('client_id',$check->id)->get();
                    $complant = Complaint::where('client_id',$check->id)->get();
                    if($amc_check->count() ==0 || $complant->count() == 0){
                        if(++$key == $count_val && $flag==1){
                            $data = [
                                'zone_id' => $Zones[$rev_cl->zone],
                                'update_remark' => 'multiple_record_found',
                            ];
                            $client = Client::where('id',$check->id)->update($data);
                        }else{
                            $data = [
                                'status' => 0,
                                'update_remark' => 'multiple_record_found',
                            ];
                            $client = Client::where('id',$check->id)->update($data);
                        }
                        $test =$test;
                    }else{
                        $data = [
                            'zone_id' => $Zones[$rev_cl->zone],
                            'update_remark' => 'multiple_record_found',
                        ];
                        $client = Client::where('id',$check->id)->update($data);
                        $flag = 0;
                    }
                    
                }
            }
        }

        dd("success");
    }

    
}
