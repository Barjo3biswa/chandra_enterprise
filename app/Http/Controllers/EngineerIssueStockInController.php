<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth, Validator, Session, Crypt,Excel,DB;

use  App\Models\SparePartMaster ,App\Models\SparePartTransaction, App\Models\IssueEngineer, App\User, App\Models\SparePart, App\Models\IssueEngineerTransaction;

class EngineerIssueStockInController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stock_ins = SparePartMaster::with('user')->where('trans_type','iss')->where('status',1)->orderBy('id','desc')->get();

        // $engg_stockins = SparePartMaster::with('spare_part','user')->where('trans_type','iss')->where('status',1)->orderBy('id','desc')->groupBy('engineer_id')->get();

        return view('admin.engineer-stockin.index',compact('stock_ins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $engineers = User::where('status',1)->get();
        $spare_parts = SparePart::with('group','subgroup','company')->where('status',1)->get();
        return view('admin.engineer-stockin.create',compact('engineers','spare_parts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $rules = [

        //      'engineer_id'                        =>  'required',
        //      'sp_name'                            =>  'required',
        //      'issue_quantity'                     =>  'required',
        //  ];

        //  $messages = [
        //     'engineer_id.required'                =>'Engineer name is required',
        //     'sp_name.required'                    =>'Spare part name is required',
        //     'issue_quantity.required'             =>'Issue quantity is required',
        //  ];
         
        // $validator = Validator::make($request->all(), $rules, $messages);

        // if ($validator->fails()) {
        //     Session::flash('error', 'Please fix the error and try again!');
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }

        // $validator = Validator::make($request->all(), SparePartTransaction::$rules);
        // if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput();

        if($request->input('date_of_transaction') != ''){
                $req_date = $request->input('date_of_transaction');
                $tdr = str_replace("/", "-", $req_date);
                $new_mnf_dt = date('Y-m-d',strtotime($tdr));
            }
        else
            $new_mnf_dt = " ";

        $date_of_transaction = $new_mnf_dt;
        // $purchase_from = $request->purchase_from;
        $remarks = $request->remarks;
        $sp_id = $request->sp_name;
        $issue_quantity = $request->issue_quantity;

        $engg_id = $request->engineer_id;
      
        $sp_master = new SparePartMaster();
        $sp_master->engineer_id = $request->engineer_id;
        $sp_master->date_of_transaction = $date_of_transaction;
        // $sp_master->purchased_from = $purchase_from;
        $sp_master->remarks = $remarks;
        $sp_master->trans_type = 'iss';
        $sp_master->save();


        if ($sp_id) {
            foreach ($sp_id as $key => $value) {

                $sp_name = SparePart::where('id',$sp_id)->first()->name;
                // dd($sp_name);
                $issue_engg_transaction = IssueEngineerTransaction::where('spare_part_id',$sp_id[$key])->where('engineer_id',$engg_id)->where('status',1)->first();

                $issue_engg = IssueEngineer::where('engineer_id',$engg_id)->where('spare_part_id',$sp_id[$key])
->where('status',1)->first();
                // dd($issue_engg_transaction);

                if ($issue_engg_transaction || $issue_engg) {

                    if ($issue_engg_transaction) {
                        $issue_engg_transaction->stock_in = $issue_engg_transaction->stock_in + $request->issue_quantity[$key];
                        $issue_engg_transaction->save();
                    }
                    
                    if ($issue_engg) {
                        $issue_engg->stock_in_hand = $issue_engg->stock_in_hand + $request->issue_quantity[$key];
                        $issue_engg->save();
                    }

                  
                }else{

                    // dd('jdfe');
                    $sp_transaction = new SparePartTransaction();
                
                    $sp_transaction->spare_parts_id = $sp_id[$key];
                    $sp_transaction->description = 'Issued spare-part'.' '.$sp_name.' '.'of quantity'.' '.$request->issue_quantity[$key];
                    $sp_transaction->transaction_date = $sp_master->date_of_transaction;
                    $sp_transaction->transaction_type = 'iss';
                    $sp_transaction->issued_quantity = $request->issue_quantity[$key];
                    $sp_transaction->last_transaction_by = Auth::user()->id;
                    $sp_transaction->spare_part_master_id = $sp_master->id;
                    $sp_transaction->save();

                    $engg_issue = new IssueEngineer();
                    $engg_issue->spare_part_master_id = $sp_master->id;
                    $engg_issue->engineer_id = $sp_master->engineer_id;
                    $engg_issue->spare_part_id = $sp_id[$key];
                    $engg_issue->stock_in_hand = $request->issue_quantity[$key];
                    $engg_issue->last_updated_at = $sp_master->date_of_transaction;
                    $engg_issue->save();


                    $issue_engg_trans = new IssueEngineerTransaction();
                    $issue_engg_trans->engineer_sp_trans_id = $sp_transaction->id;
                    $issue_engg_trans->spare_part_master_id = $sp_master->id;
                    $issue_engg_trans->engineer_id = $sp_master->engineer_id;
                    $issue_engg_trans->spare_part_id = $sp_id[$key];
                    $issue_engg_trans->description = 'Issued spare-part'.' '.$sp_name.' '.'of quantity'.' '.$request->issue_quantity[$key];
                    $issue_engg_trans->transaction_date = $sp_master->date_of_transaction;
                    $issue_engg_trans->stock_in = $request->issue_quantity[$key];
                    $issue_engg_trans->save();  

                }
     
            }
        }
   

        Session::flash('success','Successfully issued to engineer spare part details');
        return redirect()->route('view-all-engineer-issue-stockin');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sp_master_id = Crypt::decrypt($id);
        $spare_parts = SparePart::with('group','subgroup','company')->where('status',1)->get();

        $sp_master = SparePartMaster::with('spare_part','user')->where('id',$sp_master_id)->where('trans_type','iss')->where('status',1)->first();

        $sp_transaction = IssueEngineerTransaction::with('spare_part','user')->where('engineer_id',$sp_master->engineer_id)->where('status',1)->orderBy('id','asc')->get();



        // $sp_transaction = SparePartTransaction::with('user','spare_part')->where('spare_part_master_id',$sp_master->id)->where('status',1)->where('transaction_type','iss')->orderBy('id','desc')->get();


        return view('admin.engineer-stockin.show',compact('spare_parts','sp_master','sp_transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sp_master_id = Crypt::decrypt($id);

        $sp_master = SparePartMaster::with('spare_part','user')->where('id',$sp_master_id)->where('trans_type','iss')->where('status',1)->first();

        $sp_transactions = SparePartTransaction::with('user','spare_part')->where('spare_part_master_id',$sp_master_id)->where('status',1)->get();

        $engineers = User::where('status',1)->get();
        $spare_parts = SparePart::with('group','subgroup','company')->where('status',1)->get();
        return view('admin.engineer-stockin.edit',compact('engineers','spare_parts','sp_master','sp_transactions'));
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
       $sp_master_id = Crypt::decrypt($id);

        
        // dd($sp_master);

        if($request->input('date_of_transaction') != ''){
                $req_date = $request->input('date_of_transaction');
                $tdr = str_replace("/", "-", $req_date);
                $new_mnf_dt = date('Y-m-d',strtotime($tdr));
            }
        else
            $new_mnf_dt = " ";

        $date_of_transaction = $new_mnf_dt;
        // $purchase_from = $request->purchase_from;
        $remarks = $request->remarks;
        $sp_id = $request->sp_name;
        $issue_quantity = $request->issue_quantity;

        $engg_id = $request->engineer_id;
      
        $sp_master = SparePartMaster::with('spare_part','user')->where('id',$sp_master_id)->where('trans_type','iss')->where('status',1)->first();

        $sp_master->engineer_id = $request->engineer_id;
        $sp_master->date_of_transaction = $date_of_transaction;
        $sp_master->remarks = $remarks;
        $sp_master->trans_type = 'iss';
        $sp_master->save();

        SparePartTransaction::where('spare_part_master_id',$sp_master->id)->update(['status' => '0']);
        IssueEngineerTransaction::where('spare_part_master_id',$sp_master->id)->update(['status' => '0']);
        IssueEngineer::where('spare_part_master_id',$sp_master->id)->update(['status' => '0']);


        if ($sp_id) {
            foreach ($sp_id as $key => $value) {

                $sp_name = SparePart::where('id',$sp_id)->first()->name;
                // dd($sp_name);

                // $issue_engg_transaction = IssueEngineerTransaction::where('spare_part_master_id',$sp_master->id)->where('spare_part_id',$sp_id[$key])->where('engineer_id',$engg_id)->where('status',1)->first();

                // $issue_engg = IssueEngineer::where('spare_part_master_id',$sp_master->id)->where('engineer_id',$engg_id)->where('spare_part_id',$sp_id[$key])->where('status',1)->first();

               
                // $sp_master_trans = SparePartTransaction::where('spare_part_master_id',$sp_master->id)->where('spare_parts_id',$sp_id[$key])->where('status',1)->first();

                // if ($sp_master_trans || $issue_engg || $issue_engg_transaction) {
                     // dd($sp_master_trans);
                    
                    
                        $new_sp_transaction = new SparePartTransaction();
                        $new_sp_transaction->spare_parts_id = $sp_id[$key];
                        $new_sp_transaction->description = 'Issued spare-part'.' '.$sp_name.' '.'of quantity'.' '.$request->issue_quantity[$key];
                        $new_sp_transaction->transaction_date = $sp_master->date_of_transaction;
                        $new_sp_transaction->transaction_type = 'iss';
                        $new_sp_transaction->issued_quantity = $request->issue_quantity[$key];
                        $new_sp_transaction->last_transaction_by = Auth::user()->id;
                        $new_sp_transaction->spare_part_master_id = $sp_master->id;
                        $new_sp_transaction->save();

                    

                        $issue_engg_trans = new IssueEngineerTransaction();
                        $issue_engg_trans->engineer_sp_trans_id = $new_sp_transaction->id;
                        $issue_engg_trans->engineer_id = $sp_master->engineer_id;
                        $issue_engg_trans->spare_part_id = $sp_id[$key];
                        $issue_engg_trans->description = 'Issued spare-part'.' '.$sp_name.' '.'of quantity'.' '.$request->issue_quantity[$key];
                        $issue_engg_trans->transaction_date = $sp_master->date_of_transaction;
                        $issue_engg_trans->stock_in = $request->issue_quantity[$key];
                        $issue_engg_trans->save(); 

                    

                        $new_engg_issue = new IssueEngineer();
                        $new_engg_issue->spare_part_master_id = $sp_master->id;
                        $new_engg_issue->engineer_id = $sp_master->engineer_id;
                        $new_engg_issue->spare_part_id = $sp_id[$key];
                        $new_engg_issue->stock_in_hand = $request->issue_quantity[$key];
                        $new_engg_issue->last_updated_at = $sp_master->date_of_transaction;
                        $new_engg_issue->save();

                // }

                // dd('hi');

                //dd($sp_master_trans);

                // if ($issue_engg_transaction || $issue_engg || $sp_master_trans) {

                    // $issue_engg_transaction->stock_in = $issue_engg_transaction->stock_in + $request->issue_quantity[$key];

                    // $issue_engg_transaction->save();


                    // $issue_engg->stock_in_hand = $issue_engg->stock_in_hand + $request->issue_quantity[$key];
                    // $issue_engg->save();

                    // $sp_master_trans->issued_quantity = $sp_master_trans->issued_quantity + $request->issue_quantity[$key];

                    // $sp_master_trans->save();

                  
                // }else{

                   
                    // $sp_transaction = SparePartTransaction::where('spare_part_master_id',$sp_master->id)->first();

                    // if($sp_transaction){
                    //     $sp_transaction->spare_parts_id = $sp_id[$key];
                    //     $sp_transaction->description = 'Issued spare-part'.' '.$sp_name.' '.'of quantity'.' '.$request->issue_quantity[$key];
                    //     $sp_transaction->transaction_date = $sp_master->date_of_transaction;
                    //     $sp_transaction->transaction_type = 'iss';
                    //     $sp_transaction->issued_quantity = $request->issue_quantity[$key];
                    //     $sp_transaction->last_transaction_by = Auth::user()->id;
                    //     $sp_transaction->spare_part_master_id = $sp_master->id;
                    //     $sp_transaction->save();
                    // }else{

                        // $new_sp_transaction = new SparePartTransaction();
                        // $new_sp_transaction->spare_parts_id = $sp_id[$key];
                        // $new_sp_transaction->description = 'Issued spare-part'.' '.$sp_name.' '.'of quantity'.' '.$request->issue_quantity[$key];
                        // $new_sp_transaction->transaction_date = $sp_master->date_of_transaction;
                        // $new_sp_transaction->transaction_type = 'iss';
                        // $new_sp_transaction->issued_quantity = $request->issue_quantity[$key];
                        // $new_sp_transaction->last_transaction_by = Auth::user()->id;
                        // $new_sp_transaction->spare_part_master_id = $sp_master->id;
                        // $new_sp_transaction->save();
                    // }
                
                    
                    // $engg_issue = IssueEngineer::where('spare_part_master_id',$sp_master->id)->first();

                    // if($IssueEngineer)
                    // {
                    //     $engg_issue->spare_part_master_id = $sp_master->id;
                    //     $engg_issue->engineer_id = $sp_master->engineer_id;
                    //     $engg_issue->spare_part_id = $sp_id[$key];
                    //     $engg_issue->stock_in_hand = $request->issue_quantity[$key];
                    //     $engg_issue->last_updated_at = $sp_master->date_of_transaction;
                    //     $engg_issue->save();
                    // }else{
                        // $new_engg_issue = new IssueEngineer();
                        // $new_engg_issue->spare_part_master_id = $sp_master->id;
                        // $new_engg_issue->engineer_id = $sp_master->engineer_id;
                        // $new_engg_issue->spare_part_id = $sp_id[$key];
                        // $new_engg_issue->stock_in_hand = $request->issue_quantity[$key];
                        // $new_engg_issue->last_updated_at = $sp_master->date_of_transaction;
                        // $new_engg_issue->save();
                    // }

                    
                    // $issue_engg_trans = IssueEngineer::where('spare_part_master_id',$sp_master->id)->first();
                    // if ($issue_engg_trans) {
                    //     $issue_engg_trans->engineer_sp_trans_id = $sp_transaction->id;
                    //     $issue_engg_trans->engineer_id = $sp_master->engineer_id;
                    //     $issue_engg_trans->spare_part_id = $sp_id[$key];
                    //     $issue_engg_trans->description = 'Issued spare-part'.' '.$sp_name.' '.'of quantity'.' '.$request->issue_quantity[$key];
                    //     $issue_engg_trans->transaction_date = $sp_master->date_of_transaction;
                    //     $issue_engg_trans->stock_in = $request->issue_quantity[$key];
                    //     $issue_engg_trans->save(); 
                    // }else{
                        // $issue_engg_trans = new IssueEngineerTransaction();
                        // $issue_engg_trans->engineer_sp_trans_id = $sp_transaction->id;
                        // $issue_engg_trans->engineer_id = $sp_master->engineer_id;
                        // $issue_engg_trans->spare_part_id = $sp_id[$key];
                        // $issue_engg_trans->description = 'Issued spare-part'.' '.$sp_name.' '.'of quantity'.' '.$request->issue_quantity[$key];
                        // $issue_engg_trans->transaction_date = $sp_master->date_of_transaction;
                        // $issue_engg_trans->stock_in = $request->issue_quantity[$key];
                        // $issue_engg_trans->save(); 
                    // }

                // }
     
            }
        }
   

       Session::flash('success','Successfully updated issued spare part details');
       return redirect()->route('view-all-engineer-issue-stockin');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sp_master_id = Crypt::decrypt($id);

        $sp_master = SparePartMaster::with('spare_part','user')->where('id',$sp_master_id)->where('trans_type','iss')->where('status',1)->first();
        $sp_master->status = 0;
        $sp_master->save();

        $sp_master_trans = SparePartTransaction::where('spare_part_master_id',$sp_master->id)->update(['status' => '0']);
        // foreach ($sp_master_trans as $key => $value) {
        //     $value->status = 0;
        //     $value->save();
        // }

        $sp_issue_engg = IssueEngineer::where('spare_part_master_id',$sp_master->id)->update(['status' => '0']);
        // foreach ($sp_issue_engg as $key1 => $value1) {
        //     $value1->status = 0;
        //     $value1->save();
        // }

        $sp_issue_engg_trans = IssueEngineerTransaction::where('spare_part_master_id',$sp_master->id)->update(['status' => '0']);
        // foreach ($sp_issue_engg_trans as $key2 => $value2) {
        //     $value2->status = 0;
        //     $value2->save();
        // }

        // dd($sp_issue_engg_trans);
        Session::flash('success','Successfully deleted issued spare part details');
        return redirect()->route('view-all-engineer-issue-stockin');


    }


    public function export(Request $request) 
    {
        $stock_ins = SparePartMaster::with('spare_part','spare_part_transaction','user','spare_part_transaction.spare_part')->where('trans_type','iss')->where('status',1)->orderBy('id','desc')->get();

        try{
            Excel::create('StockoutDetails '.date('dmyHis'), function( $excel) use($stock_ins){
                $excel->sheet('Stockout-Details ', function($sheet) use($stock_ins){
                  $sheet->setTitle('Stockout-Details');

                  $sheet->cells('A1:L1', function($cells) {
                    $cells->setFontWeight('bold');
                  });
                  
                  $arr = [];
                  $counter = 1;
                    foreach($stock_ins->chunk(500) as $res):
                        foreach( $res as $k => $v) {
                            // dd($v);
                            $arr[$counter]['Sl No']                   = $k+1;
                            $arr[$counter]['Engineer']                 = ucwords($v->user->first_name.' '.$v->user->middle_name.' '.$v->user->last_name);
                            $arr[$counter]['Date of transaction']                    = dateFormat($v->date_of_transaction);
                            $arr[$counter]['Remarks']                 = $v->remarks;


                            // $temp_counter = $counter;
                            if (!sizeof($v->spare_part_transaction)) {
                                    $arr[$counter]['Spare part name']                 = '';
                                    $arr[$counter]['Spare part no']                 = '';
                                    $arr[$counter]['Spare part opening balance']                 = '';
                                    $arr[$counter]['Description']                 = '';
                                    $arr[$counter]['Issued quantity']                 = '';
                                    $counter++;
                            }
                            foreach ($v->spare_part_transaction as $key1 => $value1){
                                if($key1 > 0){
                                    $arr[$counter]['Sl No']                   = '';
                                    $arr[$counter]['Engineer']                      = '';
                                    $arr[$counter]['Date of transaction']                    = '';
                                    $arr[$counter]['Remarks']                 = '';
                                }
                                    $arr[$counter]['Spare part name']                 = $value1->spare_part->name;
                                    $arr[$counter]['Spare part no']                 = $value1->spare_part->part_no;
                                    $arr[$counter]['Spare part opening balance']                 = $value1->spare_part->opening_balance;
                                    $arr[$counter]['Description']                 = $value1->description;
                                    $arr[$counter]['Issued quantity']                 = $value1->issued_quantity;

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

        Session::flash('success','Successfully exported issued spare part details');
        return Redirect::route('view-all-stock-in');
    }
}
