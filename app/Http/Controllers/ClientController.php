<?php

namespace App\Http\Controllers;

use App\Models\Assign\AssignEngineer;
use Illuminate\Http\Request;
use Session,DB,Crypt,Validator,Excel, Log;
use App\Models\Zone,App\Models\State,App\Models\District, App\Models\Client, App\Models\Region, App\Models\Assign\AssignProductToClient;

class ClientController extends Controller
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
    public function index(Request $request)
    {
        $c_group_by = Client::with('zone','region')->where('status',1)->groupBy('name')->get();
        $zones = Zone::where('status',1)->get();
        $regions = Region::where('status',1)->get();

        $clients = Client::with('zone','region')->where('status',1);
        if ($request->client_id) {
           $clients=  $clients->where("name","like",'%'.$request->client_id.'%');
        }
        if ($request->branch) {
           $clients=  $clients->where("branch_name","like",'%'.$request->branch.'%');
        }
        if ($request->zone_id) {
            $clients=  $clients->where("zone_id",$request->zone_id);
        }
        if ($request->region_id) {
            $clients=  $clients->where("region_id",$request->region_id);
        }

        if($request->product_assigned=='Yes'){
            $clients=  $clients->whereHas('assigned_products');
        }else if($request->product_assigned=='No'){
            $clients=  $clients->whereDoesntHave('assigned_products');
        }

        $clients = $clients->orderBy('id','desc')->get();

        $all_clients = Client::orderBy('id','desc')->where('status',1)->get();

        $all_branches = $all_clients->map(function($item){
            return $item->branch_name;
        });
        $all_branches = $all_branches->toArray();
        asort($all_branches);

        return view('admin.client.index',compact('clients','c_group_by','zones','regions', 'all_clients', 'all_branches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $states = State::where('status',1)->get();
        $dists = District::where('status',1)->get();
        $zones = Zone::where('status',1)->get();
        $clients = Client::where('status',1)->get();
        $regions = Region::where('status',1)->get();
        return view('admin.client.create',compact('states','dists','zones','clients','regions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
        $rules = [

             'name'                        =>  'required',
             'branch_name'                 =>  'required',
             'zone_id'                     =>  'required',
         ];

         $messages = [
            'name.required'                =>'Company name is required',
            'branch_name.required'         =>'Company branch name is required',
            'zone_id.required'             =>'Company zone is required',
         ];
         
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                Session::flash('error', 'Please fix the error and try again!');
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $data = $request->all();
  
            Client::create($data);
      }catch(ValidationException $e)
            {
                return Redirect::back();
            }

        Session::flash('success','Successfully added client deatils');
        return redirect()->route('view-all-client');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         $c_id = Crypt::decrypt($id);
         $client = Client::with('zone','region','state','district')->where('id',$c_id)->where('status',1)->first();

         $assign_product = AssignProductToClient::with('product','company','client')
         ->where('client_id',$client->id)
         ->whereHas("product", function($query){
            return $query->where("status", 1);
         })
         ->where('status',1)
         ->get();

        //  $assign_product = AssignProductToClient::with('product','company','client')
        // ->where('client_id',$assign_p)
        // ->whereHas("product", function($query){
        //     return $query->where("status", 1);
        // })
        // ->get();
         return view('admin.client.show',compact('client','assign_product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $c_id = Crypt::decrypt($id);

        $states = State::where('status',1)->get();
        $dists = District::where('status',1)->get();
        $zones = Zone::where('status',1)->get();
        $client = Client::where('id',$c_id)->where('status',1)->first();
        $clients = Client::where('status',1)->get();
        $regions = Region::where('status',1)->get();
        return view('admin.client.edit',compact('client','states','dists','zones','clients','regions'));
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
        try
        {

        $client_id = Crypt::decrypt($id);
        $client = Client::where('id',$client_id)->where('status',1)->first();

        $rules = [

             'name'                        =>  'required',
             'branch_name'                 =>  'required',
             'zone_id'                     =>  'required',
         ];

         $messages = [
            'name.required'                =>'Company name is required',
            'branch_name.required'         =>'Company branch name is required',
            'zone_id.required'             =>'Company zone is required',
         ];
         
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                Session::flash('error', 'Please fix the error and try again!');
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $client->zone_id                    =$request->zone_id;
            $client->region_id                  =$request->region_id;
            $client->branch_name                =$request->branch_name;
            $client->name                       =$request->name;
            $client->email                      =$request->email;
            $client->ph_no                      =$request->ph_no;
            $client->state                      =$request->state;
            $client->district                   =$request->district;
            $client->pin_code                   =$request->pin_code;
            $client->contact_person_1_name      =$request->contact_person_1_name;
            $client->contact_person_1_email     =$request->contact_person_1_email;
            $client->contact_person_1_ph_no     =$request->contact_person_1_ph_no;
            $client->contact_person_2_name      =$request->contact_person_2_name;
            $client->contact_person_2_email     =$request->contact_person_2_email;
            $client->contact_person_2_ph_no     =$request->contact_person_2_ph_no;
            $client->address                    =$request->address;
            $client->remarks                    =$request->remarks;
            $client->save();


            
      }catch(ValidationException $e)
            {
                return Redirect::back();
            }

        Session::flash('success','Successfully updated client deatils');
        return redirect()->route('view-all-client');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $c_id = Crypt::decrypt($id);
        $client = Client::with('zone','region','state','district')->where('id',$c_id)->where('status',1)->first();
        $client->status =0;
        $client->save();
        AssignProductToClient::where('client_id',$c_id)->update(['status'=>0]);
        AMC
        Session::flash('success','Successfully deactivated client deatils');
        return redirect()->route('view-all-client');
    }

   public function export(Request $request) 
    {
        $clients = Client::with('zone','region')->where('status',1)->orderBy('id','desc')->get();


        try{
            Excel::create('ClientDetails '.date('dmyHis'), function( $excel) use($clients){
                $excel->sheet('Client-Details ', function($sheet) use($clients){
                  $sheet->setTitle('Client-Details');

                  $sheet->cells('A1:Q1', function($cells) {
                    $cells->setFontWeight('bold');
                  });
                  
                  $arr = [];
                    foreach($clients->chunk(500) as $res):
                        foreach( $res as $k => $v) {
                            // dd($v);
                            $arr[$k]['Sl No']                           = $k+1;
                            $arr[$k]['Name']                            = $v->name;
                            $arr[$k]['Branch Name']                     = $v->branch_name;
                            $arr[$k]['Zone Name']                       = $v->zone->name;

                            if($v->region_id != null)
                            {
                                $arr[$k]['Region Name']               = $v->region->name;
                            }else{
                                $arr[$k]['Region Name']               = '';
                            }

                            if($v->region_id != null)
                            {
                                $arr[$k]['Region Address']               = $v->region->address;
                            }else{
                                $arr[$k]['Region Address']               = '';
                            }

                            $arr[$k]['Email']                           = $v->email;
                            $arr[$k]['Phone No']                        = $v->ph_no;
                            $arr[$k]['State']                           = $v->state;
                            $arr[$k]['District']                        = $v->district;
                            $arr[$k]['Pin code']                        = $v->pin_code;
                            $arr[$k]['Contact Person 1 Name']           = $v->contact_person_1_name;
                            $arr[$k]['Contact Person 1 Email']          = $v->contact_person_1_email;
                            $arr[$k]['Contact Person 1 Phone No']       = $v->contact_person_1_ph_no;
                            $arr[$k]['Contact Person 2 Name']           = $v->contact_person_2_name;
                            $arr[$k]['Contact Person 2 Email']          = $v->contact_person_2_email;
                            $arr[$k]['Contact Person 2 Phone No']       = $v->contact_person_2_ph_no;
                            $arr[$k]['Address']                         = $v->address;
                            $arr[$k]['Remarks']                         = $v->remarks;
                          
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

        Session::flash('success','Successfully exported client details');
        return Redirect::route('view-all-client');
    }

    public function getBranchName(Request $request)
    {
        $client_id = $request->input('client_id');

        if($client_id){
             $acheads = Client::where('id',$client_id)->where('status',1)->get();
             return response()->json($acheads);
        }
    }

    // public function filterClientDetails(Request $request)
    // {
    //     $client_name = $request->input('client_name');

    //     dd($client_name);
    // }
    public function convertClient(Request $request){
        if(!auth()->user()->can('convert duplicate client'))
            return redirect()
                ->back()
                ->with("error", "Access Denied.");

        $rules = [
            "from_client_id"    => "required|exists:clients,id",
            "to_client"         => "required",
            "to_branch"         => "required",
        ];
        $messages = [
            "from_client_id.required"    => "Please select a valid client.",
            "from_client_id.exists"      => "Please select a valid client."
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails()){
            return redirect()
                ->back()
                ->with("error", nl2br(implode("\n", $validator->error())));
        }
        $find_to_client = Client::where("name", $request->to_client)
                            ->where("branch_name", $request->to_branch)
                            ->where("id", "!=", $request->from_client_id)
                            ->where("status", 1)
                            ->get();
        $from_client_id = Client::where("id", $request->from_client_id)
                            ->where("status", 1)
                            ->first();

        if(!$from_client_id){
            return redirect()
                ->back()
                ->with("error", "Whoops! Client is already converted.");

        }
        if($find_to_client->count() == 0){
            return redirect()
                ->back()
                ->with("error", "Whoops! Your data doesn't match any record. Please Contact Administrator.");

        }
        if($find_to_client->count() > 1){
            return redirect()
                ->back()
                ->with("error", "Whoops! Multiple Records found. Please Contact Administrator.");
        }
        $find_to_client = $find_to_client->first();
        DB::beginTransaction();
        // updating clients
        try {
            $from_client_id->status = 0;
            $from_client_id->save();
            $assing_to_product_client = AssignProductToClient::where("client_id", $from_client_id->id)
            ->update([
                "client_id" => $find_to_client->id
            ]);
            
            $assing_engineers_client = AssignEngineer::where("client_id", $from_client_id->id)
            ->update([
                "client_id" => $find_to_client->id
            ]);
            Log::notice("Old Client ID {$from_client_id} converted to {$find_to_client}");
            Log::notice("Assigned product to client effected row ".$assing_to_product_client);
            Log::notice("Assigned Engineers effected row ".$assing_engineers_client);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()
                ->route("view-all-client")
                ->with("error", "Whoops! Something went wrong. Please try again later.");
        
        }
        DB::commit();
        return redirect()
            ->route("view-all-client")
            ->with("success", "Client Successfully Converted");
    }

}
