<?php

namespace App\Http\Controllers\REST;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Assign\AmcAssignedToEngineers;
use App\Models\Client;
use DB,Hash,Auth;
use JWTAuth;
use Validator;
use App\User;

use Config;

use App\Models\Complaint, App\Models\Assign\AssignEngineer, App\Models\ClientAmcMaster, App\Models\ClientAmcTransaction, App\Models\Dsr\DailyServiceReport, App\Models\Outstanding\EngineerBillFollowUp;

class DashoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = JWTAuth::parseToken()->toUser();
        $json_arr = array();

    if (isset($user)) {

        $engg_tot_closed_complaints = Complaint::where('complaint_status',3)
        ->where(function($query) use ($user){
            return $query->where('assigned_to', $user->id)
                ->orWhereHas("assigned_engineers", function($query_sub) use ($user){
                    return $query_sub->where("engineer_id", $user->id);
                });
        })
        ->where('status',1)
        ->count();

        if (isset($engg_tot_closed_complaints)) {
            $json_arr['status'] = true;
            $json_arr['engg_tot_closed_complaints'] = $engg_tot_closed_complaints;
        }

        $engg_tot_assigned_complaints = Complaint::where('complaint_status','!=',3)
        ->where(function($query) use ($user){
            return $query->where('assigned_to', $user->id)
                ->orWhereHas("assigned_engineers", function($query_sub) use ($user){
                    return $query_sub->where("engineer_id", $user->id);
                });
        })
        ->where('status',1)
        ->count();
        if (isset($engg_tot_assigned_complaints)) {
            $json_arr['status'] = true;
            $json_arr['engg_tot_assigned_complaints'] = $engg_tot_assigned_complaints;
        }

        $engg_tot_complaints = Complaint::where('status',1)
        ->where(function($query) use ($user){
            return $query->where('assigned_to', $user->id)
                ->orWhereHas("assigned_engineers", function($query_sub) use ($user){
                    return $query_sub->where("engineer_id", $user->id);
                });
        })
        ->count();

        if (isset($engg_tot_complaints)) {
            $json_arr['status'] = true;
            $json_arr['engg_tot_complaints'] = $engg_tot_complaints;
        }

        $tot_dsr = DailyServiceReport::where('entry_by',$user->id)->where('status',1)->count();

        if (isset($tot_dsr)) {
            $json_arr['status'] = true;
            $json_arr['tot_dsr'] = $tot_dsr;
        }

        $eng_assigned_to_amc_count = monthlyAMC()
            ->count();
        $json_arr['monthly_amcs'] = $eng_assigned_to_amc_count;
        if($eng_assigned_to_amc_count){
            if($json_arr['status']){
                $json_arr['status'] = true;
            }else{
                $json_arr['status'] = false;
            }
        }
        //dd($eng_assigned_to_amc_count);
        $tot_client_amc = ClientAmcMaster::with(["roster", "client"])
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
            return $query->where("status", 1);
        })
        ->count();

        if ($tot_client_amc) {
            $json_arr['status'] = true;
            $json_arr['tot_client_amc'] = $tot_client_amc;
        }
        else{
            $json_arr['status'] = false;
            $json_arr['tot_client_amc'] = 0;
        }
        $engg_bill_follow_count = EngineerBillFollowUp::with('client_bill','engineer')->where('engineer_id',$user->id)->where('status',1)->count();

        if ($engg_bill_follow_count) {
           $json_arr['engg_bill_follow_count'] = $engg_bill_follow_count; 
        }else{
           $json_arr['engg_bill_follow_count'] = 0; 
        }

        $permissions = DB::table('model_has_permissions')
                        ->leftJoin('permissions','permissions.id','model_has_permissions.permission_id')
                        ->leftJoin('users','users.id','model_has_permissions.model_id')
                        ->where('model_id',$user->id)
                        ->select('permissions.name as permission_name')
                        ->get();

        if ($permissions->count()) {
           $json_arr['permissions'] = $permissions; 
        }else{
           $json_arr['permissions'] = []; 
        }


        $json_arr['status'] = true;
        $json_arr['today'] = date('Y-m-d');
        // $json_arr['user'] = $user;
        $json_arr['version'] = (Int)env("APP_VERSION_API"); 

        return response()->json($json_arr);}else{
            return response()->json([
                'status' => false
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
    public function userClientData()
    {
        $user = JWTAuth::parseToken()->toUser();
        $clients = Client::select("id", "name", "branch_name")->whereIn("id", function($query) use ($user){
            return $query->select("id")
                ->from("assign_engineers")
                ->where("engineer_id", $user->id);
        })
        ->get();
        $clients_count = $clients->count();
        $main_grouped_clients = [];
        $clients_grouped = $clients->groupBy("name");
        if(sizeof($clients_grouped )){
            foreach ($clients_grouped as $key => $clients) {
                $main_grouped_clients[] = [
                    "name"  => $key,
                    "branches"  => $clients
                ];
            }
        }
        
        if($clients_count){
            $data = [
                "status" => true,
                "message"   => $clients_count." client records found.",
                'data'  => $main_grouped_clients
            ];
        }else{
            $data = [
                "status" => false,
                "message"   => $clients_count." client records found.",
                'data'  => []
            ];
        }
        return $data;
    }
    
    public function ajaxClientNameSearch(Request $request)
    {
        $clients = Client::query();
        $clients->when($request->get("client_name"), function($query){
            return $query->where("name","LIKE", "%".request('client_name')."%");
        });
        $clients = $clients->select("name")
        ->orderBy("name", "ASC")
        ->groupBy("name")
        ->get();
        return response()
            ->json($clients);
    }
    
    public function ajaxBranchNameSearch(Request $request)
    {
        $branches = Client::query();
        $branches->when($request->get("branch_name"), function($query){
            return $query->where("branch_name","LIKE", "%".request('branch_name')."%");
        });
        $branches = $branches->select("branch_name")
        ->orderBy("branch_name", "ASC")
        ->groupBy("branch_name")
        ->get();
        return response()
            ->json($branches);
    }
}
