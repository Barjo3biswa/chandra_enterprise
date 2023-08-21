<?php

namespace App\Http\Controllers\Engineer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth,Session,DB,Crypt,Validator,Excel;

use App\User, App\Models\AssignToolKit;

class ToolKitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $user = User::where([['id',$user_id],['status',1]])->first();

        $assignedtoolkits = AssignToolKit::with('user','toolkit')->where([['user_id',$user_id],['status',1]])->get();

        // dd($assignedtoolkits);
        return view('engineer.toolkit.index',compact('assignedtoolkits','user'));
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

    public function export(Request $request) 
    {
        $user_id = Auth::user()->id;
        $user = User::where([['id',$user_id],['status',1]])->first();

        $assignedtoolkits = AssignToolKit::with('user','toolkit')->where([['user_id',$user_id],['status',1]])->orderBy('id','desc')->get();


        try{
            Excel::create('AssignedToolKitDetails '.date('dmyHis'), function( $excel) use($assignedtoolkits){
                $excel->sheet('AssignedToolKit-Details ', function($sheet) use($assignedtoolkits){
                  $sheet->setTitle('AssignedToolKit-Details');

                  $sheet->cells('A1:E1', function($cells) {
                    $cells->setFontWeight('bold');
                  });
                  
                  $arr = [];
                    foreach($assignedtoolkits->chunk(100) as $res):
                        foreach( $res as $k => $v) {
                            //dd($v);
                            $arr[$k]['Sl No']                           = $k+1;
                            $arr[$k]['Tool Kit Name']                   = ucwords($v->toolkit->name);
                            $arr[$k]['Tool Kit Code']                   = $v->toolkit->tool_kit_code;
                            $arr[$k]['Issued Quantity']                 = $v->quantity_to_be_issued;
                            $arr[$k]['Remarks']                         = $v->toolkit->remarks;
                          
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
