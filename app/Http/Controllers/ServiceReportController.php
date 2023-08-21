<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth,Session,DB,Crypt,Validator,Excel, Redirect;

use App\Models\Dsr\DailyServiceReport, App\Models\Dsr\DailyServiceReportTransaction, App\Models\Client, App\Models\ClientAmcMaster, App\Models\Complaint, App\Models\Assign\AssignProductToClient, App\Models\Product, App\Models\SparePartMaster, App\Models\SparePartTransaction, App\Models\SparePart, App\Models\ComplaintTransaction, App\Models\IssueEngineer, App\Models\IssueEngineerTransaction,App\User;

class ServiceReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$date_from  = "2019-01-01";
		$date_to 	= date("Y-m-d");
        $users = User::where('status',1)->get();
        $clients = Client::where('status',1)->groupBy('name')->get();
        $all_clients = Client::where('status',1)->get();
        $dsr_reports = DailyServiceReport::with('dsr_transaction','client','product','engineer','complaint')->where('status',1);

        $today = date('Y-m-d');
		
		
        if ($request->entry_datetime) {
			$dsr_reports=  $dsr_reports->where("entry_datetime","like",'%'.$request->entry_datetime.'%');
        }
		
        if ($request->date_from) {
			$date_from = date('Y-m-d', strtotime($request->date_from));
			// $dsr_reports=  $dsr_reports->where("entry_datetime","like",'%'.$date_from.'%');
        }
		
        if ($request->date_to) {
			$date_to = date('Y-m-d', strtotime($request->date_to));
			//    $dsr_reports=  $dsr_reports->where("entry_datetime","like",'%'.$date_to.'%');
		}

		$dsr_reports->whereRaw(" cast(entry_datetime as DATE) BETWEEN ? and ? ", [$date_from, $date_to]);

        if ($request->engineer_id) {
           $dsr_reports=  $dsr_reports->where("entry_by","like",'%'.$request->engineer_id.'%');
        }

        if ($request->client_id) {

          	$client_names = Client::where('name','like','%'.$request->client_id.'%')->where('status',1)->get()->toArray();
                
                $clients_id = [];
                foreach ($client_names as $key => $client_name) {
                    array_push($clients_id, $client_name['id']);
                }
            $dsr_reports = $dsr_reports->whereIn('client_id',$clients_id);
        }

        if ($request->branch) {
          	$branch_names = Client::select('id')->where('branch_name','like','%'.$request->branch.'%')->where('status',1)->get()->toArray();
			$branchs = [];
			foreach ($branch_names as $key => $branch_name) {
				array_push($branchs, $branch_name['id']);
			}
			$dsr_reports = $dsr_reports->whereIn('client_id',$branchs);
        }

		if($request->has("type") && $request->get("type") == "breakdown"){
			$dsr_reports->where("maintenance_type", 1);
		}
		if($request->has("type") && $request->get("type") == "preventive"){
			$dsr_reports->where("maintenance_type", 2);
		}
        $dsr_reports = $dsr_reports->orderBy('id','desc')->get();

        return view('admin.dsr.index',compact('dsr_reports','users','clients','all_clients'));
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
        $dsr_id = Crypt::decrypt($id);
        $dsr = DailyServiceReport::with('dsr_transaction','client','engineer','complaint', "dsr_products")->where('id',$dsr_id)->where('status',1)->first();
        return view('admin.dsr.show',compact('dsr'));
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

    public function printView($id)
    {
        $dsr_id = Crypt::decrypt($id);

        $dsr = DailyServiceReport::with('dsr_transaction','client','product','engineer','complaint', "dsr_products")->where('id',$dsr_id)->where('status',1)->first();

        return view('admin.dsr.print-view',compact('dsr'));
    }


    public function export(Request $request)
    {
		$dsr_reports = DailyServiceReport::with('dsr_transaction','client','product','engineer','complaint', "dsr_products");
		$date_from  = "2019-01-01";
		$date_to 	= date("Y-m-d");
		
        if ($request->date_from) {
			$date_from = date('Y-m-d', strtotime($request->date_from));
        }
		
        if ($request->date_to) {
			$date_to = date('Y-m-d', strtotime($request->date_to));
		}

		$dsr_reports->whereRaw(" cast(entry_datetime as DATE) BETWEEN ? and ? ", [$date_from, $date_to]);
		$dsr_reports->where('status',1)->orderBy('id','desc');
		if($request->has("type") && $request->get("type") == "breakdown"){
			$dsr_reports->where("maintenance_type", 1);
		}
		if($request->has("type") && $request->get("type") == "preventive"){
			$dsr_reports->where("maintenance_type", 2);
		}
		$dsr_reports = $dsr_reports->get();
		if($request->get("type") == "preventive"){
			// if preventive reports other then use here
		}
		try{
			Excel::create('ClientDsrDetails '.date('dmyHis'), function( $excel) use($dsr_reports){
				$excel->sheet('Client-DSR-Details ', function($sheet) use($dsr_reports){
					$sheet->setTitle('Client-DSR-Details');

					$sheet->cells('A1:BZ1', function($cells) {
						$cells->setFontWeight('bold');
					});
				  
					$arr = [];
					$counter = 1;
					foreach($dsr_reports->chunk(500) as $res):
						$after_product_count = $counter;
						// dd($res);
						foreach ($res as $k => $v) {
							$arr[$counter]['Sl No']       = $k + 1;
							$arr[$counter]['Client Name'] = $v->client->name;
							$arr[$counter]['Branch Name'] = $v->client->branch_name;

							if ($v->client->region_id != null) {
								$arr[$counter]['Region Name'] = $v->client->region->name;
							} else {
								$arr[$counter]['Region Name'] = '';
							}

							$arr[$counter]['Zone Name'] = $v->client->zone->name;

							$arr[$counter]['Call Receive Date'] = dateFormat($v->call_receive_date);
							$arr[$counter]['Call Attend Date']  = dateFormat($v->call_attend_date);

							if ($v->dsr_products->count()) {
								foreach($v->dsr_products as $product_key => $pv){
									if($product_key > 0){
										// blank all other field
										$arr[$after_product_count]['Sl No']                           = '';
										$arr[$after_product_count]['Client Name']                     = '';
										$arr[$after_product_count]['Branch Name']                     = '';
										$arr[$after_product_count]['Region Name']                     = '';
										$arr[$after_product_count]['Zone Name']                       = '';
										$arr[$after_product_count]['Call Receive Date']               = '';
										$arr[$after_product_count]['Call Attend Date']                = '';
									}
									$arr[$after_product_count]['Product']          = $pv->product->name ?? "NA";
									$arr[$after_product_count]['Product Group']    = $pv->product->group->name ?? "NA";
									$arr[$after_product_count]['Product Sl No']    = $pv->product->serial_no ?? "NA";
									$arr[$after_product_count]['Product Part No']  = $pv->product->part_no ?? "NA";
									$arr[$after_product_count]['Product Model No'] = $pv->product->model_no;
									
									$arr[$after_product_count]['Nature Of Complaint By Customer'] = $pv->nature_of_complaint_by_customer;
									$arr[$after_product_count]['Fault Observation By Engineer']   = $pv->fault_observation_by_engineer;
									$arr[$after_product_count]['Action Taken By Engineer']        = $pv->action_taken_by_engineer;
									$arr[$after_product_count]['Remarks']                         = $pv->remarks;
									if($product_key > 0){
										// blank all other field
										$arr[$after_product_count]['Contact Person Name']  = "";
										$arr[$after_product_count]['Contact Person Ph No'] = "";
										$arr[$after_product_count]['Maintenance Type']     = "";
										$arr[$after_product_count]['Complaint No']         = "";
										$arr[$after_product_count]['Complaint Status']     = "";
										$arr[$after_product_count]['Complaint Status']     = "";
										$arr[$after_product_count]['Complaint Status']     = "";

									}
									$after_product_count++;
								}
							} else {
								$arr[$counter]['Product']          = '';
								$arr[$counter]['Product Group']    = '';
								$arr[$counter]['Product Sl No']    = '';
								$arr[$counter]['Product Part No']  = '';
								$arr[$counter]['Product Model No'] = '';	

								$arr[$counter]['Nature Of Complaint By Customer'] = "";
								$arr[$counter]['Fault Observation By Engineer']   = "";
								$arr[$counter]['Action Taken By Engineer']        = "";
								$arr[$counter]['Remarks']                         = "";
							}

							$arr[$counter]['Contact Person Name']  = $v->contact_person_name;
							$arr[$counter]['Contact Person Ph No'] = $v->contact_person_ph_no;

							if ($v->maintenance_type == 1) {
								$arr[$counter]['Maintenance Type'] = 'Break Down';
							}
							if ($v->maintenance_type == 2) {
								$arr[$counter]['Maintenance Type'] = 'Preventive';
							}

							$arr[$counter]['Complaint No'] = $v->complaint_no ?? "NA";

							if ($v->complaint_status == 2) {
								$arr[$counter]['Complaint Status'] = 'Under Process';
							}

							if ($v->complaint_status == 3) {
								$arr[$counter]['Complaint Status'] = 'Closed';
							}

							if ($v->complaint_status == null) {
								$arr[$counter]['Complaint Status'] = '';
							}

							// Transactions
							foreach ($v->dsr_transaction as $key1 => $value1) {

								if (($key1+1) > $v->dsr_products->count() || $v->dsr_products->count() == 0) {
									if($key1 > 0){
										// blank all other field
										$arr[$counter]['Sl No']                           = '';
										$arr[$counter]['Client Name']                     = '';
										$arr[$counter]['Branch Name']                     = '';
										$arr[$counter]['Region Name']                     = '';
										$arr[$counter]['Zone Name']                       = '';
										$arr[$counter]['Call Receive Date']               = '';
										$arr[$counter]['Call Attend Date']                = '';
										$arr[$counter]['Product']                         = '';
										$arr[$counter]['Product Group']                   = '';
										$arr[$counter]['Product Sl No']                   = '';
										$arr[$counter]['Product Part No']                 = '';
										$arr[$counter]['Product Model No']                = '';
										$arr[$counter]['Nature Of Complaint By Customer'] = '';
										$arr[$counter]['Fault Observation By Engineer']   = '';
										$arr[$counter]['Action Taken By Engineer']        = '';
										$arr[$counter]['Remarks']                         = '';
										$arr[$counter]['Contact Person Name']             = '';
										$arr[$counter]['Contact Person Ph No']            = '';
										$arr[$counter]['Maintenance Type']                = '';
										$arr[$counter]['Complaint No']                    = '';
										$arr[$counter]['Complaint Status']                = '';

									}

								}

								$arr[$counter]['Spare Part Name'] = $value1->spare_part->name;
								$arr[$counter]['Spare Part No']   = $value1->spare_part->part_no;

								if ($value1->spare_part_quantity == 0) {
									$arr[$counter]['Supplied Spare Part Quantity'] = '0';
								} else {
									$arr[$counter]['Supplied Spare Part Quantity'] = $value1->spare_part_quantity;
								}

								if ($value1->spare_part_taken_back == 1) {
									$arr[$counter]['Spare Part Taken Back'] = 'Yes';
								}
								if ($value1->spare_part_taken_back == 0) {
									$arr[$counter]['Spare Part Taken Back'] = 'No';
								}

								if ($value1->spare_part_taken_back_quantity == 0) {
									$arr[$counter]['Spare Part Taken Back Quantity'] = '0';
								} else {
									$arr[$counter]['Spare Part Taken Back Quantity'] = $value1->spare_part_taken_back_quantity;
								}

								if ($value1->unit_price_free == 0) {
									$arr[$counter]['Unit Price Free'] = '0';
								} else {
									$arr[$counter]['Unit Price Free'] = $value1->unit_price_free;
								}

								if ($value1->unit_price_chargeable == 0) {
									$arr[$counter]['Unit Price Chargeable'] = '0';
								} else {
									$arr[$counter]['Unit Price Chargeable'] = $value1->unit_price_chargeable;
								}

								if ($value1->labour_free == 1) {
									$arr[$counter]['Labour Charge'] = 'Free';
								}
								if ($value1->labour_free == 0) {
									$arr[$counter]['Labour Charge'] = 'Chargeable';
								}

								// Check transaction are in the same index print and remove from records

								if (isset($v->dsr_transaction[$key1])) {

									$arr[$counter]['Spare Part Name'] = $value1->spare_part->name;
									$arr[$counter]['Spare Part No']   = $value1->spare_part->part_no;

									if ($value1->spare_part_quantity == 0) {
										$arr[$counter]['Supplied Spare Part Quantity'] = '0';
									} else {
										$arr[$counter]['Supplied Spare Part Quantity'] = $value1->spare_part_quantity;
									}

									if ($value1->spare_part_taken_back == 1) {
										$arr[$counter]['Spare Part Taken Back'] = 'Yes';
									}
									if ($value1->spare_part_taken_back == 0) {
										$arr[$counter]['Spare Part Taken Back'] = 'No';
									}

									if ($value1->spare_part_taken_back_quantity == 0) {
										$arr[$counter]['Spare Part Taken Back Quantity'] = '0';
									} else {
										$arr[$counter]['Spare Part Taken Back Quantity'] = $value1->spare_part_taken_back_quantity;
									}

									if ($value1->unit_price_free == 0) {
										$arr[$counter]['Unit Price Free'] = '0';
									} else {
										$arr[$counter]['Unit Price Free'] = $value1->unit_price_free;
									}

									if ($value1->unit_price_chargeable == 0) {
										$arr[$counter]['Unit Price Chargeable'] = '0';
									} else {
										$arr[$counter]['Unit Price Chargeable'] = $value1->unit_price_chargeable;
									}

									if ($value1->labour_free == 1) {
										$arr[$counter]['Labour Charge'] = 'Free';
									}
									if ($value1->labour_free == 0) {
										$arr[$counter]['Labour Charge'] = 'Chargeable';
									}

									unset($v->dsr_transaction[$key1]);
								}

								$counter++;

							}
							// dump($v->dsr_products->count());
							// dd($key1);
							if (!$v->dsr_transaction->count() && !$v->dsr_products->count()) {
								$counter++;
							}
							if($after_product_count > $counter){
								$counter = $after_product_count;
							}
						}
					endforeach;

					// dd($arr);
					$sheet->fromArray($arr, null, 'A1', false, true);
				});
				$this->setExcelHeader($excel);
			})->download('xlsx');
		}catch(\Exception $e) {
			// dd($e);
			Session::flash('error','Unable to export !');
			return Redirect::back();
		}


        Session::flash('success','Successfully exported client amc details');
        return Redirect::route('view-all-daily-service-report');
    }

    private function setExcelHeader(&$excel) {
        $excel->setCreator("Chandra Enterprise");
        $excel->setLastModifiedBy("Chandra Enterprise");
        $excel->setCompany("Web.Com (India) Pvt. Ltd.");
        $excel->setManager("Web.Com (India) Pvt. Ltd.");
        $excel->setSubject("Chandra Enterprise Excel Export");
        $excel->setKeywords("Chandra Enterprise Excel Export");
        return $excel;
    }

}
