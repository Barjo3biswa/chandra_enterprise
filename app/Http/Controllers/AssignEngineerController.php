<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session,Crypt,DB, Excel, Redirect;
use App\Models\Assign\AssignEngineer, App\Models\Client, App\User, App\Models\Assign\AssignProductToClient, App\Models\Company, App\Models\Product, App\Models\Group, App\Models\Zone;

class AssignEngineerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clients = Client::where('status',1)->groupBy('name')->get();
        $branches = Client::select("branch_name")->where('status',1)->groupBy('branch_name')->get();
        $engineers = User::where('status',1)->get();
        $companies = Company::where('status',1)->get();
        $zones = Zone::where('status',1)->get();

        $assign_engineers = AssignEngineer::with('user','client')->where('status',1);

        if ($request->client_id) {

            $client_names = Client::select('id')->where('name','like','%'.$request->client_id.'%')->where('status',1)->get()->toArray();
                
            $clients1 = [];
            foreach ($client_names as $key => $client_name) {
                array_push($clients1, $client_name['id']);
            }

            $assign_engineers = $assign_engineers->whereIn('client_id',$clients1);
                
        }

        if ($request->branch) {

            $client_names = Client::select('id')->where('branch_name','like','%'.$request->branch.'%')->where('status',1)->get()->toArray();
                
            $clients2 = [];
            foreach ($client_names as $key => $client_name) {
                array_push($clients2, $client_name['id']);
            }
     
            $assign_engineers = $assign_engineers->whereIn('client_id',$clients2);
                
         }

        if ($request->company_id) {

            $client_companies = Product::where('company_id',$request->company_id)->where('isAssigned',1)->select('id')->get()->toArray();

            $products_assign = [];
            foreach ($client_companies as $key => $client_company) {
                array_push($products_assign, $client_company['id']);
            }

            $check_assign_products = AssignProductToClient::whereIn('product_id',$products_assign)->where('status',1)->select('client_id')->get()->toArray();

            $assign_clients = [];
            foreach ($check_assign_products as $key => $check_assign_product) {
                array_push($assign_clients, $check_assign_product['client_id']);
            }

            // dd($check_assign_product);

           $assign_engineers = $assign_engineers->whereIn('client_id',$assign_clients);
        }

        if ($request->engineer_id) {
           $assign_engineers =  $assign_engineers->where("engineer_id","like",'%'.$request->engineer_id.'%');
        }

        if ($request->zone_id) {
           $assign_engineers =  $assign_engineers->where("zone_id",$request->zone_id);
        }

        // dd($request->zone_id);

        $assign_engineers = $assign_engineers->groupBy('engineer_id')->get();

        // dd($assign_engineers);

        return view('admin.assign.engineer.index',compact('assign_engineers','clients', 'branches','engineers','companies','zones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::where('status',1)->get();
        $engineers = User::where('status',1)->get();
        $companies = Company::where('status',1)->get();
        $zones = Zone::where('status',1)->get();
        return view('admin.assign.engineer.create',compact('clients','engineers','companies','zones'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $post = $request->all();
            $zone_id = $post['zone_id'];
            // $client_id = $post['client_id'];
            $engineer_id =  $post['engineer_id'];
            $clients = $post['client_detail'];

            if ($clients) {
               foreach ($clients as $client) {

                // dd($client);

                $assign_eng = new AssignEngineer();

                $assign_eng->zone_id = $zone_id;
                $assign_eng->client_id = $client;
                $assign_eng->engineer_id = $engineer_id;
                // $assign_eng->product_id = $client;
                $assign_eng->save();


                $p_details = Client::where('id',$assign_eng->client_id)->first();
                // dd($p_details);
                $p_details->isAssignedToEngineer = 1;
                $p_details->save(); 
                } 
            }
            

            }catch(ValidationException $e)
            {
                Session::flash('error','Please fix the error and try again');
                return Redirect::back();
            }
        
            Session::flash('success','Successfully assign engineers for the product');
           
        return redirect()->route('view-all-assign-engineer');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($engineer_id)
    {
        $aseng_id = Crypt::decrypt($engineer_id);

        $assign_eng_name = AssignEngineer::with('user','client','zone')->where('engineer_id',$aseng_id)->groupBy('engineer_id')->first();
 
        $assign_eng = AssignEngineer::with('user','client','client.assigned_products')->where('engineer_id',$aseng_id)->get();
       
        return view('admin.assign.engineer.show',compact('assign_eng','assign_client_details','assign_eng_name'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($engineer_id)
    {
        $clients = Client::where('status',1)->get();
        $engineers = User::where('status',1)->get();
        $companies = Company::where('status',1)->get();
        $zones = Zone::where('status',1)->get();

        $engineer_id = Crypt::decrypt($engineer_id);
        $assign_eng_name = AssignEngineer::where('engineer_id',$engineer_id)->where('status',1)->first();


        $assign_eng = AssignEngineer::with('user','client','client.assigned_products')->where('engineer_id',$engineer_id)->where('status',1)->get();

        return view('admin.assign.engineer.edit',compact('assign_eng_name','assign_eng','clients','engineers','companies','zones'));
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
    public function destroy($engineer_id)
    {
        $engineer = Crypt::decrypt($engineer_id); 


        $assign_eng_name = AssignEngineer::where('engineer_id',$engineer)->where('status',1)->get()->toArray();

        $clients = [];

        foreach ($assign_eng_name as $key => $value) {
           array_push($clients, $value['client_id']);
        }

        $client = Client::whereIn('id',$clients)->where('status',1)->update(['isAssignedToEngineer' => 0]);

        $assign_eng = AssignEngineer::where('engineer_id',$engineer)->where('status',1)->update(['status' => 0]);

        Session::flash('success','Successfully exported assign client details');
        return Redirect::route('view-all-assign-engineer');

    }

    public function getBranchName(Request $request)
    {
       $client_id = $request->input('client_id');
       if($client_id){

        $branchname = Client::where('name',$client_id)->where('status',1)->get();
        return response()->json($branchname);

       }
        
    }

   public function getClientDetail(Request $request)
    {
       $zone_id = $request->input('zone_id');
       if($zone_id){

        // $clients = Client::where('zone_id',$zone_id)->where('isAssignedToEngineer',null)->where('isAssigned',1)->where('status',1)->get();

        $clients = DB::table('clients')
                        // ->leftJoin('assign_product_to_client','assign_product_to_client.client_id','clients.id')
                        ->where('clients.zone_id',$zone_id)
                        ->where('clients.isAssigned',1)
                        ->where('clients.isAssignedToEngineer',0)
                        ->where('clients.status',1)
                        ->select('clients.id as id','clients.name as name','clients.branch_name as branch_name','clients.email as email','clients.ph_no as ph_no','clients.remarks as remarks')
                        ->get();

        // dd($clients);
        return response()->json($clients);

       }
        
    }

    public function getDetailOfProduct(Request $request)
    {
        $client_id = $request->client_id;
      
        if($client_id){

             $acheads = DB::table('assign_product_to_client')
                        ->leftJoin('products','products.id','assign_product_to_client.product_id')
                        ->where('assign_product_to_client.client_id',$client_id)
                        ->where('products.isAssigned',1)
                        ->where('products.assignToEngineer','!=',1)
                        ->where('products.status',1)
                        ->select('products.id as id','products.name as name','products.brand as brand','products.product_code as product_code','products.model_no as model_no','products.serial_no as serial_no','assign_product_to_client.date_of_install as date_of_install')
                        ->get();

            return response()->json($acheads);
   
        }
        
    }



    public function export(Request $request) 
    {

        $assign_engineers = User::with('assigned_engg','assigned_engg.client','assigned_engg.client.assigned_products.product')->has('assigned_engg','>', 0)->get();
       
        try{
            Excel::create('AssignedZoneToEngineerDetails '.date('dmyHis'), function( $excel) use($assign_engineers){
                $excel->sheet('AssignedZoneToEngineer-Details ', function($sheet) use($assign_engineers){
                  $sheet->setTitle('AssignedZoneToEngineer-Details');

                  $sheet->cells('A1:Q1', function($cells) {
                    $cells->setFontWeight('bold');
                  });
                  
                  $arr = [];
                  $counter = 1;
                    foreach($assign_engineers->chunk(500) as $res):
                        foreach( $res as $k => $v) {
                            // dd($v->assigned_engg->first()->client->name);
                            $arr[$counter]['Sl No']                    = $k+1;
                            $arr[$counter]['Engineer Name']              = $v->first_name.' '.$v->middle_name.' '.$v->last_name;

                            $arr[$counter]['Client Name']             = '';
                            $arr[$counter]['Branch Name']             = '';

                           // Assigned Clients
                            foreach ($v->assigned_engg as $key => $value ){

                                // dd($value);
                                if($key > 0){

                                    // blank all other field
                                    $arr[$counter]['Sl No']                   = '';
                                    $arr[$counter]['Engineer Name']           = '';
                                }
                                    $arr[$counter]['Client Name']             = $value->client->name;
                                    $arr[$counter]['Branch Name']             = $value->client->branch_name;
    
                                    // Assigned Products
                                    foreach ($value->client->assigned_products as $kk => $vv) {
                                        // dd($vv);

                                        if($kk > 0){
                                            $arr[$counter]['Sl No']                   = '';
                                            $arr[$counter]['Engineer Name']           = '';
                                            $arr[$counter]['Client Name']             = '';
                                            $arr[$counter]['Branch Name']             = '';
                                        }
                                        $arr[$counter]['Product Name']            = $vv->product->name;
                                        $arr[$counter]['Serial No']            = $vv->product->serial_no;
                                        $arr[$counter]['Product Code']            = $vv->product->product_code;
                                        $arr[$counter]['Group']            = $vv->product->group->name;
                                        $arr[$counter]['Company']            = $vv->product->company->name;
                                        if ($vv->product->date_of_purchase != "0000-00-00") {
                                            $arr[$counter]['Date of Purchase']            = dateFormat($vv->product->date_of_purchase);
                                        }else{
                                            $arr[$counter]['Date of Purchase']            = '';
                                        }

                                        if ($vv->product->manufacture_date != "0000-00-00") {
                                            $arr[$counter]['Manufacture Date']            = dateFormat($vv->product->manufacture_date);
                                        }else{
                                            $arr[$counter]['Manufacture Date']            = '';
                                        }

                                        $arr[$counter]['Warranty(in years)']            = $vv->product->warranty;
                                        $arr[$counter]['Brand']            = $vv->product->brand;
                                        $arr[$counter]['Model No']            = $vv->product->model_no;
                                        $arr[$counter]['Equipment No']            = $vv->product->equipment_no;
                                        
                                      
                                        $counter ++;

                                    }
                                  }
                        
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

        Session::flash('success','Successfully exported assign client details');
        return Redirect::route('view-all-assign-engineer');
    }



    public function clientEdit(Request $request, $engineer_id)
    {
        $engineer_id = Crypt::decrypt($engineer_id);
        
        $client_id = $request->client_id1;
        
        $assign_eng_name = AssignEngineer::with('user','client','zone')->where([['engineer_id',$engineer_id],['client_id',$client_id]])->where('status',1)->first();

        // $assigned_clients = AssignEngineer::with('user','client','zone')->where('engineer_id',$engineer_id)->where('status',1)->get();

        // dd($assign_eng_name);

        $engineers = User::where('status',1)->get();
        $zones = Zone::where('status',1)->get();

        return view('admin.assign.engineer.client-edit',compact('assign_eng_name','engineers','zones'));
    }

    public function clientUpdate(Request $request, $engineer_id)
    {
        $engineer = Crypt::decrypt($engineer_id);

        $client_id = $request->client_id;

        $client = Client::where('id',$client_id)->where('status',1)->update(['isAssignedToEngineer' => 0]);

        $assign_eng_name = AssignEngineer::where('engineer_id',$engineer)->where('client_id',$client_id)->where('status',1)->update(['status' => 0]);

        try{
            $post = $request->all();

            //dd($post);

            $zone_id = $post['zone_id'];
            $engineer_id1 =  $post['engineer_id_to_encrypt'];
            $engineer_id_encrypt = Crypt::encrypt($engineer_id1);

            $clients = $post['client_detail'];

            if ($clients) {
               foreach ($clients as $client) {
                $assign_eng = new AssignEngineer();

                $assign_eng->zone_id = $zone_id;
                $assign_eng->client_id = $client;
                $assign_eng->engineer_id = $engineer_id1;
                $assign_eng->save();


                $p_details = Client::where('id',$assign_eng->client_id)->first();
                $p_details->isAssignedToEngineer = 1;
                $p_details->save(); 
                } 
            }
            

            }catch(ValidationException $e)
            {
                Session::flash('error','Please fix the error and try again');
                return Redirect::back();
            }

        Session::flash('success','Successfully updated assign client details');

        return redirect()->route('edit-assign-new-client-to-engineer',['engineer_id' => $engineer_id_encrypt]);

          
    }

    public function clientDelete(Request $request, $engineer_id)
    {
        $engineer = Crypt::decrypt($engineer_id);

        $engineer_id_encrypt = Crypt::encrypt($request->engineer_id1);
      
        $client_id = $request->client_id;
        $client = Client::where('id',$client_id)->where('status',1)->update(['isAssignedToEngineer' => 0]);
        $assign_eng_name_count = AssignEngineer::where('engineer_id',$engineer)->where('status',1)->count();
        

        $assign_eng_name = AssignEngineer::where('engineer_id',$engineer)->where('client_id',$client_id)->where('status',1)->update(['status' => 0]);

        if ($assign_eng_name_count == 1) {
            Session::flash('success','Successfully deleted assign client details');
            $assign_eng = AssignEngineer::where('engineer_id',$engineer)->where('status',1)->update(['status' => 0]);

            return redirect()->route('view-all-assign-engineer');
        }

        
        Session::flash('success','Successfully deleted assign client details');

        return redirect()->route('edit-assign-new-client-to-engineer',['engineer_id' => $engineer_id_encrypt]);
    }

    public function getRolewiseUser(Request $request)
    {
        $user_role = $request->input('user_role');
        if($user_role){

        $users = User::where('user_type',$user_role)->where('status',1)->get();
        return response()->json($users);

       }
    }


}
