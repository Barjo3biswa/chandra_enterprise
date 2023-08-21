<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth, Validator, Session, Crypt,Excel;
use App\Models\SparePart, App\Models\SparePartTransaction, App\Models\Company, App\Models\Group,App\Models\SubGroup, App\Models\Product;

class SparePartsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

      $purchase_quantity = 0;
      $issued_quantity = 0;

        $s_parts = SparePart::with('group','company')->where('status',1)->get();

        foreach ($s_parts as $key => $value) {

           $purchase_quantity = SparePartTransaction::where('spare_parts_id',$value->id)->where('status',1)->sum('purchase_quantity');

        


           $issued_quantity = SparePartTransaction::where('spare_parts_id',$value->id)->where('status',1)->sum('issued_quantity');

           $stock_in_hand[] = $purchase_quantity - $issued_quantity;
           
          
        }

        // dd($stock_in_hand);
        return view('admin.spare-parts.index',compact('s_parts','stock_in_hand'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::where('status',1)->get();
        $groups = Group::where('status',1)->get();
        $product_brands = Product::where('status',1)->where('brand','!=',null)->groupBy('brand')->get();
        
        return view('admin.spare-parts.create',compact('companies','groups','product_brands'));
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
            'name.required'  =>'Spare part name is required',
        ];
         
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                Session::flash('error', 'Please fix the error and try again!');
                return redirect()->back()->withErrors($validator)->withInput();
            }
            // date_default_timezone_set('Asia/Kolkata');

            $data = $request->all();

           

            if($request->input('with_effect_from') != ''){
                $req_date = $request->input('with_effect_from');
                $tdr = str_replace("/", "-", $req_date);
                $new_mnf_dt = date('Y-m-d',strtotime($tdr));
            }
            else
            $new_mnf_dt = " ";

            $data['with_effect_from'] =  $new_mnf_dt;

            // $opening_balance = $request->opening_balance;

            // $data['stock_in_hand'] = $opening_balance;
            $data['last_transaction_by'] = Auth::user()->id;

            $today = date("Y-m-d");

            $data['transaction_date'] = $today;



            
            $spare_parts = SparePart::create($data);

            $sp_name = SparePart::where('id',$spare_parts->id)->first()->name;
            $data_transaction['spare_parts_id'] =  $spare_parts->id;
            $data_transaction['description'] =  'Opening balance for'.' '.$sp_name.' '.'is'.$spare_parts->opening_balance;
            $data_transaction['purchase_quantity'] = $spare_parts->opening_balance;
            $data_transaction['transaction_date'] =  $spare_parts->transaction_date;
            $data_transaction['transaction_type'] = 'opn';
            $data_transaction['last_transaction_by'] =  $spare_parts->last_transaction_by;
            

            $spare_parts_transaction = SparePartTransaction::create($data_transaction);


      }catch(ValidationException $e)
            {
                return Redirect::back();
            }

        Session::flash('success','Successfully added Spare part deatils');
        return redirect()->route('view-all-spare-parts');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sp_id = Crypt::decrypt($id);
        $spare_part = SparePart::with('group','subgroup','company')->where('id',$sp_id)->where('status',1)->first();

        $spare_parts = SparePart::with('group','subgroup','company')->where('status',1)->get();

        $spare_part_transactions = SparePartTransaction::with('user')->where('spare_parts_id',$spare_part->id)->where('status',1)->orderBy('id','asc')->get();
        return view('admin.spare-parts.show',compact('spare_part','spare_part_transactions','spare_parts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sp_id = Crypt::decrypt($id);
        $spare_part = SparePart::where('id',$sp_id)->where('status',1)->first();
        $companies = Company::where('status',1)->get();
        $groups = Group::where('status',1)->get();
        $sgroups = SubGroup::where('status',1)->get();
        $product_brands = Product::where('status',1)->where('brand','!=',null)->groupBy('brand')->get();
        return view('admin.spare-parts.edit',compact('spare_part','companies','groups','product_brands','sgroups'));
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
        $sp_id = Crypt::decrypt($id);
        $spare_part = SparePart::where('id',$sp_id)->where('status',1)->first();

        // dd($spare_part->id);

        // $old_opening_balance = $request->old_opening_balance;
        // $new_opening_balance = $request->opening_balance;

        $spare_part->name = $request->name;
        $spare_part->sp_code = $request->sp_code;
        $spare_part->part_no = $request->part_no;
        $spare_part->group_id = $request->group_id;
        $spare_part->subgroup_id = $request->subgroup_id;
        $spare_part->company_id = $request->company_id;
        $spare_part->brand = $request->brand;

        
        $spare_part->opening_balance = $request->opening_balance;
        // $spare_part->stock_in_hand = $request->opening_balance;

        $today = date("Y-m-d");
        $spare_part->transaction_date = $today;
        $spare_part->last_transaction_by = Auth::user()->id; 
        $spare_part->with_effect_from = $request->with_effect_from;
        $spare_part->tech_specification = $request->tech_specification;
        $spare_part->remarks = $request->remarks;
        $spare_part->save();

        $sp_transaction = SparePartTransaction::where('spare_parts_id',$spare_part->id)->where('transaction_type','opn')->where('status',1)->first();


        $sp_transaction->status = 0 ;
        $sp_transaction->save();

        $spare_part_transaction = new SparePartTransaction();
        $spare_part_transaction->spare_parts_id = $spare_part->id;
        $spare_part_transaction->purchase_quantity = $spare_part->opening_balance;
        $spare_part_transaction->description = 'Updated opening balance is'.' '.$spare_part->opening_balance;
        $spare_part_transaction->transaction_date = $spare_part->transaction_date;
        $spare_part_transaction->transaction_type = 'opn';
        $spare_part_transaction->last_transaction_by = $spare_part->last_transaction_by;
        // $spare_part_transaction->balance = $spare_part->stock_in_hand;
        $spare_part_transaction->save();

        Session::flash('success','Successfully updated Spare part deatils');
        return redirect()->route('view-all-spare-parts');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sp_id = Crypt::decrypt($id);

        
        $spare_part = SparePart::where('id',$sp_id)->where('status',1)->first();
        $spare_part->status = 0;
        $spare_part->save();

        $sp_transaction = SparePartTransaction::where('spare_parts_id',$sp_id)->where('status',1)->update(['status' => 0]);
       
        Session::flash('success','Successfully deleted Spare part deatils');
        return redirect()->route('view-all-spare-parts');

    }


    public function export(Request $request) 
    {

      $purchase_quantity = 0;
      $issued_quantity = 0 ;
      $stock_in_hand = [];

        $spare_parts = SparePart::with('group','company')->where('status',1)->orderBy('id','desc')->get();
        foreach ($spare_parts as $key => $value) {

           $purchase_quantity = SparePartTransaction::where('spare_parts_id',$value->id)->sum('purchase_quantity');
 
           $issued_quantity = SparePartTransaction::where('spare_parts_id',$value->id)->sum('issued_quantity');

           $stock_in_hand[] = $purchase_quantity - $issued_quantity;
           
          
        }

          if (!$stock_in_hand) {
            return redirect()->back();
          }

        try{
            Excel::create('SparePartDetails '.date('dmyHis'), function( $excel) use($spare_parts, $stock_in_hand){
                $excel->sheet('SparePart-Details ', function($sheet) use($spare_parts, $stock_in_hand){
                  $sheet->setTitle('SparePart-Details');

                  $sheet->cells('A1:M1', function($cells) {
                    $cells->setFontWeight('bold');
                  });
                  
                  $arr = [];
                    foreach($spare_parts->chunk(500) as $res):
                        foreach( $res as $k => $v) {
                            //dd($v);
                            $arr[$k]['Sl No']                   = $k+1;
                            $arr[$k]['Name']                    = $v->name;
                            // $arr[$k]['Group']                   = $v->group->name;
                            // $arr[$k]['Sub group name']          = $v->subgroup->name;

                            if($v->group_id != null)
                            {
                              $arr[$k]['Spare part group']                   = $v->group->name;
                            }else{
                              $arr[$k]['Spare part group']                   = '';
                            }
                            
                            if($v->subgroup_id != null)
                            {
                                $arr[$k]['Spare part sub group']               = $v->subgroup->name;
                            }else{
                                $arr[$k]['Spare part sub group']               = '';
                            }


                            if($v->subgroup_id != null)
                            {
                                $arr[$k]['Spare part Company']               = $v->company->name;
                            }else{
                                $arr[$k]['Spare part Company']               = '';
                            }

                            $arr[$k]['Brand']                = $v->brand;
                            $arr[$k]['Part no']                = $v->part_no;
                            $arr[$k]['Spare part code']                 = $v->sp_code;
                            $arr[$k]['Opening balance']                  = $v->opening_balance;
                            if (isset($stock_in_hand)) {
                                $arr[$k]['Stock in hand']             = $stock_in_hand[$k];
                            }else{
                              $arr[$k]['Stock in hand']             = '';
                            }

                            if($v->with_effect_from != "0000-00-00")
                            {
                                $arr[$k]['With effect from']        = $v->with_effect_from;
                            }else{
                                $arr[$k]['With effect from']        = '';
                            }

                            $arr[$k]['Transaction date']             = $v->transaction_date;
                            $arr[$k]['Technical specification']             = $v->tech_specification;
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

        Session::flash('success','Successfully exported company details');
        return Redirect::route('view-all-company');
    }


    

}
