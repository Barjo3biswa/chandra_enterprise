<?php

namespace App\Http\Controllers;

use App\Models\Assign\AmcAssignedToEngineers;
use Illuminate\Http\Request;
use App\User, App\Models\Company, App\Models\Product, App\Models\Client, App\Models\Complaint, App\Models\Assign\AssignEngineer, App\Models\ClientAmcMaster, App\Models\Dsr\DailyServiceReport, App\Models\ClientAmcTransaction;
use Auth, DB;
use Illuminate\Database\Eloquent\Builder;
class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $current_month = date("F");
        $current_year  = date("Y");
        $tot_company = Company::where('status',1)->count();
        $tot_products = Product::where('status',1)->count();
        $tot_users = User::where('status',1)->count();
        $tot_clients = Client::where('status',1)->count();
        $tot_complaints = Complaint::where('status',1)->count();
        $tot_pendind_complaints = Complaint::where('status',1)->where('complaint_status','!=',3)->count();
        $tot_closed_complaints = Complaint::where('status',1)->where('complaint_status',3)->count();
        $user = Auth::user();
        $engg_tot_assigned_complaints = Complaint::where('complaint_status','!=',3)
        ->where(function($query) use ($user){
            return $query->where('assigned_to', $user->id)
                ->orWhereHas("assigned_engineers", function($query_sub) use ($user){
                    return $query_sub->where("engineer_id", $user->id);
                });
        })
        ->where('status',1)->count();

        $engg_tot_closed_complaints = Complaint::where('complaint_status',3)
        ->where(function($query) use ($user){
            return $query->where('assigned_to', $user->id)
                ->orWhereHas("assigned_engineers", function($query_sub) use ($user){
                    return $query_sub->where("engineer_id", $user->id);
                });
        })
        ->where('status',1)->count();

        $engg_tot_complaints = Complaint::where('status',1)
        ->where(function($query) use ($user){
            return $query->where('assigned_to', $user->id)
                ->orWhereHas("assigned_engineers", function($query_sub) use ($user){
                    return $query_sub->where("engineer_id", $user->id);
                });
        })
        ->count();

        $tot_assigned_complaints = Complaint::where('status',1)->where('assigned_to','!=',null)->count();

        $tot_assigned_clients = AssignEngineer::where('engineer_id',Auth::user()->id)->where('status',1)->count();

        $tot_dsr = DailyServiceReport::where('entry_by',Auth::user()->id)->count();

      
        $ass_engg = AssignEngineer::where('engineer_id',Auth::user()->id)->where('status',1)->get()->toArray();
        // $amc_assigned_manually_count = AmcAssignedToEngineers::where('engineer_id', Auth::user()->id)->count();
        $amc_count = ClientAmcTransaction::whereHas("client_master", function(Builder $client_master_query){
                return $client_master_query->where(function(Builder $where_query){
                    $where_query->whereIn("client_id", function($query){
                        return $query->select("client_id")
                            ->from("assign_engineers")
                            ->where("engineer_id", auth()->id())
                            ->where("status", 1);
                    })
                    ->orWhereIn("id", function($select_ids){
                        return $select_ids->from("amc_assigned_to_engineers")
                            ->select("client_amc_master_id")->where("engineer_id", auth()->id());
                    });
                });
            })
            ->where("amc_month", $current_month)
            ->where("amc_year",  $current_year)
            ->where("status", 1)
            ->count();


        // dd($monthly_amcs);
        $tot_amc = "";
        $client_amc = "";
        $tot_client_amc = "";
        $monthly_amcs = "";
        $client_master = "";
        return view('dashboard.index',compact('tot_company','tot_products','tot_users','tot_clients','tot_complaints','engg_tot_assigned_complaints','tot_pendind_complaints','tot_closed_complaints','tot_assigned_complaints','tot_amc','tot_assigned_clients','client_amc','tot_client_amc','tot_dsr','monthly_amcs','engg_tot_closed_complaints','engg_tot_complaints','client_master',"amc_count"));
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
}
