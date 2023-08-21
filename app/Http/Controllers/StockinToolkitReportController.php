<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth,Session,DB,Crypt,Validator,Excel;

use App\User, App\Models\AssignToolKit, App\Models\ToolKit;

class StockinToolkitReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function userAssignedToolkit()
    {
        $users = User::where('status',1)->get();
        //dd($users);
        return view('admin.reports.toolkit.create',compact('users'));
    }

    public function userAssignedToolkitStore(Request $request)
    {
        $user_id = $request->user_id;

        $user = User::where([['id',$user_id],['status',1]])->first();

        $assignedtoolkits = AssignToolKit::with('user','toolkit')->where([['user_id',$user_id],['status',1]])->orderBy('id','desc')->get();

        // foreach ($assignedtoolkits as $key => $value) {
        //     $toolkits = ToolKit::where([['status',1],['id',$value->tool_kit_id]])->get();
        // }

        
        // foreach ($toolkits as $key => $value) {

        //   $all_assigned_toolkits[$key] = AssignToolKit::where([['user_id',$user_id],['tool_kit_id',$value->id],['status',1]])->groupBy('tool_kit_id')->sum('quantity_to_be_issued');
        // }

        // dd($all_assigned_toolkits);
        return view('admin.reports.toolkit.toolkit-result',compact('assignedtoolkits','user'));
    }

    public function export(Request $request) 
    {
        $user_id = $request->user_id;
        // dd($user_id);die();
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

        Session::flash('success','Successfully exported toolkit details');
        return Redirect::route('view-all-groups');
    }

    public function userAssignedToolkitTotal(Request $request)
    {
        $toolkits = ToolKit::where('status',1)->get();
        foreach ($toolkits as $key => $value) {

           $all_asgn_tlkts[$key] = AssignToolKit::with('user','toolkit')->where([['tool_kit_id',$value->id],['status',1]])->get(); 

           $all_assigned_toolkits[$key] = AssignToolKit::where([['tool_kit_id',$value->id],['status',1]])->groupBy('tool_kit_id')->sum('quantity_to_be_issued');
        }


        // dd($all_asgn_tlkts);
        return view('admin.reports.toolkit.total-toolkit',compact('toolkits','all_assigned_toolkits','all_asgn_tlkts'));
    }

    public function exportTotal(Request $request) 
    {
        $toolkits = ToolKit::where('status',1)->get();
        foreach ($toolkits as $key => $value) {

           // $all_asgn_tlkts[$key] = AssignToolKit::with('user','toolkit')->where([['tool_kit_id',$value->id],['status',1]])->get(); 

           $all_assigned_toolkits[$key] = AssignToolKit::where([['tool_kit_id',$value->id],['status',1]])->groupBy('tool_kit_id')->sum('quantity_to_be_issued');
        }

        try{
            Excel::create('TotalAssignedToolKit '.date('dmyHis'), function( $excel) use($toolkits, $all_assigned_toolkits){
                $excel->sheet('TotalAssignedToolKit ', function($sheet) use($toolkits, $all_assigned_toolkits){
                  $sheet->setTitle('TotalAssignedToolKit');

                  $sheet->cells('A1:E1', function($cells) {
                    $cells->setFontWeight('bold');
                  });
                  
                  $arr = [];
                    foreach($toolkits->chunk(100) as $res):
                        foreach( $res as $k => $v) {
                            //dd($v);
                            $arr[$k]['Sl No']                           = $k+1;
                            $arr[$k]['Tool Kit Name']                   = ucwords($v->name);
                            $arr[$k]['Tool Kit Code']                   = $v->tool_kit_code;
                            $arr[$k]['Assigned Quantity']                 = $all_assigned_toolkits[$k];
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

        Session::flash('success','Successfully exported toolkit details');
        return Redirect::route('view-all-groups');
    }

    public function userAssignedToolkitEngineerWise(Request $request)
    {
        $user_wise_toolkits = User::with("assigned_toolkits")
            ->whereIn("user_type", [2,3])
            ->orderBy("first_name")
            ->has("assigned_toolkits")
            ->get();
        if($request->get("export")){
            $this->export_user_wise_toolkit($user_wise_toolkits);
        }
        return view('admin.reports.toolkit.engineer-wise', compact('user_wise_toolkits'));
    }

    public function export_user_wise_toolkit($user_wise_toolkits)
    {
        $filename = "assigned_toolkits_".date("Ymd");
        Excel::create($filename, function ($excel) use ($user_wise_toolkits) {
            $excel->sheet('ToolKit-Details ', function ($sheet) use ($user_wise_toolkits) {
                $sheet->setTitle('ToolKit-Details');

                $sheet->cells('A1:E1', function ($cells) {
                    $cells->setFontWeight('bold');
                });

                $sheet->loadView('admin.reports.toolkit.engineer-wise-table', compact('user_wise_toolkits'));

            });
        })->download('xlsx');
    }
}
