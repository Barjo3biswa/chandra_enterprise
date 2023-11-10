<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session,DB,Crypt,Validator,Redirect,Excel;
use App\Models\Product, App\User, App\Models\Brand, App\Models\Group, App\Models\SubGroup, App\Models\Company;

class ProductController extends Controller
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
        // $products = Product::with(['assigned_product_to_client' => function($select_query){
        //     return $select_query->select(["id", "product_id"]);
        // }, 'brand','group','company', "assigned_branch" => function($select_query){
        //     return $select_query->select(["clients.id", "clients.name", "clients.branch_name"]);
        // }]);
        $p_brands = Product::where('status',1)->where('brand','!=',null)->get();
        $p_serial_no = Product::where('status',1)->where('serial_no','!=',null)->get();
        $p_group = Product::with('group')->where('status',1)->where('group_id','!=',null)->get();
        $p_company = Product::with('company')->where('status',1)->where('company_id','!=',null)->get();
        $p_model_no = Product::where('status',1)->where('model_no','!=',null)->get();
        $products = Product::with(['assigned_product_to_client', 'brand','group','company', "assigned_branch"]);

        if ($request->product_id) {
            $products=  $products->where("name","like",'%'.$request->product_id.'%');
        }
        if ($request->model_no) {
            $products=  $products->where("model_no","like",'%'.$request->model_no.'%');
        }
        if ($request->serial_no) {
            $products=  $products->where("serial_no","like",'%'.$request->serial_no.'%');
        }
        if ($request->product_code) {
            $products=  $products->where("product_code","like",'%'.$request->product_code.'%');
        }
        if ($request->group_id) {
            $grp_id = Group::where('name',$request->group_id)->where('status',1)->get();
            foreach ($grp_id as $key => $value) {
              $products=  $products->where("group_id","like",'%'.$value->id.'%');
            }
        }
        if ($request->company_id) {
            $products=  $products->where("company_id","like",'%'.$request->company_id.'%');
        }
        if ($request->brand) {
            $products=  $products->where("brand","like",'%'.$request->brand.'%');
        }
        if ($request->date_of_purchase) {
            $req_date1 = $request->input('date_of_purchase');
            $tdr1 = str_replace("/", "-", $req_date1);
            $new_exp_dt = date('Y-m-d',strtotime($tdr1));
            $products=  $products->where("date_of_purchase","like",'%'.$new_exp_dt.'%');
        }
        if ($request->manufacture_date) {
            $req_date = $request->input('manufacture_date');
            $tdr = str_replace("/", "-", $req_date);
            $new_mnf_dt = date('Y-m-d',strtotime($tdr));
            $products=  $products->where("manufacture_date","like",'%'.$new_mnf_dt.'%');
        }

        if($request->product_status){
            $products    = $products->where('status', $request->product_status);
        }
       
        if($request->product_assigned_status=="Yes"){
            // dd("ok");
            $products    = $products->whereHas('newAssigtnedBranch');
        }else if($request->product_assigned_status=="No"){
            $products    = $products->whereDoesntHave('newAssigtnedBranch');
        }
        // dd()

        $products->orderBy("name", "ASC");
        if(!$request->product_status){
            if($request->get("deactivated_product") == 1){
                $products    = $products->where('status', 2);
            }else{
                $products = $products->where('status', 1);
            }
        }
        $p_count = $products->count();
        $products = $products->orderBy('id','desc')->paginate(200);

        return view('admin.product.index',compact('products','p_brands','p_serial_no','p_group','p_company','p_model_no','p_count'));
    }


    public function create()
    {
        $brands = Brand::where('status',1)->get();
        $groups = Group::where('status',1)->get();
        $companies = Company::where('status',1)->get();
        return view('admin.product.create',compact('brands','groups','companies'));
    }


    public function store(Request $request)
    {
       try
      {
         $rules = [
            'name'                                  => 'required',
        ];
         $messages = [
            'name.required'                         => 'Product name is required',
        ];       
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                Session::flash('error', 'Please fix the error and try again!');
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $data = $request->all();
            $code = DB::table('products')->max('unique_no');
            if($code==0){
                $code=1000;
            }else{
                $code= $code+1; 
            }
            $data['unique_no'] = $code;
            if($request->input('manufacture_date') != ''){
                $req_date = $request->input('manufacture_date');
                $tdr = str_replace("/", "-", $req_date);
                $new_mnf_dt = date('Y-m-d',strtotime($tdr));
            }
            else
            $new_mnf_dt = " ";
            $data['manufacture_date'] =  $new_mnf_dt;
            if($request->input('date_of_purchase') != ''){
                $req_date1 = $request->input('date_of_purchase');
                $tdr1 = str_replace("/", "-", $req_date1);
                $new_exp_dt = date('Y-m-d',strtotime($tdr1));
            }
            else{
                $new_exp_dt = "";  
            }
              
            $data['date_of_purchase'] =  $new_exp_dt;
            $data['product_code'] = strtoupper($request->product_code);
        
            
            Product::create($data);
      }catch(ValidationException $e)
            {
                return Redirect::back();
            }
        Session::flash('success','Successfully added product deatils');
        return redirect()->route('view-all-product');
    }


    public function show($id)
    {
        try {
            $p_id = Crypt::decrypt($id);
            $product = Product::with('brand','group','subgroup','company')->where('id',$p_id)->whereIn('status', [1,2])->first();
            return view('admin.product.show',compact('product'));    
        } catch (\Throwable $th) {
            Session::flash('error','Something went wrong, try again later or contact administrator.');
            return redirect()->route('view-all-product');
        }
    }


    public function edit($id)
    {
        $p_id = Crypt::decrypt($id);
        $product = Product::where('id',$p_id)->whereIn('status', [1,2])->first();
        $brands = Brand::where('status',1)->get();
        $groups = Group::where('status',1)->get();
        $sgroups = SubGroup::where('status',1)->get();
        $companies = Company::where('status',1)->get();
        return view('admin.product.edit',compact('product','brands','groups','companies','sgroups'));
    }




    public function update(Request $request, $id)
    {
       try
          {
             $p_id = Crypt::decrypt($id);
             $product = Product::where('id',$p_id)->where('status',1)->first();
             $rules = [
                'name'                                  => 'required',
             ];
             $messages = [
                'name.required'                         => 'Product name is required',
             ];       
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                Session::flash('error', 'Please fix the error and try again!');
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $product->name                      = $request->name;
            $product->product_code              = $request->product_code;
            if($request->date_of_purchase != ''){
                $req_date1 = $request->date_of_purchase;
                $tdr1 = str_replace("/", "-", $req_date1);
                $new_exp_dt = date('Y-m-d',strtotime($tdr1));
            }
            else{
                $new_exp_dt = "";  
            }           
            $product->date_of_purchase =  $new_exp_dt;
            $product->serial_no = $request->serial_no;
            if($request->manufacture_date != ''){
                $req_date = $request->manufacture_date;
                $tdr = str_replace("/", "-", $req_date);
                $new_mnf_dt = date('Y-m-d',strtotime($tdr));
            }
            else
            $new_mnf_dt = " ";
            $product->manufacture_date =  $new_mnf_dt;
            $product->warranty                  = $request->warranty;
            $product->brand                  = $request->brand;
            $product->model_no                  = $request->model_no;
            $product->unique_no                 = $request->unique_no;
            $product->equipment_no              = $request->equipment_no;
            $product->group_id                  = $request->group_id;
            $product->subgroup_id               = $request->subgroup_id;
            $product->company_id                = $request->company_id;
            $product->product_code              = strtoupper($request->product_code);
            $product->save();

        }catch(ValidationException $e){
            return Redirect::back();
        }

        Session::flash('success','Successfully updated product deatils');
        return redirect()->route('view-all-product');
    }


    public function export(Request $request) 
    {
        $products = Product::with('brand','group','subgroup','company', 'assigned_branch')->where('status',1)->orderBy('id','desc')->get();


        try{
            Excel::create('ProductDetails '.date('dmyHis'), function( $excel) use($products){
                $excel->sheet('Product-Details ', function($sheet) use($products){
                  $sheet->setTitle('Product-Details');

                  $sheet->cells('A1:N1', function($cells) {
                    $cells->setFontWeight('bold');
                  });
                  
                  $arr = [];
                    foreach($products->chunk(500) as $res):
                        foreach( $res as $k => $v) {
                            //dd($v);
                           $arr[$k]['Sl No']                           = $k+1;
                            $arr[$k]['Product Name']                    = $v->name;
                            $arr[$k]['Product Code']                    = $v->product_code;
                            
                            if($v->date_of_purchase != "0000-00-00")
                            {
                                $arr[$k]['Product Date Of Purchase']        = $v->date_of_purchase;
                            }else{
                                $arr[$k]['Product Date Of Purchase']        = '';
                            }

                            $arr[$k]['Product Serial No']               = $v->serial_no;

                            if($v->manufacture_date != "0000-00-00")
                            {
                                $arr[$k]['Product Menufacture Date']        = $v->manufacture_date;
                            }else{
                                $arr[$k]['Product Menufacture Date']        = '';
                            }
                            $arr[$k]['Product Warranty (in years)']     = $v->warranty;
                            $arr[$k]['Product Brand Name']              = $v->brand;
                            $arr[$k]['Product Model No']                = $v->model_no;
                            $arr[$k]['Product Equipment No']            = $v->equipment_no;
                            if($v->group_id != null)
                            {
                              $arr[$k]['Product Group']                   = $v->group->name;
                            }else{
                              $arr[$k]['Product Group']                   = '';
                            }
                            
                            if($v->subgroup)
                            {
                              $arr[$k]['Product Sub Group']               = $v->subgroup->name;
                            }else{
                              $arr[$k]['Product Sub Group']               = '';
                            }
                            if($v->company_id != null)
                            {
                              $arr[$k]['Product Company']                 = $v->company->name;
                            }else{
                              $arr[$k]['Product Company']                 = '';
                            }
                            
                            $clients = $v->assigned_branch->map(function($item){
                                return $item->name;
                            })->toArray();
                            $branches = $v->assigned_branch->map(function($item){
                                return $item->branch_name;
                            })->toArray();
                            $arr[$k]['Assigned Client']                 = ($clients ? implode(",", $clients) : "");
                            $arr[$k]['Assigned Branch']                 = ($branches ? implode(",", $branches) : "");
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

        Session::flash('success','Successfully exported product details');
        return Redirect::route('view-all-product');
    }


    public function getSubGroupList(Request $request) 
    {
        $group_id = $request->input('group_id');

        if($group_id){
             $acheads = SubGroup::where('group_id',$group_id)->where('status',1)->get();
             return response()->json($acheads);
        }
    }



    public function destroy($id)
    {
        try {
            $p_id = Crypt::decrypt($id);
            $product = Product::where('id',$p_id)->where('status', 2)->first();
            $product->status        = 0;
            $product->save();
            Session::flash('success','Successfully Deleted product details');
            return redirect()->route('view-all-product');
    
        } catch (\Throwable $th) {
            Session::flash('error','Something went wrong, try again later or contact administrator.');
            return redirect()->route('view-all-product');
        }
    }



    public function deactivateProduct($id)
    {
        $product_id = Crypt::decrypt($id);
        $product = Product::where('id', $product_id)->where('status', 1)->first();
        $product->status                 = 2;
        $product->save();

        Session::flash('success', 'Successfully deactivated product details');
        return Redirect::route('view-all-product');
    }



    public function activateProduct($id)
    {
        $product_id = Crypt::decrypt($id);
        $product = Product::where('id', $product_id)->where('status', 2)->first();
        $product->status                 = 1;
        $product->save();

        Session::flash('success', 'Successfully Activated Product Details.');
        return Redirect::route('view-all-product');
    }



    public function ajaxProductList(Request $request)
    {
        $products = Product::query();
        $products->when($request->get("product_name"), function($query) use ($request){
            return $query->orWhere(function($where_query) use ($request){
                return $where_query->orWhere("name", "LIKE", "%{$request->get("product_name")}%")
                    ->orWhere("serial_no", "LIKE", "%{$request->get("product_name")}%");
            });
        });
        $products->where("status", 1);
        $products_list = $products->select("id", "name", "serial_no")
            ->orderBy("name", "ASC")
            ->limit(30)
            ->get();
        return response()->json($products_list);
    }
}
