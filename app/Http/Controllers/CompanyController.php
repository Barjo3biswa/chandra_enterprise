<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session,DB,Crypt,Validator,Excel;
use App\Models\Company,App\Models\State,App\Models\District, App\Models\Product;

class CompanyController extends Controller
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
        $companies = Company::where('status',1)->orderBy('id','desc')->get();
        return view('admin.company.index',compact('companies'));
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
        return view('admin.company.create',compact('states','dists'));
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
            // 'email'                     =>  'required',
            // 'ph_no'                     =>  'required',
            // 'state'                     =>  'required',
            // 'district'                  =>  'required',
            // 'pin_code'                  =>  'required',
            // 'address'                   =>  'required',
            // 'gst_no'                    =>  'required',
            // 'pan_card_no'               =>  'required',
            // 'remarks'                   =>  'required',
         ];

         $messages = [
            'name.required'         =>'Company name is required',
            // 'email.required'        =>'Company email is required',
            // 'ph_no.required'        =>'Company phone no is required',
            // 'state.required'        =>'Please select one state for the company',
            // 'district.required'     =>'Please select one district for the company',
            // 'pin_code.required'     =>'Company town pin code is required',
            // 'address.required'      =>'Company address is required',
            // 'gst_no.required'       =>'Company registered gst no is required',
            // 'pan_card_no.required'  =>'Company registered pan card no is required',
            // 'remarks.required'      =>'Company remarks is required',
         ];
         
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                Session::flash('error', 'Please fix the error and try again!');
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $data = $request->all();

            $code = DB::table('companies')->max('unique_no');

            if($code==0){
                $code=1000;
            }else{
                $code= $code+1; 
            }
            $data['unique_no'] = $code;
        
            
            Company::create($data);
      }catch(ValidationException $e)
            {
                return Redirect::back();
            }

        Session::flash('success','Successfully added company deatils');
        return redirect()->route('view-all-company');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $c_id = Crypt::decrypt($id);
        $company = Company::with('state','district')->where('id',$c_id)->where('status',1)->first();
        $products = Product::with('company')->where('company_id',$company->id)->where('status',1)->paginate('20');
        return view('admin.company.show',compact('company','products'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $c_id = Crypt::decrypt($id);
       $company = Company::where('id',$c_id)->where('status',1)->first();
       $states = State::where('status',1)->get();
       $dists = District::where('status',1)->get();
       return view('admin.company.edit',compact('states','dists','company'));

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
       
    try
      {

        $company_id = Crypt::decrypt($id);
        $company = Company::where('id',$company_id)->where('status',1)->first();
         
        $company->name              =$request->name;
        $company->email             =$request->email;
        $company->ph_no             =$request->ph_no;
        $company->state             =$request->state;
        $company->district          =$request->district;
        $company->pin_code          =$request->pin_code;
        $company->address           =$request->address;
        $company->gst_no            =$request->gst_no;
        $company->pan_card_no       =$request->pan_card_no;
        $company->remarks           =$request->remarks;
        $company->save();


      }catch(ValidationException $e)
            {
                return Redirect::back();
            }

        Session::flash('success','Successfully updated company deatils');
        return redirect()->route('view-all-company');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $c_id = Crypt::decrypt($id);
        $company = Company::with('state','district')->where('id',$c_id)->where('status',1)->first();
        $company->status        = 0;
        $company->save();
        Session::flash('success','Successfully deactivated company deatils');
        return redirect()->route('view-all-company');
    }

    public function export(Request $request) 
    {
        $companies = Company::where('status',1)->orderBy('id','desc')->get();


        try{
            Excel::create('CompanyDetails '.date('dmyHis'), function( $excel) use($companies){
                $excel->sheet('Company-Details ', function($sheet) use($companies){
                  $sheet->setTitle('Company-Details');

                  $sheet->cells('A1:L1', function($cells) {
                    $cells->setFontWeight('bold');
                  });
                  
                  $arr = [];
                    foreach($companies->chunk(500) as $res):
                        foreach( $res as $k => $v) {
                            //dd($v);
                            $arr[$k]['Sl No']                   = $k+1;
                            $arr[$k]['Name']                    = $v->name;
                            $arr[$k]['Email']                   = $v->email;
                            $arr[$k]['Phone No']                = $v->ph_no;
                            $arr[$k]['State']                   = $v->state;
                            $arr[$k]['District']                = $v->district;
                            $arr[$k]['Pin code']                = $v->pin_code;
                            $arr[$k]['Address']                 = $v->address;
                            $arr[$k]['GST No']                  = $v->gst_no;
                            $arr[$k]['Pan Card No']             = $v->pan_card_no;
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
