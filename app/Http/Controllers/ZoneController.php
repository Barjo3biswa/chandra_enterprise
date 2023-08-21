<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session,DB,Crypt,Validator,Excel;
use App\Models\Zone, App\Models\Client, App\Models\Region;

class ZoneController extends Controller
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
        $zones = Zone::where('status',1)->orderBy('id','asc')->get();
        return view('admin.zone.index',compact('zones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.zone.create');
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

            'name'                      =>  'required',
         ];

         $messages = [
            'name.required'  =>'Zone name is required',
          ];
         
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                Session::flash('error', 'Please fix the error and try again!');
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $data = $request->all();
            
            Zone::create($data);
      }catch(ValidationException $e)
            {
                return Redirect::back();
            }

        Session::flash('success','Successfully added zone deatils');
        return redirect()->route('view-all-zones');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $z_id = Crypt::decrypt($id);
        $zone = Zone::where('id',$z_id)->where('status',1)->first();
        // $clients = Client::with('zone','region')->where('zone_id',$zone->id)->where('status',1)->paginate('20');

        $clients = Client::with('zone','region')->where('zone_id',$zone->id)->where('status',1);



        if ($request->client_id) {
           $clients=  $clients->where("name","like",'%'.$request->client_id.'%');
        }

        if ($request->branch) {
           $clients=  $clients->where("branch_name","like",'%'.$request->branch.'%');
        }

        if ($request->region_id) {
            $clients=  $clients->where("region_id","like",'%'.$request->region_id.'%');
        }



        $clients = $clients->paginate('20');

        $c_group_by = Client::with('zone','region')->where('status',1)->groupBy('name')->get();
        $regions = Region::where('status',1)->get();

        return view('admin.zone.show',compact('zone','clients','c_group_by','regions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $z_id = Crypt::decrypt($id);
        $zone = Zone::where('id',$z_id)->where('status',1)->first();
        return view('admin.zone.edit',compact('zone'));
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

         $z_id = Crypt::decrypt($id);
         $zone = Zone::where('id',$z_id)->where('status',1)->first();

         $rules = [

            'name'                      =>  'required',
         ];

         $messages = [
            'name.required'  =>'Zone name is required',
        ];
         
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            Session::flash('error', 'Please fix the error and try again!');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $zone->name         = $request->name;
        $zone->remarks      = $request->remarks;
        $zone->save();

      }catch(ValidationException $e)
            {
                return Redirect::back();
            }

        Session::flash('success','Successfully updated zone deatils');
        return redirect()->route('view-all-zones');
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

    public function export(Request $request) 
    {
        $zones = Zone::where('status',1)->orderBy('id','desc')->get();


        try{
            Excel::create('ZoneDetails '.date('dmyHis'), function( $excel) use($zones){
                $excel->sheet('Zone-Details ', function($sheet) use($zones){
                  $sheet->setTitle('Zone-Details');

                  $sheet->cells('A1:D1', function($cells) {
                    $cells->setFontWeight('bold');
                  });
                  
                  $arr = [];
                    foreach($zones->chunk(500) as $res):
                        foreach( $res as $k => $v) {
                            //dd($v);
                            $arr[$k]['Sl No']                   = $k+1;
                            $arr[$k]['Name']                    = $v->name;
                            $arr[$k]['Remarks']                 = $v->remarks;
                          
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

        Session::flash('success','Successfully exported zone details');
        return Redirect::route('view-all-zones');
    }
}
