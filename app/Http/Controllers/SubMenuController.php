<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session,DB,Crypt,Validator,Excel;
use App\Models\Menu, App\Models\SubMenu;

class SubMenuController extends Controller
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
        $smenus = SubMenu::with('menu')->where('status',1)->orderBy('id','desc')->get();
        return view('admin.submenu.index',compact('smenus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menus = Menu::where('status',1)->get();
        return view('admin.submenu.create',compact('menus'));
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
            'menu_id'                  =>  'required',
            'name'                      =>  'required',
            'route'                      =>  'required',
         ];

         $messages = [
            'menu_id.required'  =>'Please select one menu from the list',
            'name.required'  =>'Sub menu name is required',
            'route.required'  =>'Sub menu url is required',
         ];
         
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                Session::flash('error', 'Please fix the error and try again!');
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $data = $request->all();
            
            SubMenu::create($data);
      }catch(ValidationException $e)
            {
                return Redirect::back();
            }

        Session::flash('success','Successfully added sub menu deatils');
        return redirect()->route('view-all-sub-menues');
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

    public function export(Request $request) 
    {
        $smenus = SubMenu::with('menu')->where('status',1)->orderBy('id','desc')->get();


        try{
            Excel::create('SubMenuDetails '.date('dmyHis'), function( $excel) use($smenus){
                $excel->sheet('SubMenu-Details ', function($sheet) use($smenus){
                  $sheet->setTitle('SubMenu-Details');

                  $sheet->cells('A1:C1', function($cells) {
                    $cells->setFontWeight('bold');
                  });
                  
                  $arr = [];
                    foreach($smenus->chunk(100) as $res):
                        foreach( $res as $k => $v) {
                            //dd($v);
                            $arr[$k]['Sl No']                   = $k+1;
                            $arr[$k]['Menu Name']              = $v->menu->name;
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

        Session::flash('success','Successfully exported sub menu details');
        return Redirect::route('view-all-sub-menues');
    }
}
