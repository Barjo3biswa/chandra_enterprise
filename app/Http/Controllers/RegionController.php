<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session,DB,Crypt,Validator,Excel,Auth;
use App\Models\Region;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $regions = Region::where('status',1)->get();
        return view('admin.region.index',compact('regions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.region.create');
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
            'name.required'  =>'Region name is required',
        ];
         
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                Session::flash('error', 'Please fix the error and try again!');
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $data = $request->all();
            
            Region::create($data);
      }catch(ValidationException $e)
            {
                return Redirect::back();
            }

        Session::flash('success','Successfully added region deatils');
        return redirect()->route('view-all-regions');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $r_id = Crypt::decrypt($id);
        $region = Region::where('id',$r_id)->where('status',1)->first();
        return view('admin.region.show',compact('region'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $r_id = Crypt::decrypt($id);
        $region = Region::where('id',$r_id)->where('status',1)->first();
        return view('admin.region.edit',compact('region'));

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
        $r_id = Crypt::decrypt($id);
        $region = Region::where('id',$r_id)->where('status',1)->first();

        $rules = [

            'name'                      =>  'required',
        ];

         $messages = [
            'name.required'  =>'Region name is required',
        ];
         
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            Session::flash('error', 'Please fix the error and try again!');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $region->name = $request->name;
        $region->address = $request->address;
        $region->remarks = $request->remarks;
        $region->save();

        Session::flash('success','Successfully updated region deatils');
        return redirect()->route('view-all-regions');

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $r_id = Crypt::decrypt($id);
        $region = Region::where('id',$r_id)->where('status',1)->first();
        $region->status            = 0;
        $region->save();
        Session::flash('success','Successfully deactivated region deatils');
        return redirect()->route('view-all-regions');
    }

    public function export(Request $request) 
    {
        $zones = Region::where('status',1)->orderBy('id','desc')->get();


        try{
            Excel::create('RegionDetails '.date('dmyHis'), function( $excel) use($zones){
                $excel->sheet('Region-Details ', function($sheet) use($zones){
                  $sheet->setTitle('Region-Details');

                  $sheet->cells('A1:D1', function($cells) {
                    $cells->setFontWeight('bold');
                  });
                  
                  $arr = [];
                    foreach($zones->chunk(500) as $res):
                        foreach( $res as $k => $v) {
                            //dd($v);
                            $arr[$k]['Sl No']                   = $k+1;
                            $arr[$k]['Name']                    = $v->name;
                            $arr[$k]['Address']                    = $v->address;
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

        Session::flash('success','Successfully exported region details');
        return Redirect::route('view-all-regions');
    }
}
