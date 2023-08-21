<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session,DB,Crypt,Validator,Excel;
use App\Models\Group, App\Models\Product;

class GroupController extends Controller
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
        $groups = Group::where('status',1)->orderBy('id','desc')->get();
        return view('admin.group.index',compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.group.create');
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
            'name.required'  =>'Group name is required',
        ];
         
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                Session::flash('error', 'Please fix the error and try again!');
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $data = $request->all();
            
            Group::create($data);
      }catch(ValidationException $e)
            {
                return Redirect::back();
            }

        Session::flash('success','Successfully added group deatils');
        return redirect()->route('view-all-groups');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $g_id = Crypt::decrypt($id);
        $grp = Group::where('id',$g_id)->where('status',1)->first();
        $products = Product::with('company')->where('group_id',$grp->id)->where('status',1)->get();
        return view('admin.group.show',compact('grp','products')); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $g_id = Crypt::decrypt($id);
        $grp = Group::where('id',$g_id)->where('status',1)->first();
        return view('admin.group.edit',compact('grp')); 
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
            $g_id = Crypt::decrypt($id);
            $grp = Group::where('id',$g_id)->where('status',1)->first();

            $rules = [

                'name'                      =>  'required',
             ];

             $messages = [
                'name.required'  =>'Group name is required',
             ];
             
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                Session::flash('error', 'Please fix the error and try again!');
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $grp->name      = $request->name;
            $grp->remarks   = $request->remarks;
            $grp->save();

        }catch(ValidationException $e)
            {
                return Redirect::back();
            }

        Session::flash('success','Successfully updated group deatils');
        return redirect()->route('view-all-groups');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $g_id = Crypt::decrypt($id);
        $grp = Group::where('id',$g_id)->where('status',1)->first();
        $grp->status            = 0;
        $grp->save();
        Session::flash('success','Successfully deactivated group deatils');
        return redirect()->route('view-all-groups'); 
    }

    public function export(Request $request) 
    {
        $groups = Group::where('status',1)->orderBy('id','desc')->get();


        try{
            Excel::create('GroupDetails '.date('dmyHis'), function( $excel) use($groups){
                $excel->sheet('Group-Details ', function($sheet) use($groups){
                  $sheet->setTitle('Group-Details');

                  $sheet->cells('A1:C1', function($cells) {
                    $cells->setFontWeight('bold');
                  });
                  
                  $arr = [];
                    foreach($groups->chunk(100) as $res):
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

        Session::flash('success','Successfully exported group details');
        return Redirect::route('view-all-groups');
    }


}
