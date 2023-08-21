<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session,DB,Crypt,Validator,Redirect,Excel,Auth;
use App\User, App\Models\State, App\Models\District, App\Models\ToolKit, App\Models\AssignToolKit, App\Models\Assign\AssignEngineer, App\Models\IssueEngineer, App\Models\IssueEngineerTransaction, App\Models\SparePartMaster, App\Models\SparePartTransaction;

class UserController extends Controller
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
        $users = User::query();

        if ($request->first_name) {
            $users=  $users->where("first_name","like",'%'.$request->first_name.'%');
        }

        if ($request->middle_name) {
            $users=  $users->where("middle_name","like",'%'.$request->middle_name.'%');
        }

        if ($request->last_name) {
            $users=  $users->where("last_name","like",'%'.$request->last_name.'%');
        }

        if ($request->emp_code) {
            $users=  $users->where("emp_code","like",'%'.$request->emp_code.'%');
        }

        if ($request->dob) {
            $users=  $users->where("dob","like",'%'.$request->dob.'%');
        }

        if ($request->ph_no) {
            $users=  $users->where("ph_no","like",'%'.$request->ph_no.'%');
        }

        if ($request->email) {
            $users=  $users->where("email","like",'%'.$request->email.'%');
        }

        if ($request->emp_designation) {
            $users=  $users->where("emp_designation","like",'%'.$request->emp_designation.'%');
        }

        if ($request->role) {
            $users=  $users->where("role","like",'%'.$request->role.'%');
        }
        if($request->get("deactivated_user") == 1){
            $users = $users->where('status', 2);
        }else{
            $users = $users->where('status', 1);
        }
        $users = $users->orderBy('id','desc')->get();
        return view('admin.user.index',compact('users'));
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
        return view('admin.user.create',compact('states','dists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
      $data = $request->all();

      $validator = Validator::make($data, User::$rules);
      if ($validator->fails()){
         return Redirect::back()->withErrors($validator)->withInput();
      } 

      $admin_code = 'adm'.date('dmyHis');
      $manager_code = 'man'.date('dmyHis');
      $engineer_code = 'eng'.date('dmyHis');

      // if($emp_code = User::where('emp_code',$data['emp_code'])->where('status',1)->exists()){
      //     Session::flash('error','Emp code is already in database, try another');
      //     return redirect()->back();
      // }else{
          if($request->input('dob') != ''){
          $req_date = $request->input('dob');
          $tdr = str_replace("/", "-", $req_date);
          $new_dob = date('Y-m-d',strtotime($tdr));
          }
          else
            $new_dob = "";  

          $data['dob'] =  $new_dob;

          $data['password'] = bcrypt($request->password);

          if ($data['role'] == 'admin') {
              $data['user_type'] = 1;
              
          }

          if ($data['role'] == 'manager') {
              $data['user_type'] = 2;
              
          }

          if ($data['role'] == 'engineer') {
              $data['user_type'] = 3;
              
          }
 
          // dd($data);die();
           User::create($data);

           Session::flash('success','Successfully added user details');
           return Redirect::route('view-all-users');
      // }
     }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_id = Crypt::decrypt($id);
        $user = User::where('id',$user_id)->whereIn('status', [1, 2])->first();
        $assign_user_tools = AssignToolKit::with('user','toolkit')->where([['user_id',$user_id],['status',1]])->groupBy('assign_date')->orderBy('id','desc')->paginate(5);
        // dd($assign_user_tools);
        $assign_eng = AssignEngineer::with('user','client')->where('engineer_id',$user->id)->get();
        foreach ($assign_eng as $key => $value) {

          $assign_client_details = DB::table('assign_product_to_client')
                    ->leftJoin('products','products.id','assign_product_to_client.product_id')
                    ->leftJoin('companies','companies.id','assign_product_to_client.company_id')
                    ->where('assign_product_to_client.client_id',$value->client_id)
                    ->where('assign_product_to_client.status',1)
                    ->select('products.id as id','products.name as name','products.brand as brand','products.product_code as product_code','products.model_no as model_no','products.serial_no as serial_no','assign_product_to_client.date_of_install as date_of_install','companies.name as company_name')
                    ->get();

        }

      $assigned_spare_parts = SparePartMaster::with('spare_part_transaction','user')->where('engineer_id',$user->id)->where('trans_type','=','iss')->where('status',1)->get();

        $all_sp_prts = [];
        if ($assigned_spare_parts) {

            foreach($assigned_spare_parts as $key => $value) {
                    $all_spare_parts = SparePartTransaction::where('spare_part_master_id',$value->id)->where('status',1)->get();
                    // dd($all_spare_parts);

                    $all_spare_parts_to_array = SparePartTransaction::with('spare_part')->where('spare_part_master_id',$value->id)->where('status',1)->get();


                    foreach ($all_spare_parts_to_array as $key1 => $value1) {
                        // array_push($all_sp_prts, $value1['spare_parts_id']);
                        $all_sp_prts[$value1->spare_parts_id]  = $value1;
                    }


              }

              foreach ($all_sp_prts as $key2 => $value2) {

                        $stock_in[$key2] = IssueEngineerTransaction::with('spare_part','user')->where('spare_part_id',$key2)->where('engineer_id',$user->id)->where('status',1)->sum('stock_in');


                        $stock_out[$key2] = IssueEngineerTransaction::with('spare_part','user')->where('spare_part_id',$key2)->where('engineer_id',$user->id)->where('status',1)->sum('stock_out');

                        $stock_in_hand[$key2] = $stock_in[$key2]-$stock_out[$key2];
                    }
              }

// dd($stock_in_hand);

        return view('admin.user.show',compact('user','assign_user_tools','assign_eng','assign_client_details','issued_spare_part','stock_in_hand','all_sp_prts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user_id = Crypt::decrypt($id);
        $user = User::where('id',$user_id)->where('status',1)->first();
        $states = State::where('status',1)->get();
        $dists = District::where('status',1)->get();
        return view('admin.user.edit',compact('states','dists','user'));
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

        // $rules = [
        //     'title'                         => 'required',
        //     'first_name'                    => 'required|min:3',
        //     'emp_designation'               => 'required',
        //     'emp_code'                      => 'required',
        //     'role'                          => 'required',
        // ];

        // $validator = Validator::make($request->all(), $rules);

        // if ($validator->fails()) {
        //     Session::flash('error', 'Please fix the error and try again!');
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }


        $user_id = Crypt::decrypt($id);
        $user = User::where('id',$user_id)->where('status',1)->first();

// $emp_code_old = User::where('emp_code',$user->emp_code)->where('status',1)->first();

// $emp_code_new = $request->emp_code;

//         if($emp_code_old == $emp_code_new){
//             dd('equal value');
//         }elseif($emp_code = User::where('emp_code',$emp_code_new)->where('status',1)->exists()){
//             dd('have emp code');
//         }else{
//             dd('no data found');
//         } 

//     die();
   
        $user->title                    = $request->title;
        $user->first_name               = $request->first_name;
        $user->middle_name              = $request->middle_name;
        $user->last_name                = $request->last_name;
        $user->email                    = $request->email;
        $user->emp_designation          = $request->emp_designation;
        $user->dob                      = $request->dob;
        $user->pan_card_no              = $request->pan_card_no;
        $user->role                     = $request->role;
       
        if ($user->role == 'admin') {
           $user->user_type          = 1;
        }

        if ($user->role == 'manager') {
           $user->user_type          = 2;
        }

        if ($user->role == 'engineer') {
           $user->user_type          = 3;
        }

        $user->emp_code                 = $request->emp_code;
        $user->gender                   = $request->gender;
        $user->ph_no                    = $request->ph_no;
        $user->state                    = $request->state;
        $user->district                 = $request->district;
        $user->pin_code                 = $request->pin_code;
        $user->address                  = $request->address;
        $user->remarks                  = $request->remarks;
        $user->save();

        Session::flash('success','Successfully updated user details');
        return Redirect::route('view-all-users');
     

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user_id = Crypt::decrypt($id);
        $user = User::where('id',$user_id)->where('status', 2)->first();
        $user->status                 = 0;
        $user->save();

        Session::flash('success','Successfully Deleted user details');
        return Redirect::route('view-all-users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deactivateUser($id)
    {
        $user_id = Crypt::decrypt($id);
        $user = User::where('id', $user_id)->where('status',1)->first();
        $user->status                 = 2;
        $user->save();

        Session::flash('success', 'Successfully deactivated user details');
        return Redirect::route('view-all-users');
    }

    /**
     * Active User
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activateUser($id)
    {
        $user_id = Crypt::decrypt($id);
        $user = User::where('id', $user_id)->where('status', 2)->first();
        $user->status                 = 1;
        $user->save();

        Session::flash('success', 'Successfully Activated.');
        return Redirect::route('view-all-users');
    }

    public function getDistList(Request $request) 
    {
        $state = $request->input('state');

        if($state){
             $acheads = District::where('state_id',$state)->where('status',1)->get();
             return response()->json($acheads);
        }
    }


    public function changePassword($id)
    {
        $user_id = Crypt::decrypt($id);
        $user = User::where('id',$user_id)->where('status',1)->first();
       

        $user->password = bcrypt('chandra@2019');
        $user->save();
      
        Session::flash('reset_password',ucwords($user->name).' to '.' chandra@2019' );
        return Redirect::route('view-all-users');

    }


    public function export(Request $request) 
    {
        $users = User::query();
        
        if($request->get("deactivated_user") == 1){
            $users = $users->where('status', 2);
        }else{
            $users = $users->where('status', 1);
        }
        $users = $users->orderBy('id','desc')->get();


        try{
            Excel::create('UserDetails '.date('dmyHis'), function( $excel) use($users){
                $excel->sheet('User-Details ', function($sheet) use($users){
                  $sheet->setTitle('User-Details');

                  $sheet->cells('A1:N1', function($cells) {
                    $cells->setFontWeight('bold');
                  });
                  
                  $arr = [];
                    foreach($users->chunk(500) as $res):
                        foreach( $res as $k => $v) {
                            //dd($v);
                            $arr[$k]['Sl No']                   = $k+1;
                            $arr[$k]['Name']                    = $v->first_name.' '.$v->middle_name.' '.$v->last_name;
                            $arr[$k]['Username']                = $v->emp_code;
                            $arr[$k]['Email']                   = $v->email;
                            $arr[$k]['DOB']                     = $v->dob;
                            $arr[$k]['Designation']             = $v->emp_designation;
                            $arr[$k]['Role']                    = $v->role;
                            $arr[$k]['Pan card no']             = $v->pan_card_no;
                            $arr[$k]['Phone no']                = $v->ph_no;
                            $arr[$k]['State']                   = $v->state;
                            $arr[$k]['District']                = $v->district;
                            $arr[$k]['Pin code']                = $v->pin_code;
                            $arr[$k]['Address']                 = $v->address;
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

        Session::flash('success','Successfully exported user details');
        return Redirect::route('view-all-users');
    }


    public function assignTool($id)
    {
        $user_id = Crypt::decrypt($id);

        $user = User::where('id',$user_id)->first();
        $tools = ToolKit::where('status',1)->get();
        return view('admin.user.assign-tool',compact('user','tools'));
    }

    public function assignToolStore(Request $request, $id)
    {
        $user_id = Crypt::decrypt($id);

        // $post = $request->all();

        // dd($post);

        $tool_kit = $request->tool_kit;
        $quantity_to_be_issued = $request->quantity_to_be_issued;
        $remarks = $request->remarks;
        $today = date('Y-m-d');
        $user = User::find($user_id);

        if ($request->select_all) {
          foreach ($tool_kit as $key => $value) {
             $assign_tool_kit = new AssignToolKit();
             $assign_tool_kit->user_id = $user->id ;
             $assign_tool_kit->tool_kit_id = $value;
             $assign_tool_kit->quantity_to_be_issued = $quantity_to_be_issued[$key];
             $assign_tool_kit->assign_date = $today;
             $assign_tool_kit->remarks = $remarks[$key];

             // dd($assign_tool_kit);
             $assign_tool_kit->save();

             Session::flash('success','Successfully assign tool kits to the user');
          }
        }else{
          foreach ($tool_kit as $key => $value) {
             $assign_tool_kit = new AssignToolKit();
             $assign_tool_kit->user_id = $user->id ;
             $assign_tool_kit->tool_kit_id = $value;
             $assign_tool_kit->quantity_to_be_issued = $quantity_to_be_issued[$key];
             $assign_tool_kit->assign_date = $today;
             $assign_tool_kit->remarks = $remarks[$key];

             // dd($assign_tool_kit);
             $assign_tool_kit->save();

             Session::flash('success','Successfully assign tool kits to the user');
          }
        }

        // dd($assign_tool_kit);
      return redirect()->route('view-all-users');
    }


    public function showAssignTool(Request $request, $user_id)
    {
        $user_id = Crypt::decrypt($user_id);

        $user = User::where('id',$user_id)->first();

        $assign_date = $request->assign_date;
        
        $assign_user_tools = AssignToolKit::with('user','toolkit')->where([['user_id',$user_id],['assign_date',$assign_date],['status',1]])->orderBy('id','desc')->paginate(10);
        
       //dd($user_assigned_toolkits);
        return view('admin.user.show-assigned-toolkit',compact('user','assign_user_tools','assign_date'));
    }

    public function editAssignTool(Request $request, $user_id)
    {
        $user_id = Crypt::decrypt($user_id);

        $user = User::where('id',$user_id)->first();
        $assign_date = $request->assign_date_edit;


        $tools = ToolKit::where('status',1)->get();
        foreach($tools as $key=>$val){
          $user_assigned_toolkits[$key] = AssignToolKit::where([['user_id',$user_id],['assign_date',$assign_date],['status',1],['tool_kit_id',$val->id]])->first();
        }
        
       // dd($user_assigned_toolkits);
        return view('admin.user.edit-assign-tool',compact('user','tools','user_assigned_toolkits','assign_date'));
    }

    public function updateAssignTool(Request $request,$id)
    {
      $user_id_new = Crypt::decrypt($id);

      $assign_date = $request->assign_date;

        $delete_assign_toolkit = AssignToolKit::where([['user_id',$user_id_new],['assign_date',$assign_date],['status',1]])->update(['status' => 0]);

        // dd($user_id_new);

        $post = $request->all();
        $tool_kit = $post['tool_kit'];
        $quantity_to_be_issued = $post['quantity_to_be_issued'];
        $remarks = $post['remarks'];
        $today = date('Y-m-d');
        $user = User::find($user_id_new);

        if ($request->select_all) {
          foreach ($tool_kit as $key => $value) {
             $assign_tool_kit = new AssignToolKit();
             $assign_tool_kit->user_id = $user->id ;
             $assign_tool_kit->tool_kit_id = $value;
             $assign_tool_kit->quantity_to_be_issued = $quantity_to_be_issued[$key];
             $assign_tool_kit->assign_date = $today;
             $assign_tool_kit->remarks = $remarks[$key];

             // dd($assign_tool_kit);
             $assign_tool_kit->save();

             Session::flash('success','Updated successfully assign tool kits to the user');
          }
        }else{
          foreach ($tool_kit as $key => $value) {
             $assign_tool_kit = new AssignToolKit();
             $assign_tool_kit->user_id = $user->id ;
             $assign_tool_kit->tool_kit_id = $value;
             $assign_tool_kit->quantity_to_be_issued = $quantity_to_be_issued[$key];
             $assign_tool_kit->assign_date = $today;
             $assign_tool_kit->remarks = $remarks[$key];

             // dd($assign_tool_kit);
             $assign_tool_kit->save();

             Session::flash('success','Updated successfully assign tool kits to the user');
          }
        }
      $user_id_crypt = Crypt::encrypt($user_id_new);
      return redirect()->route('details-users',['id' => $user_id_crypt]);
    }

    public function deleteAssignTool(Request $request, $user_id)
    {
        $user_id_new = Crypt::decrypt($user_id);

        $user_id_crypt = Crypt::encrypt($user_id_new);
        $assign_date = $request->assign_date_delete;
        $tools = ToolKit::where('status',1)->get();
        $user_assigned_toolkits = AssignToolKit::where([['user_id',$user_id_new],['assign_date',$assign_date],['status',1]])->update(['status' => 0]);
        
        // dd($user_id_crypt);

        Session::flash('success','Deleted successfully assign tool kits to the user');
        return redirect()->route('details-users',['id' => $user_id_crypt]);
    }

    // ########################## Test data ######################################

    // public function getTableData()
    // {
    //     $states = State::all();
    //     $dists = District::with('state')->paginate(5);
    //     return view('table-data',compact('states','dists'));
    // }

    // public function getTableDataDetails(Request $request)
    // {
    //    $state = $request->input('state');

    //     if($state){
    //          $acheads = District::with('state')->where('state_id',$state)->where('status',1)->get();
    //          return response()->json($acheads);
    //     } 
    // }

    // ############################### end of Test data ################################
}
