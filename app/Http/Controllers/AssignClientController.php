<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assign\AssignProductToClient, App\Models\Client, App\Models\Company, App\Models\Product, App\Models\Group, App\Models\SubGroup;

use Session, Crypt, Excel, Redirect;

class AssignClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clients = Client::where('status',1)->groupBy('name')->get();
        $companies = Company::where('status',1)->get();
        $groups = Group::where('status',1)->get();
        $assign_client_branch = AssignProductToClient::with('product','company','client')->where('status',1)->select('client_id')->get()->toArray();
// dd($assign_client_branch);
        $assign_clients = AssignProductToClient::with('product','company','client')->where('status',1);

        if ($request->client_id) {

            $client_names = Client::select('id')->where('name','like','%'.$request->client_id.'%')->where('status',1)->get()->toArray();
                
            $clients1 = [];
            foreach ($client_names as $key => $client_name) {
                array_push($clients1, $client_name['id']);
            }

            //dd($clients);
            //foreach ($client_name as $key => $value) {
               
                $assign_clients = $assign_clients->whereIn('client_id',$clients1);
                
            //}
     
        }

        if ($request->branch) {

            $client_names = Client::select('id')->where('branch_name','like','%'.$request->branch.'%')->where('status',1)->get()->toArray();
                
            $clients2 = [];
            foreach ($client_names as $key => $client_name) {
                array_push($clients2, $client_name['id']);
            }

            //dd($clients);
            //foreach ($client_name as $key => $value) {
               
                $assign_clients = $assign_clients->whereIn('client_id',$clients2);
                
            //}
     
        }

        if ($request->company_id) {
           $assign_clients=  $assign_clients->where("company_id","like",'%'.$request->company_id.'%');
        }


        if ($request->group_id) {
            $client_groups = Product::where('group_id',$request->group_id)->select('id')->get()->toArray();

            $products_assign = [];
            foreach ($client_groups as $key => $client_group) {
                array_push($products_assign, $client_group['id']);
            }

            //dd($clients);
            //foreach ($client_group as $key => $value) {
               
                $assign_clients = $assign_clients->whereIn('product_id',$products_assign);
                
            //}

        }

        // dd($client_groups);
        $assign_clients->when($request->get("product_id"), function($query) use ($request){
            return $query->whereHas("product", function($sub_query) use ($request){
                return $sub_query->where("id", $request->get("product_id"));
            });
        });
        $assign_clients = $assign_clients->groupBy('client_id')->get();

        //dd($assign_clients);
        $client_branches = Client::select("branch_name")->distinct("branch_name")->orderBy("branch_name")->pluck("branch_name", "branch_name")->toArray();

        return view('admin.assign.client.index',compact('assign_clients','clients','companies','groups', "client_branches"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::where('status',1)->groupBy('name')->get();
        $companies = Company::where('status',1)->get();
        $groups = Group::where('status',1)->get();
        $sgroups = SubGroup::where('status',1)->get();
        return view('admin.assign.client.create',compact('clients','companies','groups','sgroups'));
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

        $c_name = $request->client_id;
        $branch = $request->branch;

        $c_id = Client::where('branch_name',$branch)->where('name',$c_name)->first()->id;

        // dd($c_id);

        $client_id = $c_id;
        $company_id =  $post['company_id'];
        $product_id = $post['product_detail'];
        $date_of_install = $post['date_of_install'];
 
     
        foreach ($product_id as $key => $products) {

            $product = new AssignProductToClient();

            $product->client_id = $client_id;
            $product->company_id = $company_id;
            $product->product_id = $products;


            if($request->date_of_install[$key] != ''){
                $req_date = $request->date_of_install[$key];
               // $tdr = str_replace("/", "-", $req_date);
                $new_mnf_dt = date('Y-m-d',strtotime($req_date));
            }
            else
            $new_mnf_dt = " ";

            $product->date_of_install =  $new_mnf_dt;

            // dd($product);

            $product->save();

            $pduct = Product::where('id',$product->product_id)->first();
            $pduct->isAssigned = 1;
            $pduct->save();

   

            $client = Client::where('id',$product->client_id)->first();
            // dd($client);
            $client->isAssigned = 1;
            $client->save();
           

            Session::flash('success','Successfully assign products to the client');
          } 
      

        return redirect()->route('view-all-assign-client');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($client_id)
    {
        $assign_p = Crypt::decrypt($client_id);

        $client_name = Client::where('id',$assign_p)->where('status',1)->first();

       
        $assign_product = AssignProductToClient::with('product','company','client')
        ->where('client_id',$assign_p)
        ->whereHas("product", function($query){
            return $query->where("status", 1);
        })
        ->get();
  
        return view('admin.assign.client.show',compact('assign_product','assign_product_detail','client_name'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($client_id)
    {
        $assign_p = Crypt::decrypt($client_id);

        // dd($assign_p);
        $assign_product = AssignProductToClient::with('product','company','client')->where('client_id',$assign_p)->where('status',1)->get();

        $assign_client_name = Client::where('id',$assign_p)->where('status',1)->first();

        // dd($assign_client_name);

        $clients = Client::where('status',1)->get();
        $companies = Company::where('status',1)->get();
        $groups = Group::where('status',1)->get();
        $sgroups = SubGroup::where('status',1)->get();

        return view('admin.assign.client.edit',compact('assign_product','clients','companies','groups','sgroups','assign_client_name'));
        // dd($assign_product);
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
    public function destroy($client_id)
    {
        $assign_p = Crypt::decrypt($client_id);

        $assign_product = AssignProductToClient::with('product','company','client')->where('client_id',$assign_p)->where('status',1)->get()->toArray();

        $products = [];

        foreach ($assign_product as $key => $value) {
           array_push($products, $value['product_id']);
        }

        $product = Product::whereIn('id',$products)->update(['isAssigned' => 0]);
        $client = Client::where('id',$assign_p)->update(['isAssigned' => 0]);
       
        $assign = AssignProductToClient::with('product','company','client')->where('client_id',$assign_p)->where('status',1)->update(['status' => 0]);

        Session::flash('success','Successfully deleted assign products to the client detais');
        return redirect()->route('view-all-assign-client');

    }

    public function getSubGroup(Request $request)
    {
        $group_id = $request->input('group_id');

        if($group_id){
             $sgroups = SubGroup::where('group_id',$group_id)->where('status',1)->get();
             return response()->json($sgroups);
        }
    }

    public function getGroupDetail(Request $request)
    {
        $company_id = $request->input('company_id');
        $groups = [];
        if($company_id){
            //  $grp_id = Product::where('company_id',$company_id)->get();

            //  foreach ($grp_id as $key => $value) {
            //      $groups[] = Group::where('id',$value->group_id)->where('status',1)->first();
            //      // return response()->json($groups);
            //  }
            $groups = Group::whereHas("products", function($query) use ($company_id){
                return $query->where("company_id", $company_id);
            })->get();
         return response()->json($groups); 
        }
    }

    public function getDetailOfProduct(Request $request)
    {
        $company_id = $request->input('company_id');
        $group_id = $request->input('group_id');
        // $sub_group_id = $request->input('sub_group_id');

        if($group_id){
             $acheads = Product::where('company_id',$company_id)->where('group_id',$group_id)->where('isAssigned','!=',1)->where('status',1)->get();
             return response()->json($acheads);
        }
        else
        {
            $else_data = "No data found";
            return response()->json($else_data);
        }
    }


    public function export(Request $request) 
    {
        $client_have_products = Client::with("assigned_products", "assigned_products.product")->has("assigned_products", ">", 0)->get();
        /*dd($client_have_products);
        $assign_clients = AssignProductToClient::with('product','company','client')->where('status',1)->groupBy('client_id')->get();

        foreach ($assign_clients as $key => $value) {
            $assign_products[] = Product::where('id',$value->product_id)->where('status',1)->get();
        }

        dd($assign_products);*/
        try{
            Excel::create('AssignedProductDetails '.date('dmyHis'), function( $excel) use($client_have_products){
                $excel->sheet('AssignedProductDetails ', function($sheet) use($client_have_products){
                  $sheet->setTitle('AssignedProductDetails');

                  $sheet->cells('A1:L1', function($cells) {
                    $cells->setFontWeight('bold');
                  });
                  
                  $arr = [];
                  $counter = 1;
                    foreach($client_have_products->chunk(500) as $res):
                        foreach( $res as $k => $v) {
                            //dd($v);
                            $arr[$counter]['Sl No']                    = $k+1;
                            $arr[$counter]['Client Name']              = $v->name;
                            $arr[$counter]['Branch Name']              = $v->branch_name;
                            $arr[$counter]['Company Name']             = $v->assigned_products->first()->company->name;
                             // Assigned Products
                            foreach ($v->assigned_products as $key => $value ){
                               
                                if($key > 0){

                                    // blank all other field
                                    $arr[$counter]['Sl No']                   = '';
                                    $arr[$counter]['Client Name']             = '';
                                    $arr[$counter]['Branch Name']             = '';
                                    $arr[$counter]['Company Name']            = '';
                                }
                                    $arr[$counter]['Product Name']            = $value->product->name;
                                    $arr[$counter]['Product Code']            = $value->product->product_code;
                                    $arr[$counter]['Product Brand']            = $value->product->brand;
                                    $arr[$counter]['Product Serial No']            = $value->product->serial_no;
                                    $arr[$counter]['Product Company']            = $value->product->company->name;
                                    $arr[$counter]['Product Group']            = $value->product->group->name;
                                    $arr[$counter]['Product Date Of Install']            = dateFormat($value->date_of_install);
                                    $counter ++;
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

        Session::flash('success','Successfully exported company details');
        return Redirect::route('view-all-assign-client');
    }


    public function productEdit(Request $request, $product_id)
    {
        $assign_p = Crypt::decrypt($product_id);
        // dd($assign_p);

        $assgn_client_id = $request->assgn_client_id1;

        $clients = Client::where('status',1)->get();
        $companies = Company::where('status',1)->get();
        $groups = Group::where('status',1)->get();
        $sgroups = SubGroup::where('status',1)->get();

        $assign_client_name = Client::where('id',$assgn_client_id)->where('status',1)->first();

       $assign_product = AssignProductToClient::with('product','company','client')->where('product_id',$assign_p)->first();

        // dd($assign_client_name);
        

        return view('admin.assign.client.product-edit',compact('clients','companies','groups','sgroups','assign_client_name','assign_product'));

       
    }


    public function productUpdate(Request $request, $product_id)
    {
        $assign_p = Crypt::decrypt($product_id);

        $assign_client_name = Crypt::encrypt($request->assgn_client_id);

        $assign_product = AssignProductToClient::with('product','company','client')->where('product_id',$assign_p)->first();
        $assign_product->status = 0;
        $assign_product->save();

        $product = Product::where('id',$assign_p)->where('status',1)->first();
        $product->isAssigned = 0;
        $product->save();
       

        $post = $request->all();

        $c_id = $request->assgn_client_id;

        // dd($c_id);

        $client_id = $c_id;
        $company_id =  $post['company_id'];
        $product_id = $post['product_detail'];
        $date_of_install = $post['date_of_install'];
 
     
        foreach ($product_id as $key => $products) {

            $product = new AssignProductToClient();

            $product->client_id = $client_id;
            $product->company_id = $company_id;
            $product->product_id = $products;


            if($request->date_of_install[$key] != ''){
                $req_date = $request->date_of_install[$key];
               // $tdr = str_replace("/", "-", $req_date);
                $new_mnf_dt = date('Y-m-d',strtotime($req_date));
            }
            else
            $new_mnf_dt = " ";

            $product->date_of_install =  $new_mnf_dt;

            // dd($product);

            $product->save();

            $pduct = Product::where('id',$product->product_id)->first();
            $pduct->isAssigned = 1;
            $pduct->save();

   

            $client = Client::where('id',$product->client_id)->first();
            // dd($client);
            $client->isAssigned = 1;
            $client->save();
           

            Session::flash('success','Successfully assign products to the client');
          } 
      

        return Redirect::route('edit-assign-new-product-to-client',['client_id' => $assign_client_name]);


    }


    public function productDestroy(Request $request, $product_id)
    {
        $clients = Client::where('status',1)->groupBy('name')->get();
        $assign_p = Crypt::decrypt($product_id);

        // $client_name = Client::where('id',$assign_p)->where('status',1)->first();

        // dd($client_name);

        $assign_client_name = Crypt::encrypt($request->assgn_client_id);

        $assign_client = $request->assgn_client_id;

        // dd($assign_client);

        $assign_client_count = AssignProductToClient::with('product','company','client')->where('client_id',$assign_client)->where('status',1)->count();

        // dd($assign_client_count);

        $assign_product = AssignProductToClient::with('product','company','client')->where('product_id',$assign_p)->first();

        $assign_product->status = 0;
        $assign_product->save();

        $product = Product::where('id',$assign_p)->where('status',1)->first();
        $product->isAssigned = 0;
        $product->save();

        if ($assign_client_count == 1) {
            Session::flash('success','Successfully deleted product detail from assigned client list');

            // $assign_eng = AssignEngineer::where('engineer_id',$engineer)->where('status',1)->update(['status' => 0]);
            $client = Client::where('id',$assign_client)->update(['isAssigned' => 0]);

            return redirect()->route('view-all-assign-client');
        }

        Session::flash('success','Successfully deleted product detail from assigned client list');
        // return Redirect::route('view-all-assign-client');

        return Redirect::route('edit-assign-new-product-to-client',['client_id' => $assign_client_name]);
    }


    public function transferProduct($id)
    {
        $assign_p_id = Crypt::decrypt($id);

        $assign_p = AssignProductToClient::with('product','client')->where('id',$assign_p_id)->first();
        $clients = Client::where('status',1)->groupBy('name')->get(); 
        return view('admin.assign.client.transfer',compact('assign_p','clients'));
    }

    public function transferProductPost(Request $request, $id)
    {
            $assign_p_id = Crypt::decrypt($id);

            $old_client_id = $request->old_client_id;
            $old_product_id = $request->old_product_id;

            $assign_p_count = AssignProductToClient::where('client_id',$old_client_id)->count();
            
            $c_name = $request->client_id;
            $branch = $request->branch;

            if ($assign_p_count == 1) {
                $client = Client::where('id',$old_client_id)->update(['isAssigned' => 0]);
            }

            $c_id = Client::where('branch_name',$branch)->where('name',$c_name)->first()->id;

            $assign_p = AssignProductToClient::with('product')->where('id',$assign_p_id)->first();
            $assign_p->client_id = $c_id;
            $assign_p->save();

            $assign_client_name = Client::where('id',$assign_p->client_id)->where('status',1)->first();

        // dd($assign_client_name);

            Session::flash('success','Successfully tranfered product to your selected client');
           // return redirect()->back();

            return Redirect::route('view-all-assign-client');
            
    }



}
