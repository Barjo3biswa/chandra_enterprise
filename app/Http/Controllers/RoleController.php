<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session,DB,Crypt,Validator,Excel,Auth;
use App\User, App\Models\Menu, App\Models\SubMenu;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
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
        $user_id = Auth::user()->id;
        // $roles = Role::with('submenu','user')->where('status',1)->orderBy('id','desc')->get();

        $roles = DB::table('model_has_permissions')
            ->leftjoin('permissions','permissions.id','model_has_permissions.permission_id')
            ->leftjoin('users','users.id','model_has_permissions.model_id')
            ->where('users.status',1)

           ->select('users.first_name as first_name','users.middle_name as middle_name','users.last_name as last_name','model_has_permissions.model_id as model_id')

           ->groupBy('users.first_name','users.middle_name','users.last_name','model_has_permissions.model_id')

           ->get();
  
        return view('admin.role.index',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $permissions = Permission::get();
        $users = User::where('status',1)->get();
        $permissions = Permission::select('heading')->groupBy('heading')->orderby('id','asc')->get();


        foreach ($permissions as $key => $value) {
            $permission_roles = Permission::where('heading', $value->heading)->get();

            // dd($permission_roles);die();

            $permissions[$key]['permission_roles'] =$permission_roles;
        }
 
        return view('admin.role.create',compact('users','permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = $request->all();
        $user_id = $post['user_id'];
        $user = User::find($user_id);
     
        try {
                if ($user != null) {
                    if ($request->select_all) {
                        $permissions = $post['permission'];
                        // dd($permissions.'success');
                        $user->revokePermissionTo($user->permissions);
                        foreach($permissions as $permission){
                            $user->givePermissionTo($permission);
                        }
                        Session::flash('success','Successfully added permissions'); 
                    }else{
                        $permissions = $post['permission'];
                        //dd($permissions.'error');
                        $user->revokePermissionTo($user->permissions);
                        foreach($permissions as $permission){
                            $user->givePermissionTo($permission);
                        }
                        Session::flash('success','Successfully added permissions');
                    }
                }
                else{
                   Session::flash('error','User already exist'); 
                }
               } catch (\Exception $e) {
                // dd($e);
                $user->revokePermissionTo($user->permissions);
                Session::flash('success','Removes all permissions for this user');
            }
        return redirect()->route('view-all-roles');
     
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_id     = Crypt::decrypt($id);
        $users = User::where('id',$user_id)->where('status',1)->first();
        
        $user_permission_detail = DB::table('model_has_permissions')
            ->leftjoin('permissions','permissions.id','model_has_permissions.permission_id')
            ->leftjoin('users','users.id','model_has_permissions.model_id')
            ->where('users.status',1)
            ->where('users.id',$user_id)

            ->select('users.first_name as first_name','users.middle_name as middle_name','users.last_name as last_name','model_has_permissions.model_id as model_id','permissions.id as id','permissions.name as name','users.title as title')
            ->get();

            // dd($user_permission_detail);die();


        return view('admin.role.show',compact('user_permission_detail','permissions','users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   
    public function edit($id)
    {
        $user_id     = Crypt::decrypt($id);

        // $users = User::where('id',$user_id)->where('status',1)->first();
        $users = User::where('status',1)->get();

        $selected_user = DB::table('model_has_permissions')
            ->leftjoin('users','users.id','model_has_permissions.model_id')
            ->where('users.status',1)
            ->where('model_has_permissions.model_id',$user_id)
            ->select('users.first_name as first_name','users.middle_name as middle_name','users.last_name as last_name','users.title as title','model_has_permissions.model_id as model_id')

            ->first();

        // dd($selected_user->model_id);die();
        
        $user_permission_detail = DB::table('model_has_permissions')
            ->leftjoin('permissions','permissions.id','model_has_permissions.permission_id')
            ->leftjoin('users','users.id','model_has_permissions.model_id')
            ->where('users.status',1)
            ->where('users.id',$user_id)

            ->select('users.first_name as first_name','users.middle_name as middle_name','users.last_name as last_name','model_has_permissions.model_id as model_id','permissions.id as id','permissions.name as name','users.title as title')
            ->get();

            // dd($user_permission_detail);die();


        $permissions = Permission::select('heading')->groupBy('heading')->orderby('id','asc')->get();


        foreach ($permissions as $key => $value) {
            $permission_roles = Permission::where('heading', $value->heading)->get();
  
            $permissions[$key]['permission_roles'] = $permission_roles;
        }

        return view('admin.role.edit',compact('user_permission_detail','permissions','users','selected_user'));
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
        $user_id = Crypt::decrypt($id);
        $post = $request->all();
        // $user_id = $post['user_id'];
        $user = User::find($user_id);

        // $user->givePermissionTo('edit articles');
        try {
            if ($request->select_all) {

                $permissions = $post['permission'];
                // $revoke_permission = $post['prmssn'];

                 // dd($permissions.'success');die();
                $user->revokePermissionTo($user->permissions);
                foreach($permissions as $permission){
                    $user->givePermissionTo($permission);
                }
                Session::flash('success','Successfully updated permissions');
            }else{

                $permissions = $post['permission'];
                // $revoke_permission = $request->prmssn;

                // dd($permissions.'error');
                $user->revokePermissionTo($user->permissions);
                foreach($permissions as $permission){
                    $user->givePermissionTo($permission);
                }
                Session::flash('success','Successfully updated permissions');
                }
            } catch (\Exception $e) {
                // dd($e);
                $user->revokePermissionTo($user->permissions);
                Session::flash('success','Removed all permissions for this user');
            }
        return redirect()->route('view-all-roles');
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
        $permissions = DB::table('model_has_permissions')
            ->leftjoin('permissions','permissions.id','model_has_permissions.permission_id')
            ->leftjoin('users','users.id','model_has_permissions.model_id')
            ->where('users.status',1)
           
            ->select('users.first_name as first_name','users.middle_name as middle_name','users.last_name as last_name','model_has_permissions.model_id as model_id','permissions.id as id','permissions.name as name','users.title as title')
            ->get();


        try{
            Excel::create('PermissionDetails '.date('dmyHis'), function( $excel) use($permissions){
                $excel->sheet('Permission-Details ', function($sheet) use($permissions){
                  $sheet->setTitle('Permission-Details');

                  $sheet->cells('A1:C1', function($cells) {
                    $cells->setFontWeight('bold');
                  });
                  
                  $arr = [];
                    foreach($permissions->chunk(100) as $res):
                        foreach( $res as $k => $v) {
                            //dd($v);
                            $arr[$k]['Sl No']                   = $k+1;
                            $arr[$k]['User Name']                    = $v->first_name.' '.$v->middle_name.' '.$v->last_name;

                            $arr[$k]['Permission']                 = $v->name;
                          
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

        Session::flash('success','Successfully exported permission details');
        return Redirect::route('view-all-roles');
    }
}
