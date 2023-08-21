<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\State;
use App\Models\Company;
use Session,DB,Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Contracts\Encryption\DecryptException;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $districts = District::with([
            'state' => function($state_select){
                return $state_select->select(["id","name"]);
            }])
            ->select(["name", "state_id", "id"])
            ->where('status',1)
            ->orderBy('id')
            ->get(); 
        return view('admin.district.index',compact('districts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $states = State::where('status',1)->get();
        return view('admin.district.create',compact('states'));
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
        try
        {
           $rules = [
              'name' =>  'required',           
           ];
  
           $messages = [
              'name.required' =>'District name is required',             
           ];
           
              $validator = Validator::make($request->all(), $rules, $messages); 
              if ($validator->fails()) {
                  Session::flash('error', 'Please fix the error and try again!');
                  return redirect()->back()->withErrors($validator)->withInput();
              } 

              $ch=District::where('name', '=', Input::get('name'), 'and', 'state_id', '=', Input::get('state_id'))->count() > 0;
              if ($ch==null) {
                $data = $request->all();
              District::create($data);
            } else{
                Session::flash('error','District already exist');
                return redirect()->route('add-new-district');
            }

              
        }catch(ValidationException $e)
              {
                  return Redirect::back();
              }
  
          Session::flash('success','Successfully added district details');
          return redirect()->route('view-all-district');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
        $did = Crypt::decrypt($id);
        
        $districts = District::where('status',1)->where('id',$did)->first();
        $states = State::where('status',1)->get();   
        return view('admin.district.edit',compact('states','districts'));
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
        try
        {
  
          $districtid = Crypt::decrypt($id);
          $district = District::where('id',$districtid)->where('status',1)->first();          
          $district->name              =$request->name;        
          $district->state_id             =$request->state_id;       
          $district->save();
  
  
        }catch(ValidationException $e)
              {
                  return Redirect::back();
              }
  
          Session::flash('success','Successfully updated district deatils');
          return redirect()->route('view-all-district');
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
}
