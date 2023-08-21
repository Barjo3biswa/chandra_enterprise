<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth, Validator, Session, Crypt,Excel;
use App\Models\SparePart, App\Models\SparePartMaster, App\Models\SparePartTransaction;

class StockInController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stock_ins = SparePartMaster::with('spare_part')->where('trans_type','pur')->where('status',1)->orderBy('id','desc')->get();
       
        return view('admin.stockin.index',compact('stock_ins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $spare_parts = SparePart::with('group','subgroup','company')->where('status',1)->get();
        return view('admin.stockin.create',compact('spare_parts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        
        if($request->input('date_of_transaction') != ''){
                $req_date = $request->input('date_of_transaction');
                $tdr = str_replace("/", "-", $req_date);
                $new_mnf_dt = date('Y-m-d',strtotime($tdr));
            }
        else
            $new_mnf_dt = " ";

        $date_of_transaction = $new_mnf_dt;
        $purchase_from = $request->purchase_from;
        $remarks = $request->remarks;
        $sp_id = $request->sp_name;
        $purchase_quantity = $request->purchase_quantity;
      
        $sp_master = new SparePartMaster();
        // $sp_master->spare_part_id = $value;
        $sp_master->date_of_transaction = $date_of_transaction;
        $sp_master->purchased_from = $purchase_from;
        $sp_master->remarks = $remarks;
        $sp_master->trans_type = 'pur';
        $sp_master->save();


        if ($sp_id) {
            foreach ($sp_id as $key => $value) {

                $sp_name = SparePart::where('id',$sp_id)->first()->name;
                // dd($sp_name);

                $sp_transaction = new SparePartTransaction();
                
                $sp_transaction->spare_parts_id = $sp_id[$key];
                $sp_transaction->description = 'Purchased new spare-part'.' '.$sp_name.' '.'of quantity'.' '.$request->purchase_quantity[$key];
                $sp_transaction->transaction_date = $sp_master->date_of_transaction;
                $sp_transaction->transaction_type = 'pur';
                $sp_transaction->purchase_quantity = $request->purchase_quantity[$key];
                $sp_transaction->last_transaction_by = Auth::user()->id;
                $sp_transaction->spare_part_master_id = $sp_master->id;
                $sp_transaction->save();

            }
        }

        Session::flash('success','Successfully added spare part purchase details');
        return redirect()->route('view-all-stock-in');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $purchase_quantity = 0;
        $issued_quantity = 0;

        $sp_master_id = Crypt::decrypt($id);
       
     // $trans = SparePartTransaction::where('status',1)->get();
     //    foreach ($trans as $key => $value) {
     //        $spare_parts = SparePart::where('id',$value->spare_parts_id)->where('status',1)->get();
     //        foreach ($spare_parts as $key1 => $value1) {
     //             $purchase_quantity = SparePartTransaction::where('spare_parts_id',$value->id)->where('status',1)->sum('purchase_quantity');

     //             $issued_quantity = SparePartTransaction::where('spare_parts_id',$value->id)->sum('issued_quantity');

     //             $stock_in_hand[] = $purchase_quantity - $issued_quantity;
     //        }
     //    }


        $sp_master = SparePartMaster::with('spare_part')->where('id',$sp_master_id)->where('status',1)->first();


            $sp_transaction = SparePartTransaction::with('user','spare_part')->where('spare_part_master_id',$sp_master->id)->where('status',1)->orderBy('id','desc')->get();

          
                // foreach ($sp_transaction as $key => $value) {
                //     $purchase_quantity = SparePartTransaction::where('spare_parts_id',$value->spare_parts_id)->sum('purchase_quantity');
                //     $issued_quantity = SparePartTransaction::where('spare_parts_id',$value->spare_parts_id)->sum('issued_quantity');

                //     $stock_in_hand[] = $purchase_quantity - $issued_quantity;

                // }

// dd($stock_in_hand);
       
        return view('admin.stockin.show',compact('spare_parts','sp_master','sp_transaction','stock_in_hand'));



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

         $sp_master = SparePartMaster::with('spare_part')->where('id',$sp_master_id)->where('status',1)->first();

         $sp_transactions = SparePartTransaction::with('spare_part_master')->where('spare_part_master_id',$sp_master_id)->where('status',1)->get();

         $spare_parts = SparePart::with('group','subgroup','company')->where('status',1)->get();
         return view('admin.stockin.edit',compact('sp_transactions','spare_parts','sp_master'));
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

        if($request->input('date_of_transaction') != ''){
                $req_date = $request->input('date_of_transaction');
                $tdr = str_replace("/", "-", $req_date);
                $new_mnf_dt = date('Y-m-d',strtotime($tdr));
            }
        else
            $new_mnf_dt = " ";

        $date_of_transaction = $new_mnf_dt;
        $purchase_from = $request->purchase_from;
        $remarks = $request->remarks;
        $sp_id = $request->sp_name;
        $purchase_quantity = $request->purchase_quantity;


        $sp_master_id = Crypt::decrypt($id);

         //$sp_edit_transaction = SparePartTransaction::with('spare_part_master')->where('id',$sp_master_id)->where('status',1)->first();
         $sp_master = SparePartMaster::with('spare_part')->where('id',$sp_master_id)->first();
         $sp_master->date_of_transaction = $date_of_transaction;
         $sp_master->purchased_from = $purchase_from;
         $sp_master->remarks = $remarks;
         $sp_master->trans_type = 'pur';
         $sp_master->save();
         // $sp_master->status = 0;
         $sp_master->save();

         SparePartTransaction::where('spare_part_master_id',$sp_master->id)->where('status',1)->update(['status'=>'0']);
         //        $sp_edit_transaction->spare_parts_id = $request->sp_name;
         //        $sp_edit_transaction->purchase_quantity = $request->purchase_quantity;
                //$sp_edit_transaction->save();

        if ($sp_id) {
            foreach ($sp_id as $key => $value) {
                
                $sp_name = SparePart::where('id',$sp_id)->first()->name;
                // dd($sp_name);

                $sp_transaction = new SparePartTransaction();
                
                $sp_transaction->spare_parts_id = $sp_id[$key];
                $sp_transaction->description = 'Purchased new spare-part'.' '.$sp_name.' '.'of quantity'.' '.$request->purchase_quantity[$key];
                $sp_transaction->transaction_date = $sp_master->date_of_transaction;
                $sp_transaction->transaction_type = 'pur';
                $sp_transaction->purchase_quantity = $request->purchase_quantity[$key];
                $sp_transaction->last_transaction_by = Auth::user()->id;
                $sp_transaction->spare_part_master_id = $sp_master->id;
                $sp_transaction->save();

            }
        }

        Session::flash('success','Successfully updated spare part purchase details');
        return redirect()->route('view-all-stock-in');


         //dd($sp_master);
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

         $sp_master = SparePartMaster::with('spare_part')->where('id',$sp_master_id)->where('status',1)->first();
         $sp_master->status = 0;
         $sp_master->save();

         $sp_master_trans = SparePartTransaction::where('spare_part_master_id',$sp_master->id)->get();
         foreach ($sp_master_trans as $key => $value) {
             $value->status = 0;
             $value->save();
         }

        Session::flash('success','Successfully deleted spare part purchase details');
        return redirect()->route('view-all-stock-in');
    }

    public function addNewStockInPost(Request $request)
    {
        $spare_part_id = $request->spare_part_id;

        $spare_part_master = new SparePartMaster();
        $spare_part_master->spare_part_id = $spare_part_id;
        $spare_part_master->date_of_transaction = $request->date_of_transaction;
        $spare_part_master->purchased_from = $request->purchase_from;
        $spare_part_master->remarks = $request->remarks;
        $spare_part_master->save();

        // $spare_prt_trans = SparePartTransaction::where('spare_parts_id',$spare_part_master->spare_part_id)->where('status',1)->first();
        // $spare_prt_trans->status = 0;
        // $spare_prt_trans->save();

        $aaa = $request->purchase_quantity;

        if($aaa){
            foreach ($aaa as $key => $value) {
                $sp_transaction = new SparePartTransaction();
                $sp_transaction->spare_parts_id = $spare_part_master->spare_part_id;
                $sp_transaction->description = 'Purchased new spare-part'.' '.$request->purchase_quantity;
                $sp_transaction->transaction_date = $spare_part_master->date_of_transaction;
                $sp_transaction->purchase_quantity = $request->purchase_quantity;
                $sp_transaction->spare_part_master_id = $spare_part_master->id;
                $sp_transaction->save(); 
            }
        }

        // $sp_transaction = new SparePartTransaction();
        // $sp_transaction->spare_parts_id = $spare_part_master->spare_part_id;
        // $sp_transaction->description = 'Purchased new spare-part'.' '.$request->purchase_quantity;
        // $sp_transaction->transaction_date = $spare_part_master->date_of_transaction;
        // $sp_transaction->purchase_quantity = $request->purchase_quantity;
        // $sp_transaction->spare_part_master_id = $spare_part_master->id;
        // $sp_transaction->save(); 

        Session::flash('success','Successfully added spare part purchase details');
        return redirect()->route('show-spare-parts',['id'=>$spare_part_id]);

    }

    public function export(Request $request) 
    {
        $stock_ins = SparePartMaster::with('spare_part','spare_part_transaction','user','spare_part_transaction.spare_part')->where('trans_type','pur')->where('status',1)->orderBy('id','desc')->get();

        // dd($stock_ins);

       
        try{
            Excel::create('StockinDetails '.date('dmyHis'), function( $excel) use($stock_ins){
                $excel->sheet('Stockin-Details ', function($sheet) use($stock_ins){
                  $sheet->setTitle('Stockin-Details');

                  $sheet->cells('A1:L1', function($cells) {
                    $cells->setFontWeight('bold');
                  });
                  
                  $arr = [];
                  $counter = 1;

                    foreach($stock_ins->chunk(500) as $res):

                        foreach( $res as $k => $v) {
                            // dd($v);
                            $arr[$counter]['Sl No']                   = $k+1;
                            $arr[$counter]['Date of transaction']                    = dateFormat($v->date_of_transaction);
                            $arr[$counter]['Purchased from']                    = ucwords($v->purchased_from);
                            $arr[$counter]['Remarks']                 = $v->remarks;

                            // $temp_counter = $counter;

                            foreach ($v->spare_part_transaction as $key1 => $value1){
                                if($key1 > 0){
                                    $arr[$counter]['Sl No']                   = '';
                                    $arr[$counter]['Date of transaction']                    = '';
                                    $arr[$counter]['Purchased from']                    = '';
                                    $arr[$counter]['Remarks']                 = '';
                                }
                                    $arr[$counter]['Spare part name']                 = $value1->spare_part->name;
                                    $arr[$counter]['Spare part no']                 = $value1->spare_part->part_no;
                                    $arr[$counter]['Spare part opening balance']                 = $value1->spare_part->opening_balance;
                                    $arr[$counter]['Description']                 = $value1->description;
                                    $arr[$counter]['Purchase quantity']                 = $value1->purchase_quantity;

                                  
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

        Session::flash('success','Successfully exported spare part stock in details');
        return Redirect::route('view-all-stock-in');
    }
}
