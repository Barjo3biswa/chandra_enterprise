<?php

namespace App\Http\Controllers;

use App\Models\Assign\ComplaintAssignedToEngineer;
use Illuminate\Http\Request;

use App\Models\Complaint, App\Models\ComplaintMaster, App\Models\ComplaintTransaction, App\Models\Client, App\Models\Group, App\Models\Product, App\Models\Zone, App\Models\Assign\AssignEngineer, App\User, App\Models\Assign\AssignProductToClient, App\Models\Email;

use DB,Crypt,Session,Auth,Validator,Mail,Excel;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // $complaints = Complaint::with('client')->where('status',1)->orderBy('id','desc')->get();
        $clients = Client::where('status',1)->groupBy('name')->get();
        $zones = Zone::where('status',1)->get();
        $groups = Group::where('status',1)->get();
        $c_masters = ComplaintMaster::where('status',1)->orderBy('id','desc')->get();
        $products = Product::where('status',1)->groupBy('name')->get();


        $complaints = Complaint::with('client', 'group','product','comp_master','user', "assigned_engineers");

        if($request->from_date) {
                $complaints = $complaints->whereDate('complaint_entry_date', '>=', date('Y-m-d', strtotime($request->from_date)));
            }

            if($request->to_date) {
                $complaints = $complaints->whereDate('complaint_entry_date', '<=', date('Y-m-d', strtotime($request->to_date)));
            }

            if($request->zone_id) {

                $z_id = Client::where('zone_id',$request->zone_id)->first();

                if($z_id){
                   $complaints = $complaints->where('client_id','like','%'.$z_id->id.'%'); 
                }

                
            }

           
            if($request->client_id) {

                $client_names = Client::select('id')->where('name','like','%'.$request->client_id.'%')->where('status',1)->get()->toArray();
                // dd($client_name);
                $client = [];
                foreach ($client_names as $key => $client_name) {
                    array_push($client, $client_name['id']);
                }

                foreach ($client_name as $key => $value) {
                   
                    $complaints = $complaints->whereIn('client_id',$client);
                    
                }
                // dd($complaints);

             }

            

            if($request->branch) {
                $branch = Client::where('branch_name','like','%'.$request->branch.'%')->first()->id;

                $complaints = $complaints->where('client_id','like','%'.$branch.'%');
            }

            if($request->complaint_no) {
                $complaints = $complaints->where('complaint_no','like','%'.$request->complaint_no.'%');
            }

            if($request->priority) {
                $complaints = $complaints->where('priority','like','%'.$request->priority.'%');
            }

            if ($request->complaint_status) {
                $complaints = $complaints->where('complaint_status','like','%'.$request->complaint_status.'%');
            }

            if($request->group_id) {
                $complaints = $complaints->where('group_id','like','%'.$request->group_id.'%');
            }

            if($request->complaint_master_id) {
                $complaints = $complaints->where('complaint_master_id','like','%'.$request->complaint_master_id.'%');
            }

            if($request->product_id) {

                // dd($request->product_id);
                $complaints = $complaints->where('product_id','like','%'.$request->product_id.'%');
            }

            if (Auth::user()->user_type == 1 || Auth::user()->user_type == 0) {
                $results = $complaints->orderBy('complaint_entry_date', 'DESC')->where('status',1)->get();
            }
            if(Auth::user()->user_type == 3 || Auth::user()->user_type == 2){
                $results = $complaints->where('assigned_to',Auth::user()->id)->where('complaint_status','!=',3)->orderBy('complaint_entry_date', 'DESC')->where('status',1)->get();
            }

            // dd($results);

            
        return view('admin.complaint.index',compact('results','clients','zones','groups','c_masters','products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function closedComplaint()
    {
        $closed_comp = Complaint::with('client', 'group','product','comp_master','user')->where('assigned_to',Auth::user()->id)->where('complaint_status',3)->orderBy('complaint_entry_date', 'DESC')->where('status',1)->get();

        return view('engineer.complaint.closed_complaint',compact('closed_comp'));
    }


    public function create()
    {
        $clients = Client::where('status',1)->groupBy('name')->get();
        $c_masters = ComplaintMaster::where('status',1)->get();
        $groups = Group::where('status',1)->get();

       
        $product_details = DB::table('products')
                    ->leftJoin('assign_product_to_client','assign_product_to_client.product_id','products.id')
                    //->where('products.group_id',$value->id)
                    ->where('products.status',1)
                    ->select('products.id as id','products.name as name','products.brand as brand','products.product_code as product_code','products.model_no as model_no','products.serial_no as serial_no','products.brand as brand','products.equipment_no as equipment_no','assign_product_to_client.date_of_install as date_of_install')
                    ->get();
   
        return view('admin.complaint.create',compact('clients','c_masters','groups','product_details'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $aaa = $request->contact_person_details;
        // $ex = explode("#",$aaa);
        // $contact_person_name = $ex[0];
        // $contact_person_email = $ex[1];
        // $contact_person_ph_no = $ex[2];

        // dd($contact_person_name , $contact_person_email , $contact_person_ph_no);

        try
        {

        $rules = [

             'client_id'                        =>  'required',
             'contact_person_name'                     =>  'required',
             'contact_person_details'                   =>  'required',
             // 'product_id'                               =>  'required',
             // 'contact_person_email'                     =>  'required',
             // 'contact_person_ph_no'                     =>  'required',
         ];

         $messages = [
            'client_id.required'                =>'Client name is required',
            'contact_person_name.required'             =>'Contact person name is required',
            'contact_person_details.required'             =>'Contact person details is required',
            // 'product_id.required'             =>'Product details is required',
            // 'contact_person_email.required'             =>'Contact person email is required',
            // 'contact_person_ph_no.required'             =>'Contact person phone no is required',
         ];
         
          // dd($request->all());

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                Session::flash('error', 'Please fix the error and try again!');
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $data = $request->all();

            $code = DB::table('complaints')->max('complaint_no');

            date_default_timezone_set('Asia/Kolkata');

            if($code==0){
                $code=001;
            }else{
                $code= $code+1; 
            }

            $new_code = 'com'.'/'.date('dmyHis').'/'.$code;
            $data['complaint_no'] = $new_code;

            if($request->input('complaint_call_date') != ''){
                $req_date = $request->input('complaint_call_date');
                $tdr = str_replace("/", "-", $req_date);
                $new_mnf_dt = date('Y-m-d',strtotime($tdr));
            }
            else
            $new_mnf_dt = " ";

            $data['complaint_call_date'] =  $new_mnf_dt;

            // $contact_person_details = $request->contact_person_details;
            // $ex = explode("#",$contact_person_details);
            // $contact_person_name = $ex[0];
            // $contact_person_email = $ex[1];
            // $contact_person_ph_no = $ex[2];

            // $data['contact_person_name'] = $contact_person_name;
            // $data['contact_person_email'] = $contact_person_email;
            // $data['contact_person_ph_no'] = $contact_person_ph_no;

            $client_name = $request->client_id;
            $branch = $request->branch;
            $client_id = Client::where('branch_name',$branch)->where('name',$client_name)->first()->id;
            $data['client_id'] = $client_id;
            // dd($client_id);
            $contact_person_details = $request->contact_person_details;
            if ($contact_person_details == 1) {
                 $data['contact_persons_value'] = 1;
                 $data['contact_person_name'] = $request->contact_person_name;
                 $data['contact_person_email'] = $request->c_p_1_email;
                 $data['contact_person_ph_no'] = $request->c_p_1_ph_no;
            }

            if ($contact_person_details == 2) {
                 $data['contact_persons_value'] = 2;
                 $data['contact_person_name'] = $request->c_p_2_name;
                 $data['contact_person_email'] = $request->c_p_2_email;
                 $data['contact_person_ph_no'] = $request->c_p_2_ph_no;
            }
           
            $data['complaint_details'] = $request->complaint_details;
            $data['complaint_status'] = 1;

            if ($data['complaint_status'] == 1) {
                $last_updated_remarks = 'Complaint lodged';
            }
            if ($data['complaint_status'] == 2) {
                $last_updated_remarks = 'Complaint under-process';
            }
            if ($data['complaint_status'] == 3) {
                $last_updated_remarks = 'Complaint closed';
            }

            $data['last_updated_remarks'] = $last_updated_remarks;
            $data['last_remarks_by'] = Auth::user()->id;
            $today = date("Y-m-d H:i:s");
            $data['complaint_entry_date'] = $today;
            $data['complaint_entry_by'] = Auth::user()->id;
            $data['not_in_the_list_detail'] = $request->not_in_the_list_detail;
            $complaint = Complaint::create($data);
            $comp_transaction['complaint_id']      = $complaint->id;
            $comp_transaction['transaction_date']  = $complaint->complaint_entry_date;
            $comp_transaction['transaction_by']      = $complaint->complaint_entry_by;

            if ($complaint->complaint_status == 1) {
                $c_status = 'Complaint lodged';
            }
            if ($complaint->complaint_status == 2) {
                $c_status = 'Complaint under-process';
            }
            if ($complaint->complaint_status == 3) {
                $c_status = 'Complaint closed';
            }
            $comp_transaction['remarks']      = $c_status;
            $comp_transaction['transaction_remarks']      = $c_status;
            ComplaintTransaction::create($comp_transaction);

            // dd($comp_transaction);
            if ($request->c_p_1_email != null) {
                Mail::send('mails.complaint-success', $data, function($message) use($data) {
                $message->to($data['contact_person_email']);
                $message->subject('Complaint registered');
                });
            }
            if ($request->c_p_2_email != null) {
                Mail::send('mails.complaint-success', $data, function($message) use($data) {
                $message->to($data['contact_person_email']);
                $message->subject('Complaint registered');
                });
            }
            $message = "Thank you for contacting Chandra Enterprises. Your complaint is registered and the complaint no is ".$new_code.". Our engineer will visit your place as early as possible.-Chandra Enterprises";
            if($request->c_p_1_ph_no !=""){
                sendSMSNew($request->c_p_1_ph_no, $message, "1107169046500951055");
            }
            if($request->c_p_2_ph_no !=""){
                sendSMSNew($request->c_p_1_ph_no, $message, "1107169046500951055");
            }

      }catch(ValidationException $e)
            {
                return Redirect::back();
            }

            // $data['comp_enrty_by'] = User::where('id',$complaint->complaint_entry_by)->first();

           

            // if ($data['contact_person_email'] != null) {
            //     Mail::send('mails.complaint-success', $data, function($message) use($data) {
            //     $message->to($data['contact_person_email']);
            //     $message->subject('Complaint registered');
            //     });
            // }

            

        Session::flash('success','Successfully added complaint deatils');
        return redirect()->route('view-all-complaints');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $complaint_id = Crypt::decrypt($id);

       $complaint_details = DB::table('complaints')
                ->leftJoin('clients','clients.id','complaints.client_id')
                ->leftJoin('groups','groups.id','complaints.group_id')
                ->leftJoin('products','products.id','complaints.product_id')
                ->leftJoin('complaint_masters','complaint_masters.id','complaints.complaint_master_id')
                ->leftJoin('users','users.id','complaints.complaint_entry_by')

                ->leftJoin('zones','zones.id','clients.zone_id')
                ->leftJoin('companies','companies.id','products.company_id')

                ->leftJoin('assign_product_to_client','assign_product_to_client.product_id','complaints.product_id')
                ->where('complaints.id',$complaint_id)
                ->where('complaints.status',1)

                ->select('complaints.*','complaint_masters.complaint_details as m_complaint_details','users.first_name as first_name','users.middle_name as middle_name','users.last_name as last_name','clients.name as c_name','clients.branch_name as c_branch_name','zones.name as z_name','clients.email as c_email','clients.ph_no as c_ph_no','clients.address as c_address','clients.remarks as c_remarks','products.name as p_name','groups.name as g_name','products.serial_no as p_serial_no','products.product_code as p_product_code','products.model_no as p_model_no','products.brand as p_brand','companies.name as company_name','assign_product_to_client.date_of_install as date_of_install')

                ->first();

        // dd($complaint_details);
        $assigned_engg_details = DB::table('complaints')
                        ->leftJoin('users','users.id','complaints.assigned_to')
                        ->where('complaints.id',$complaint_id)
                        ->where('complaints.status',1)
                        ->select('complaints.*','users.first_name as f_name','users.middle_name as m_name','users.last_name as l_name','users.email as email','users.ph_no as ph_no','users.emp_code as emp_code','users.emp_designation as designation','users.role as role')
                        ->first();

        // dd($assigned_engg_details);



        $complaint_trans = DB::table('complaint_transactions')

                        ->leftJoin('assign_engineers','complaint_transactions.transaction_assigned_by','assign_engineers.engineer_id')

                        ->leftJoin('users','complaint_transactions.transaction_assigned_by','users.id')

                        ->where('complaint_transactions.status',1)
                        ->where('complaint_id',$complaint_details->id)
                        ->select('complaint_transactions.transaction_assigned_by as transaction_assigned_by','users.first_name as first_name','users.middle_name as middle_name','users.last_name as last_name','assign_engineers.zone_id as zone_id','users.email as email','users.ph_no as ph_no','users.emp_code as emp_code','users.emp_designation as emp_designation','users.role as role','complaint_transactions.remarks as remarks','complaint_transactions.transaction_by as transaction_by')
                        ->first();

        // dd($complaint_trans->transaction_by);
        $last_transaction_by = User::where('id',$complaint_trans->transaction_by)->first();

        // dd($last_transaction_by);

      
        $complaint_transaction_details = ComplaintTransaction::with('user')->where('complaint_id',$complaint_details->id)->orderBy('id','desc')->get();

        $closed_complaint = ComplaintTransaction::with('user')->where('complaint_id',$complaint_details->id)->where('status',1)->first();
        $assigned_engineers = ComplaintAssignedToEngineer::whereHas("complaint", function($query) use ($complaint_id){
            return $query->where("id", $complaint_id);
        })
        ->get();

    

        return view('admin.complaint.show',compact('complaint_details','complaint_trans','complaint_transaction_details','closed_complaint','last_transaction_by','assigned_engg_details', 'assigned_engineers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $complaint_id = Crypt::decrypt($id);
        $complaint_details = Complaint::with('client','group','product','comp_master','user')->where('id',$complaint_id)->where('status',1)->first();

        $complaint_branch = DB::table('clients')->leftJoin('complaints','complaints.client_id','clients.id')->where('complaints.id',$complaint_id)->select('clients.branch_name as branch_name','clients.contact_person_1_name as contact_person_1_name','clients.contact_person_1_email as contact_person_1_email','clients.contact_person_1_ph_no as contact_person_1_ph_no','clients.contact_person_2_name as contact_person_2_name','clients.contact_person_2_email as contact_person_2_email','clients.contact_person_2_ph_no as contact_person_2_ph_no')->first();

        $f_branch = DB::table('clients')->where([['status',1],['name',$complaint_details->client->name],['branch_name','!=',$complaint_branch->branch_name ]])->select('branch_name')->get();

      
        $clients = Client::where('status',1)->groupBy('name')->get();
        //dd($clients);
        $c_masters = ComplaintMaster::where('status',1)->get();
        $groups = Group::where('status',1)->get();

        $product_details = DB::table('products')
                        ->leftJoin('assign_product_to_client','assign_product_to_client.product_id','products.id')
                        ->where('products.group_id',$complaint_details->group_id)
                        ->where('products.isAssigned',1)
                        ->where('assign_product_to_client.client_id',$complaint_details->client_id)

                        ->where('products.status',1)

                        ->select('products.id as id','products.name as name','products.brand as brand','products.product_code as product_code','products.model_no as model_no','products.serial_no as serial_no','products.brand as brand','products.equipment_no as equipment_no','assign_product_to_client.date_of_install as date_of_install')
                        ->get();

        return view('admin.complaint.edit',compact('complaint_details','clients','c_masters','groups','complaint_branch','product_details','f_branch'));
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

      // dd($request->all());
        $complaint_id = Crypt::decrypt($id);
        $complaint_details = Complaint::with('client','group','product','comp_master','user')->where('id',$complaint_id)->where('status',1)->first();
        date_default_timezone_set('Asia/Kolkata');

        $rules = [

             'client_id'                        =>  'required',
             'c_p_1_name'                     =>  'required',
             'contact_person_details'                   =>  'required',
             // 'product_id'                               =>  'required',
             // 'contact_person_email'                     =>  'required',
             // 'contact_person_ph_no'                     =>  'required',
         ];

         $messages = [
            'client_id.required'                =>'Client name is required',
            'c_p_1_name.required'             =>'Contact person name is required',
            'contact_person_details.required'             =>'Contact person details is required',
            // 'product_id.required'             =>'Product details is required',
            // 'contact_person_email.required'             =>'Contact person email is required',
            // 'contact_person_ph_no.required'             =>'Contact person phone no is required',
         ];
         
          // dd($request->all());

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                Session::flash('error', 'Please fix the error and try again!');
                return redirect()->back()->withErrors($validator)->withInput();
            }

          $client_name = $request->client_id;
          $branch = $request->branch;
          $client_id = Client::where('branch_name',$branch)->where('name',$client_name)->first()->id;

          $complaint_details->client_id = $client_id;
          

           $contact_person_details = $request->contact_person_details;

            if ($contact_person_details == 1) {
                 $complaint_details->contact_persons_value = 1;
                 $complaint_details->contact_person_name = $request->c_p_1_name;
                 $complaint_details->contact_person_email = $request->c_p_1_email;
                 $complaint_details->contact_person_ph_no = $request->c_p_1_ph_no;
            }

            if ($contact_person_details == 2) {
                 $complaint_details->contact_persons_value = 2;
                 $complaint_details->contact_person_name = $request->c_p_2_name;
                 $complaint_details->contact_person_email = $request->c_p_2_email;
                 $complaint_details->contact_person_ph_no = $request->c_p_2_ph_no;
            }

           $complaint_details->complaint_status = 4;

            if ($complaint_details->complaint_status == 1) {
                $last_updated_remarks = 'Complaint lodged';
            }
            if ($complaint_details->complaint_status == 2) {
                $last_updated_remarks = 'Complaint under-process';
            }
            if ($complaint_details->complaint_status == 3) {
                $last_updated_remarks = 'Complaint closed';
            }
            if ($complaint_details->complaint_status == 4) {
                $last_updated_remarks = 'Complaint updated';
            }

            $complaint_details->last_updated_remarks = $last_updated_remarks;
            $complaint_details->last_remarks_by = Auth::user()->id;


            if($request->input('complaint_call_date') != ''){
                $req_date = $request->input('complaint_call_date');
                $tdr = str_replace("/", "-", $req_date);
                $new_mnf_dt = date('Y-m-d',strtotime($tdr));
            }
            else
            $new_mnf_dt = " ";

           $complaint_details->complaint_call_date =  $new_mnf_dt;
           $complaint_details->group_id = $request->group_id;
           $complaint_details->complaint_master_id = $request->complaint_master_id;
           $complaint_details->product_id = $request->product_id;
           $complaint_details->complaint_details = $request->complaint_details;
           $complaint_details->not_in_the_list_detail = $request->not_in_the_list_detail;

           $complaint_details->save();


           $complaint_transaction = ComplaintTransaction::where('complaint_id',$complaint_details->id)->where('status',1)->first();
           $complaint_transaction->status = 0 ;
           $complaint_transaction->save();



          $comp_transaction = new ComplaintTransaction();



            $comp_transaction->complaint_id      = $complaint_details->id;

            $today = date("Y-m-d H:i:s");
 
            $comp_transaction->transaction_date  = $today;
            $comp_transaction->transaction_by      = $complaint_details->last_remarks_by;

            if ($complaint_details->complaint_status == 1) {
                $c_status = 'Complaint lodged';
            }
            if ($complaint_details->complaint_status == 2) {
                $c_status = 'Complaint under-process';
            }
            if ($complaint_details->complaint_status == 3) {
                $c_status = 'Complaint closed';
            }
            if ($complaint_details->complaint_status ==4) {
                $c_status = 'Complaint updated';
            }
            $comp_transaction->remarks      = $c_status;
            $comp_transaction->transaction_remarks      = $c_status;

            $comp_transaction->save();
            

        Session::flash('success','Successfully updated complaint deatils');

        // return view('admin.complaint.show');
        return redirect()->route('show-complaint-register-details',['id'=>$id]);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $complaint_id = Crypt::decrypt($id);

        $complaint_transaction = ComplaintTransaction::where('complaint_id',$complaint_id)->where('status',1)->update(['status' => 0]);

       
        $complaint_details = Complaint::with('client','group','product','comp_master','user')->where('id',$complaint_id)->update(['status' => '0']);

        Session::flash('success','Successfully deleted complaint deatils');

        return redirect()->route('view-all-complaints');
    }

    public function getBranchName(Request $request)
    {
       $client_id = $request->input('client_id');
       if($client_id){

        $branchname = Client::where('name',$client_id)->where('status',1)->get();
        return response()->json($branchname);

       }
        
    }

    public function getContactPersonDetails(Request $request)
    {
        $branch     = $request->input('branch');
        $client_id  = $request->input('client_id');

        if($branch){
            $c_person_details = Client::where('name',$client_id)
            ->where('branch_name',$branch)
            // whereRaw("BINARY `name`= ?",[$client_id])
            ->has("assigned_products", ">", 0)
            // ->whereRaw("BINARY `branch_name`= ?",[$branch])
            // ->whereNotNull('client_code')
            ->where('status',1)
            ->limit(1)
            ->get();
            return response()->json($c_person_details);
        }
    }

    public function getComplaintMaster(Request $request)
    {
        $group_id = $request->group_id;
        $client_name = $request->client_id;
        $branch = $request->branch;

        $client_id = Client::where('branch_name',$branch)->where('name',$client_name)->first()->id;

        if($group_id){

        $complaint_master_details = ComplaintMaster::where('group_id',$group_id)->where('status',1)->get();
        
        $product_details = DB::table('products')
                        ->leftJoin('assign_product_to_client','assign_product_to_client.product_id','products.id')
                        ->where('products.group_id',$group_id)
                        ->where('products.isAssigned',1)
                        ->where('assign_product_to_client.client_id',$client_id)

                        ->where('products.status',1)

                        ->select('products.id as id','products.name as name','products.brand as brand','products.product_code as product_code','products.model_no as model_no','products.serial_no as serial_no','products.brand as brand','products.equipment_no as equipment_no','assign_product_to_client.date_of_install as date_of_install')
                        ->get();

     
       
        return response()->json(array(
            'complaint_master_details' => $complaint_master_details,
            'product_details' => $product_details,
        ));

       }
    }

    public function assignToEngineer($id)
    {
        $complaint_id = Crypt::decrypt($id);
        // $complaint_details = Complaint::with('client','group','product','comp_master','user')->where('id',$complaint_id)->where('status',1)->first();

        $complaint_details = DB::table('complaints')
                ->leftJoin('clients','clients.id','complaints.client_id')
                ->leftJoin('groups','groups.id','complaints.group_id')
                ->leftJoin('products','products.id','complaints.product_id')
                ->leftJoin('complaint_masters','complaint_masters.id','complaints.complaint_master_id')
                ->leftJoin('users','users.id','complaints.complaint_entry_by')

                ->leftJoin('zones','zones.id','clients.zone_id')
                ->leftJoin('companies','companies.id','products.company_id')

                ->leftJoin('assign_product_to_client','assign_product_to_client.product_id','complaints.product_id')
                ->where('complaints.id',$complaint_id)
                ->where('complaints.status',1)

                ->select('complaints.*','complaint_masters.complaint_details as complaint_details','users.first_name as first_name','users.middle_name as middle_name','users.last_name as last_name','clients.name as c_name','clients.branch_name as c_branch_name','zones.name as z_name','clients.email as c_email','clients.ph_no as c_ph_no','clients.address as c_address','clients.remarks as c_remarks','products.name as p_name','groups.name as g_name','products.serial_no as p_serial_no','products.product_code as p_product_code','products.model_no as p_model_no','products.brand as p_brand','companies.name as company_name','assign_product_to_client.date_of_install as date_of_install')

                ->first();

        $complaint_trans = DB::table('complaint_transactions')

                        ->leftJoin('assign_engineers','complaint_transactions.transaction_assigned_by','assign_engineers.engineer_id')

                        ->leftJoin('users','complaint_transactions.transaction_assigned_by','users.id')
                        ->where('complaint_transactions.status',1)
                        ->select('complaint_transactions.transaction_assigned_by as transaction_assigned_by','users.first_name as first_name','users.middle_name as middle_name','users.last_name as last_name','assign_engineers.zone_id as zone_id','users.email as email','users.ph_no as ph_no','users.emp_code as emp_code','users.emp_designation as emp_designation','users.role as role','complaint_transactions.remarks as remarks')
                        ->first();

        // dd($complaint_details);

        // $zones = Zone::where('status',1)->get();

        $assigned_engineers_zone = AssignEngineer::with('zone','user')->where('status',1)->get();
        //dd($assigned_engineers_zone);
        $users = User::where('user_type',3)->where('status',1)->get();

          
        $all_zones = [];
        foreach ($assigned_engineers_zone as $key => $value) {
            array_push($all_zones, $value['zone_id']);
        }
        $assigned_engineers = ComplaintAssignedToEngineer::whereHas("complaint", function($query) use ($complaint_id){
            return $query->where("id", $complaint_id);
        })
        ->get();
        $zones = Zone::whereIn('id',$all_zones)->where('status',1)->get();

        // dd($zones);
       
        
        return view('admin.complaint.assign-to',compact('complaint_details','assigned_engineers_zone','complaint_trans','zones', "assigned_engineers",'users'));
    }

    public function assignToEngineerUpdate(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $complaint_id = Crypt::decrypt($id);
            $complaint_details = Complaint::with('client','group','product','comp_master','user')->where('id',$complaint_id)->where('status',1)->first();

            date_default_timezone_set('Asia/Kolkata');

            // $complaint_details->priority = $request->priority;
            // $complaint_details->assigned_to = $request->assigned_to;

            $last_updated_date = date("Y-m-d H:i:s");

            $complaint_details->last_updated_date = $last_updated_date; 
            $complaint_details->complaint_status = 2;
            $complaint_details->last_updated_remarks = 'Complaint under-process';
            $complaint_details->last_remarks_by = Auth::user()->id;
            $complaint_details->save();
            $assigned_engineer_create = [
                "engineer_id"   => $request->assigned_to,
                "priority"      => $request->priority,
                "remark"        => $request->get('transaction_remarks'),
            ];
            $complaint_details->assigned_engineers()
                ->where("engineer_id", $request->assigned_to)
                ->delete();
            $complaint_details->assigned_engineers()->create($assigned_engineer_create);
    

            $complaint_transaction = ComplaintTransaction::where('complaint_id',$complaint_details->id)->where('status',1)->first();
            $complaint_transaction->status = 0 ;
            $complaint_transaction->save();


            $comp_trans = new ComplaintTransaction();
            $comp_trans->complaint_id = $complaint_details->id;
            $transaction_date = date("Y-m-d H:i:s");
            $comp_trans->transaction_date = $transaction_date;
            $comp_trans->transaction_by = Auth::user()->id;
            $comp_trans->transaction_assigned_by = Auth::user()->id;
            $comp_trans->remarks = 'Complaint assigned';
            $comp_trans->transaction_remarks = $request->transaction_remarks;
            $comp_trans->save();
        } catch (\Throwable $th) {
            Session::flash('error','Whoops! Something went wrong. please try again later.');
            return redirect()->route('view-all-complaints');
        }
        DB::commit();

        Session::flash('success','Successfully assigned complaint deatils to engineer');
        return redirect()->route('view-all-complaints');

     }

     public function updateComplaintStatus(Request $request, $id)
     {
        $complaint_id = Crypt::decrypt($id);
        $complaint_details = Complaint::with('client','group','product','comp_master','user')->where('id',$complaint_id)->where('status',1)->first();

        date_default_timezone_set('Asia/Kolkata');

        // dd($complaint_details);
        $last_updated_date = date("Y-m-d H:i:s");

        $complaint_details->last_updated_date = $last_updated_date;

        $complaint_details->complaint_status = $request->complaint_status;

        if ($complaint_details->complaint_status == 2) {
            $complaint_details->last_updated_remarks = 'Complaint under-process';
        }
        if ($complaint_details->complaint_status == 3) {
            $complaint_details->last_updated_remarks = 'Complaint closed';
        }
        //$complaint_details->last_updated_remarks = 'Complaint closed';
        $complaint_details->last_remarks_by = Auth::user()->id;
        $complaint_details->save();
  

        $complaint_transaction = ComplaintTransaction::where('complaint_id',$complaint_details->id)->where('status',1)->first();
        $complaint_transaction->status = 0 ;
        $complaint_transaction->save();

        $comp_trans = new ComplaintTransaction();
        $comp_trans->complaint_id = $complaint_details->id;
        $transaction_date = date("Y-m-d H:i:s");
        $comp_trans->transaction_date = $transaction_date;
        $comp_trans->transaction_by = Auth::user()->id;
        // $comp_trans->transaction_assigned_by = Auth::user()->id;

        if ($complaint_details->complaint_status == 2) {
            $comp_trans->remarks = 'Complaint under-process';
        }
        if ($complaint_details->complaint_status == 3) {
            $comp_trans->remarks = 'Complaint closed';
        }
        $comp_trans->transaction_remarks = $request->transaction_remarks;
        $comp_trans->save();

        if($request->complaint_status == 2){
            $data['complaint_status'] = 'Complaint under-process';
        }

        if($request->complaint_status == 3){
            $data['complaint_status'] = 'Complaint closed';
        }

        $data['transaction_date'] = $transaction_date;
        $data['transaction_by'] = User::where('id',$comp_trans->transaction_by)->first();
        $data['remarks'] = $request->transaction_remarks;
        $data['complaint_no'] = $complaint_details->complaint_no;
        $data_to = Email::where('status',1)->select('email')->get();
        $data_cc = Email::where('status',2)->select('email')->get();
        $x = array();
        foreach ($data_to as $key => $value) {
            array_push($x,$value->email);
        }
       $data['email_to'] = $x;

       $y = array();
       foreach ($data_cc as $key => $value) {
           array_push($y,$value->email);
       }
       $data['email_cc'] = $y;
      
        Mail::send('mails.complaint-status', $data, function($message) use($data) {
        $message->to($data['email_to']);
        $message->cc($data['email_cc']);
        $message->subject('Complaint status');
        });
        $message = "Thank you for contacting Chandra Enterprises. Your machine is ready to use. Kindly contact us in future if you face any problem. -Chandra Enterprises";
        if($request->complaint_status == 3){
                sendSMSNew($complaint_details->contact_person_ph_no, $message, "1107169046510599595");
        }
        Session::flash('success','Successfully updated complaint deatils for the engineer');
        return redirect()->route('view-all-complaints');


     }

    public function getEngineerDetails(Request $request)
    {
        $zone_id = $request->input('zone_id');
        if($zone_id){

        $engineer_details = AssignEngineer::with('user')->where('zone_id',$zone_id)->where('status',1)->groupBy('engineer_id')->get();
        return response()->json($engineer_details);

       }
    }

    public function export(Request $request)
    {
        $complaints = Complaint::with('client','group','product','comp_master','user','comp_transaction')->where('status',1)->orderBy('id','desc')->get();


        try{
            Excel::create('ComplaintDetails '.date('dmyHis'), function( $excel) use($complaints){
                $excel->sheet('Complaint-Details ', function($sheet) use($complaints){
                  $sheet->setTitle('Complaint-Details');

                  $sheet->cells('A1:Z1', function($cells) {
                    $cells->setFontWeight('bold');
                  });
                  
                  $arr = [];
                  $counter = 1;
                    foreach($complaints->chunk(500) as $res):
                        // dd($res);
                        foreach( $res as $k => $v) {
                            $arr[$counter]['Sl No']                    = $k+1;
                            $arr[$counter]['Complaint No']             = $v->complaint_no;
                            $arr[$counter]['Client Name']              = $v->client->name;
                                                 

                            $arr[$counter]['Branch Name']              = $v->client->branch_name;
                                                 
                            if($v->client->region_id != null)
                            {
                                $arr[$counter]['Region Name']          = $v->client->region->name;
                            }else{
                                $arr[$counter]['Region Name']          = '';
                            }

                            $arr[$counter]['Zone Name']                = $v->client->zone->name;
                            $arr[$counter]['Group Name']               = $v->group->name;
  
                            if ($v->product_id != null) {
                                $arr[$counter]['Product']                  = $v->product->name;
                                $arr[$counter]['Product Sl No']            = $v->product->serial_no;
                            }else{
                                $arr[$counter]['Product']                  = '';
                                $arr[$counter]['Product Sl No']            = '';
                            }
                            

                            $arr[$counter]['Complaint Call Date']      = dateFormat($v->complaint_call_date);
                            $arr[$counter]['Complaint Entry Date']     = dateFormat($v->complaint_entry_date);

                            $arr[$counter]['Complaint Entry By']       = $v->user->first_name.' '.$v->user->middle_name.' '.$v->user->last_name;

                            $arr[$counter]['Contact Person Name']      = $v->contact_person_name;
                            $arr[$counter]['Contact Person Email']     = $v->contact_person_email;
                            $arr[$counter]['Contact Person Ph No']     = $v->contact_person_ph_no;

                            $arr[$counter]['Complaint Type']           = $v->comp_master->complaint_details;

                            if ($v->not_in_the_list_detail != null) {
                                $arr[$counter]['Not In The List Detail']     = $v->not_in_the_list_detail;
                            }else
                            {
                                $arr[$counter]['Not In The List Detail']     ='';
                            }

                            if ($v->priority == 0) {
                                $priority = 'No Priority';
                            }
                            if ($v->priority == 1) {
                                $priority = 'Low Priority';
                            }
                            if ($v->priority == 2) {
                                $priority = 'High Priority';
                            }
                            $arr[$counter]['Complaint Priority']             = $priority;

                            $arr[$counter]['Complaint Details']              = $v->complaint_details;

                            if ($v->complaint_status == 1 ) {
                                $complaint_status = 'Complaint Lodged';
                            }
                            if ($v->complaint_status == 2 ) {
                                $complaint_status = 'Complaint Under Process';
                            }
                            if ($v->complaint_status == 3 ) {
                                $complaint_status = 'Complaint Closed';
                            }
                            if ($v->complaint_status == 4 ) {
                                $complaint_status = 'Complaint Updated';
                            }
                            $arr[$counter]['Complaint Status']               = $complaint_status;
                            $counter ++;


                            // Transactions
                            // foreach ($v->comp_transaction as $key => $value) {
                            //     if($key > 0){
                                    // blank all other field
                            // $arr[$counter]['Sl No']                          = '';
                            // $arr[$counter]['Complaint No']                   = '';
                            // $arr[$counter]['Client Name']                    = '';
                                                 

                            // $arr[$counter]['Branch Name']                    = '';
                                                 
                            // if($v->client->region_id != null)
                            // {
                            //     $arr[$counter]['Region Name']                = '';
                            // }else{
                            //     $arr[$counter]['Region Name']                = '';
                            // }

                            // $arr[$counter]['Zone Name']                      = '';
                            // $arr[$counter]['Group Name']                     = '';
  
                            // $arr[$counter]['Product']                        = '';
                            // $arr[$counter]['Product Sl No']                  = '';

                            // $arr[$counter]['Complaint Call Date']            = '';
                            // $arr[$counter]['Complaint Entry Date']           = '';

                            // $arr[$counter]['Complaint Entry By']             = '';

                            // $arr[$counter]['Contact Person Name']            = '';
                            // $arr[$counter]['Contact Person Email']           = '';
                            // $arr[$counter]['Contact Person Ph No']           = '';

                            // $arr[$counter]['Complaint Type']                 = '';


                            // if ($v->not_in_the_list_detail != null) {
                            //     $arr[$counter]['Not In The List Detail']     = '';
                            // }else
                            // {
                            //     $arr[$counter]['Not In The List Detail']     ='';
                            // }
                            // $arr[$counter]['Complaint Priority']             = '';
                            // $arr[$counter]['Complaint Details']              = '';
                            // $arr[$counter]['Complaint Status']               = '';
                            // }
                            //     $arr[$counter]['Transaction Date']           = dateFormat($value->transaction_date);                    
                                                 
                            //     $arr[$counter]['Transaction By']                = $value->user->first_name.' '.$value->user->middle_name.' '.$value->user->last_name;
                            //     $arr[$counter]['Remarks']                         = $value->remarks;
                            //     $counter ++;
                            // }
      
                        }
                  
                     endforeach;
                    $sheet->fromArray($arr, null, 'A1', false, true);
                });
                // $this->setExcelHeader($excel);
            })->download('xlsx');
        }
        catch(Exception $e)
        {
            Session::flash('error','Unable to export !');
            return Redirect::back();
        }

        Session::flash('success','Successfully exported complaint details');
        return Redirect::route('view-all-complaints');
    }

}
