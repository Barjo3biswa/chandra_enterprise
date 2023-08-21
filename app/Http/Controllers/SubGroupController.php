<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session,DB,Crypt,Validator,Excel;
use App\Models\Group, App\Models\SubGroup;

class SubGroupController extends Controller
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
        $sgroups = SubGroup::with('group')->where('status',1)->orderBy('id','desc')->get();
        return view('admin.subgroup.index',compact('sgroups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = Group::where('status',1)->get();
        return view('admin.subgroup.create',compact('groups'));
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
            'group_id'                  =>  'required',
            'name'                      =>  'required',
         ];

         $messages = [
            'group_id.required'  =>'Please select one group from the list',
            'name.required'  =>'Sub Group name is required',
         ];
         
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                Session::flash('error', 'Please fix the error and try again!');
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $data = $request->all();
            
            SubGroup::create($data);
      }catch(ValidationException $e)
            {
                return Redirect::back();
            }

        Session::flash('success','Successfully added sub group deatils');
        return redirect()->route('view-all-sub-groups');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sg_id = Crypt::decrypt($id);
        $sgrp = SubGroup::with('group')->where('id',$sg_id)->where('status',1)->first();
        return view('admin.subgroup.show',compact('sgrp'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sg_id = Crypt::decrypt($id);
        $groups = Group::where('status',1)->get();
        $sgrp = SubGroup::where('id',$sg_id)->where('status',1)->first();
        return view('admin.subgroup.edit',compact('groups','sgrp'));
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

            $sg_id = Crypt::decrypt($id);
            $sgrp = SubGroup::where('id',$sg_id)->where('status',1)->first();

            $rules = [
                'group_id'                  =>  'required',
                'name'                      =>  'required',
            ];

            $messages = [
                'group_id.required'  =>'Please select one group from the list',
                'name.required'  =>'Sub Group name is required',
            ];
         
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                Session::flash('error', 'Please fix the error and try again!');
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $sgrp->group_id         = $request->group_id;
            $sgrp->name             = $request->name;
            $sgrp->remarks          = $request->remarks;
            $sgrp->save();

            
      }catch(ValidationException $e)
            {
                return Redirect::back();
            }

        Session::flash('success','Successfully updated sub group deatils');
        return redirect()->route('view-all-sub-groups');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sg_id = Crypt::decrypt($id);
        $sgrp = SubGroup::with('group')->where('id',$sg_id)->where('status',1)->first();
        $sgrp->status       = 0;
        $sgrp->save();
        Session::flash('success','Successfully deactivated sub group deatils');
        return redirect()->route('view-all-sub-groups');
    }

    public function export(Request $request) 
    {
        $sgroups = SubGroup::with('group')->where('status',1)->orderBy('id','desc')->get();


        try{
            Excel::create('SubGroupDetails '.date('dmyHis'), function( $excel) use($sgroups){
                $excel->sheet('SubGroup-Details ', function($sheet) use($sgroups){
                  $sheet->setTitle('SubGroup-Details');

                  $sheet->cells('A1:D1', function($cells) {
                    $cells->setFontWeight('bold');
                  });
                  
                  $arr = [];
                    foreach($sgroups->chunk(100) as $res):
                        foreach( $res as $k => $v) {
                            //dd($v);
                            $arr[$k]['Sl No']                   = $k+1;
                            $arr[$k]['Group Name']              = $v->group->name;
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

        Session::flash('success','Successfully exported sub group details');
        return Redirect::route('view-all-sub-groups');
    }
}
