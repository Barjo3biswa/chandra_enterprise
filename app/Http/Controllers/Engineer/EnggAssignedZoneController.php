<?php

namespace App\Http\Controllers\Engineer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use \App\Models\Zone, App\Models\Assign\AssignEngineer, App\Models\Client, App\Models\Region;
use Auth,Session,DB,Crypt,Validator,Excel;

class EnggAssignedZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
            $assigned_zones = AssignEngineer::with('zone')->where('engineer_id', Auth::user()->id)->where('status',1)->groupBy('zone_id')->get();
        // dd($assigned_zones);
        return view('engineer.assign.view_all_assign_zone',compact('assigned_zones'));
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
    public function show(Request $request,$zone_id)
    {
        $z_id = Crypt::decrypt($zone_id);

        $zones = AssignEngineer::with('zone','client')->where('zone_id',$z_id)->where('engineer_id', Auth::user()->id)->paginate('20');

        // dd($zones);

        foreach ($zones as $key => $value) {
           $clients = Client::with('zone','region')->where('id',$value->client_id)->where('status',1);
        }

        if ($request->client_id) {
           $clients=  $clients->where("name","like",'%'.$request->client_id.'%');
        }

        if ($request->branch) {
           $clients=  $clients->where("branch_name","like",'%'.$request->branch.'%');
        }

        if ($request->region_id) {
            $clients=  $clients->where("region_id","like",'%'.$request->region_id.'%');
        }



        $clients = $clients->get();
         // dd($clients);

        $c_group_by = Client::with('zone','region')->where('status',1)->groupBy('name')->get();
        $regions = Region::where('status',1)->get();

        return view('engineer.assign.show_assign_zone',compact('zones','clients','c_group_by','regions'));
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
