<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Illuminate\Http\Request;
use Auth,Session;
use Hash, Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function login(Request $request, User $user)
    {
        // $rules = [
        //     'emp_code'              => 'required',
        //     'password'                 => 'required',
        //  ];

        // $messages = [
        //     'emp_code.required'    => 'Employee code is required',
        //     'password.required' => 'Password is required',
        // ];

        // $validator = Validator::make($request->all(), $rules, $messages);

        // if ($validator->fails()) {
        //     Session::flash('error', 'Please fix the error and try again!');
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }

        $emp_code = $request->input('emp_code');
        $password = $request->input('password');

        $userObj  = User::where('emp_code',$emp_code)->first() ;
        if($userObj ){

            $check = Hash::check( $password, $userObj->password); 
            
            if($check==true  && $userObj->role=='super-admin' || $userObj->role=='admin' || $userObj->role=='manager' || $userObj->role=='engineer'){
                if (Auth::attempt(['emp_code' => $emp_code, 'password' => $password])) {
                    Session::flash('success','You have successfully logged in');
                     return redirect()->intended($this->redirectPath()); 
                  }else{
                    Session::flash('error','Please fix the error and try again');
                    return redirect()->back();
                  }

            }else{
                Session::flash('error','Please fix the error and try again');
                return redirect()->back();  
            }

        }else{
             Session::flash('error','Please fix the error and try again');
             return redirect()->back(); 
        }

    }


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
