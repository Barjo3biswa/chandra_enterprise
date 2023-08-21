<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientAmcMaster;
use App\Models\Complaint;
use App\Models\ComplaintMaster;
use App\Models\ComplaintTransaction;
use App\Models\Dsr\DailyServiceReport;
use App\Models\Group;
use App\Models\Product;
use App\Models\Zone;
use App\User;
use Crypt;
use DB;
use Excel;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients   = Client::where('status', 1)->groupBy('name')->get();
        $groups    = Group::where('status', 1)->get();
        $c_masters = ComplaintMaster::where('status', 1)->orderBy('id', 'desc')->get();
        $products  = Product::where('status', 1)->groupBy('name')->get();
        $zones     = Zone::where('status', 1)->get();
        return view('admin.reports.complaint_wise.index', compact('clients', 'groups', 'c_masters', 'products', 'zones'));
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

        $compl = Complaint::with('client', 'group', 'product', 'comp_master', 'user');

        // dd($compl->client->id);

        if ($request->from_date) {
            $compl = $compl->whereDate('complaint_entry_date', '>=', date('Y-m-d', strtotime($request->from_date)));
        }

        if ($request->to_date) {
            $compl = $compl->whereDate('complaint_entry_date', '<=', date('Y-m-d', strtotime($request->to_date)));
        }

        if ($request->zone_id) {

            $zone_id = Zone::where('id', $request->zone_id)->where('status', 1)->first()->name;

            $z_id = Client::where('zone_id', $request->zone_id)->first();

            if ($z_id) {
                $compl = $compl->where('client_id', 'like', '%' . $z_id->id . '%');
            }

        }

        if ($request->client_id) {

            $client_names = Client::select('id')->where('name', 'like', '%' . $request->client_id . '%')->where('status', 1)->get()->toArray();
            // dd($client_name);
            $clients = [];
            foreach ($client_names as $key => $client_name) {
                array_push($clients, $client_name['id']);
            }

            foreach ($client_name as $key => $value) {

                $compl = $compl->whereIn('client_id', $clients);

            }
            // dd($compl);

        }

        if ($request->branch) {
            $branch = Client::where('branch_name', 'like', '%' . $request->branch . '%')->first()->id;

            $compl = $compl->where('client_id', 'like', '%' . $branch . '%');
        }

        if ($request->priority) {
            $compl = $compl->where('priority', 'like', '%' . $request->priority . '%');
        }

        if ($request->group_id) {
            $group_id = Group::where('id', $request->group_id)->where('status', 1)->first()->name;
            $compl    = $compl->where('group_id', 'like', '%' . $request->group_id . '%');
        }

        if ($request->complaint_master_id) {
            $comp_type = ComplaintMaster::where('id', $request->complaint_master_id)->where('status', 1)->first()->complaint_details;
            $compl     = $compl->where('complaint_master_id', 'like', '%' . $request->complaint_master_id . '%');
        }

        if ($request->product_id) {

            $product_id = Product::where('id', $request->product_id)->where('status', 1)->first()->name;
            $compl      = $compl->where('product_id', 'like', '%' . $request->product_id . '%');
        }

        $results = $compl->orderBy('complaint_entry_date', 'DESC')->where('status', 1)->get();

        // dd($results);die();
        return view('admin.reports.complaint_wise.view-result', compact('results', 'zone_id', 'group_id', 'comp_type', 'product_id'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $complaint_id = Crypt::decrypt($id);

        // $complaint_details = Complaint::with('client','group','product','comp_master','user')->where('id',$complaint_id)->where('status',1)->first();

        $complaint_details = DB::table('complaints')
            ->leftJoin('clients', 'clients.id', 'complaints.client_id')
            ->leftJoin('groups', 'groups.id', 'complaints.group_id')
            ->leftJoin('products', 'products.id', 'complaints.product_id')
            ->leftJoin('complaint_masters', 'complaint_masters.id', 'complaints.complaint_master_id')
            ->leftJoin('users', 'users.id', 'complaints.complaint_entry_by')

            ->leftJoin('zones', 'zones.id', 'clients.zone_id')
            ->leftJoin('companies', 'companies.id', 'products.company_id')

            ->leftJoin('assign_product_to_client', 'assign_product_to_client.product_id', 'complaints.product_id')
            ->where('complaints.id', $complaint_id)
            ->where('complaints.status', 1)

            ->select('complaints.*', 'complaint_masters.complaint_details as m_complaint_details', 'users.first_name as first_name', 'users.middle_name as middle_name', 'users.last_name as last_name', 'clients.name as c_name', 'clients.branch_name as c_branch_name', 'zones.name as z_name', 'clients.email as c_email', 'clients.ph_no as c_ph_no', 'clients.address as c_address', 'clients.remarks as c_remarks', 'products.name as p_name', 'groups.name as g_name', 'products.serial_no as p_serial_no', 'products.product_code as p_product_code', 'products.model_no as p_model_no', 'products.brand as p_brand', 'companies.name as company_name', 'assign_product_to_client.date_of_install as date_of_install')

            ->first();

        // dd($complaint_details);

        $complaint_trans = DB::table('complaint_transactions')

            ->leftJoin('assign_engineers', 'complaint_transactions.transaction_assigned_by', 'assign_engineers.engineer_id')

            ->leftJoin('users', 'complaint_transactions.transaction_assigned_by', 'users.id')

            ->where('complaint_transactions.status', 1)
            ->where('complaint_id', $complaint_details->id)
            ->select('complaint_transactions.transaction_assigned_by as transaction_assigned_by', 'users.first_name as first_name', 'users.middle_name as middle_name', 'users.last_name as last_name', 'assign_engineers.zone_id as zone_id', 'users.email as email', 'users.ph_no as ph_no', 'users.emp_code as emp_code', 'users.emp_designation as emp_designation', 'users.role as role', 'complaint_transactions.remarks as remarks', 'complaint_transactions.transaction_by as transaction_by')
            ->first();

        // dd($complaint_trans->transaction_by);
        $last_transaction_by = User::where('id', $complaint_trans->transaction_by)->first();

        // dd($last_transaction_by);

        $complaint_transaction_details = ComplaintTransaction::with('user')->where('complaint_id', $complaint_details->id)->orderBy('id', 'desc')->get();

        $closed_complaint = ComplaintTransaction::with('user')->where('complaint_id', $complaint_details->id)->where('status', 1)->first();

        return view('admin.reports.complaint_wise.show', compact('complaint_details', 'complaint_trans', 'comp_product_date_of_install', 'complaint_transaction_details', 'closed_complaint', 'last_transaction_by'));
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

    public function getBranchName(Request $request)
    {
        $client_id = $request->input('client_id');
        if ($client_id) {

            $branchname = Client::where('name', $client_id)->where('status', 1)->get();
            return response()->json($branchname);

        }

    }
    public function engineersMachine(Request $request)
    {
        $engineers_machine_reports = User::with("assigned_engg.client.assigned_products.product.company")
            ->has("assigned_engg", ">", 0);

        $engineers_machine_reports = $engineers_machine_reports  
            ->where("user_type", 3)
            ->when($request->get("engineer"), function($query){
                return $query->whereHas("assigned_engg", function($sub_sub_query){
                    return $sub_sub_query->where("engineer_id", request("engineer"));
                });
            })
            ->get();
        $where_conditions = [];
        $engineer_wise_count =  [];
        $engineers_machine_reports->each(function($engineer) use (&$engineer_wise_count, &$where_conditions){
            $total = 0;
            foreach($engineer->assigned_engg as $assigned){
                $total += $assigned->client->assigned_products->count();
                foreach ($assigned->client->assigned_products as $assigned_product) {
                    $where_conditions[] = [
                        "client_id"  => $assigned_product->client_id,
                        "product_id" => $assigned_product->product_id,
                    ];
                }
            }
            return $engineer_wise_count[$engineer->id] = $total;
        });

        $client_ids = collect($where_conditions)->map(function($item){
            return $item['client_id'];
        });
        $all_amcs = ClientAmcMaster::with(["amc_master_transaction" => function($select_query){
                return $select_query->select("id","client_amc_masters_id", "amc_rqst_date", "amc_demand");
            },'amc_master_product' => function($select_query){
                return $select_query->select("id", "client_amc_masters_id", "product_id");
            }
        ])
        ->select("id", "client_id", "amc_start_date", "amc_end_date", "financial_year", "amc_duration")
        ->whereIn("client_id", $client_ids)
        ->where("status", 1)
        ->get();
        $all_dsr_preventive = DailyServiceReport::whereIn("client_id", $client_ids)
        ->where("status", 1)
        ->where("maintenance_type", 2)
        ->get();
        $amc_counts = [];
        foreach ($all_amcs as $key => $single_amc) {
            foreach ($single_amc->amc_master_product as $index => $amc_product) {
                if(isset($amc_counts[$single_amc->client_id.$single_amc->product_id])){
                    $amc_counts[$single_amc->client_id.$single_amc->product_id]+= $single_amc->amc_master_transaction->count();
                }else{
                    $amc_counts[$single_amc->client_id.$amc_product->product_id] = $single_amc->amc_master_transaction->count();
                }
            }
        }


        $amc_completed_counts = [];
        foreach ($all_dsr_preventive as $key => $single_dsr) {
            if(isset($amc_completed_counts[$single_dsr->client_id.$single_dsr->product_id])){
                $amc_completed_counts[$single_dsr->client_id.$single_dsr->product_id]+= 1;
            }else{
                $amc_completed_counts[$single_dsr->client_id.$single_dsr->product_id] = 1;
            }
        }

        if($request->get("export-data")){
            return $this->engineersMachineExport($engineers_machine_reports, $engineer_wise_count, $amc_counts, $amc_completed_counts);
        }

        return view("admin.reports.engineer_machines.index", compact("engineer_wise_count", "engineers_machine_reports", "amc_counts", "amc_completed_counts"));
    }
    private function engineersMachineExport($engineers_data, $counting_data, $amc_counts, $amc_completed_counts){
        $export_array = [];
        foreach ($engineers_data as $key => $engineer) {
            $export_array[] = [
                "Engineer Name"  => $engineer->full_name(),
                "Total Machines" => $counting_data[$engineer->id],
                "Client"         => "",
                "Branch"         => "",
                "Product Name"   => "",
                "Company"        => "",
                "Product Code"   => "",
                "Serial No"      => "",
                // "Total AMC"      => "",
                // "AMC Done"      => "",
            ];
            if ($engineer->assigned_engg) {
                foreach ($engineer->assigned_engg as $assigned) {
                    foreach ($assigned->client->assigned_products as $index => $assigned_product) {
                        $export_array[] = [
                            "Engineer Name"  => "",
                            "Total Machines" => "",
                            "Client"         => ($index == 0 ? $assigned->client->name : ""),
                            "Branch"         => ($index == 0 ? $assigned->client->branch_name : ""),
                            "Product Name"   => $assigned_product->product->name,
                            "Company"        => $assigned_product->product->company->name ?? "",
                            "Product Code"   => ($assigned_product->product->product_code == "NULL" ? "" : $assigned_product->product->product_code),
                            "Serial No"      => $assigned_product->product->serial_no,
                            // "Total AMC"      => getTotalAmc($assigned_product, $amc_counts),
                            // "AMC Done"       => getTotalAmcCompleted($assigned_product, $amc_completed_counts),
                        ];
                    }
                }
            }
        }
        Excel::create('Engineers Machines ' . date('dmyHis'), function ($excel) use ($export_array) {
            $excel->sheet('Engineers Machines ', function ($sheet) use ($export_array) {
                $sheet->setTitle('Engineers Machines');
                $sheet->cells('A1:L1', function ($cells) {
                    $cells->setFontWeight('bold');
                });
                $sheet->freezeFirstRow();
                $sheet->fromArray($export_array, null, 'A1', false, true);
            });
        })->download('xlsx');;

        dd($export_array);

        
    }
}
