<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session,DB,Crypt,Validator,Excel;
use App\Models\Menu;

class MenuController extends Controller
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
        
        $menues = Menu::where('status',1)->orderBy('id','desc')->get();
        return view('admin.menu.index',compact('menues'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.menu.create');
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
            'name.required'  =>'Menu name is required',
         ];
         
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                Session::flash('error', 'Please fix the error and try again!');
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $data = $request->all();
            
            Menu::create($data);
      }catch(ValidationException $e)
            {
                return Redirect::back();
            }

        Session::flash('success','Successfully added menu deatils');
        return redirect()->route('view-all-menus');
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
        $m_id = Crypt::decrypt($id);
        $menu = Menu::where('id',$m_id)->where('status',1)->first();
        return view('admin.menu.edit',compact('menu')); 
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
            $m_id = Crypt::decrypt($id);
            $menu = Menu::where('id',$m_id)->where('status',1)->first();

            $rules = [

                'name'                      =>  'required',
             ];

             $messages = [
                'name.required'  =>'Menu name is required',
             ];
             
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                Session::flash('error', 'Please fix the error and try again!');
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $menu->name      = $request->name;
            $menu->save();

        }catch(ValidationException $e)
            {
                return Redirect::back();
            }

        Session::flash('success','Successfully updated menu deatils');
        return redirect()->route('view-all-menus');
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

    public function export(Request $request) 
    {
        $menues = Menu::where('status',1)->orderBy('id','desc')->get();


        try{
            Excel::create('MenuDetails '.date('dmyHis'), function( $excel) use($menues){
                $excel->sheet('Menu-Details ', function($sheet) use($menues){
                  $sheet->setTitle('Menu-Details');

                  $sheet->cells('A1:B1', function($cells) {
                    $cells->setFontWeight('bold');
                  });
                  
                  $arr = [];
                    foreach($menues->chunk(100) as $res):
                        foreach( $res as $k => $v) {
                            //dd($v);
                            $arr[$k]['Sl No']                   = $k+1;
                            $arr[$k]['Name']                    = $v->name;
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

        Session::flash('success','Successfully exported menu details');
        return Redirect::route('view-all-menus');
    }
}
