<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Hash, Crypt, Session;
use App\User;

class ChangePasswordController extends Controller
{
    public function changePassword()
    {
        return view('public.change-password');
    }



   public function changePasswordStore(Request $request)
    {
      
      	if($request->user_id):
    	 $user_id = $request->user_id;

           $user = User::find($user_id);

            if($user):
                $old_password = $request->old_password;

                $new_password = $request->new_password;
                $confirm_password = $request->confirm_password;


                $check = Hash::check($old_password, $user->password); 
 
                if($check):
                    if($new_password == $confirm_password):
                      $user->password= bcrypt($confirm_password);
                       // if($user->password = Crypt::encrypt($confirm_password)):
                      if($user->save()):
                          // $message = "Password Changed !";
                          // return redirect()->back()->with('message', $message);
	                      	Session::flash('success','Successfully Changed Your Password !');
	                      	return redirect()->route('home');
                        else:
                          // $message = "Unable to change password ! Please try again";
                          // return redirect()->back()->with('message', $message);
                        	Session::flash('error','Unable to change password ! Please try again');
	                      	return redirect()->back();
                        endif;
                    else:
                        //  $message = "Password not matched !";
                        // return redirect()->back()->with('message', $message);
                    	Session::flash('error','Password not matched !');
                      	return redirect()->back();
                    endif;
                else:
                    // $message = "Invalid Password !";
                    // return redirect()->back()->with('message', $message);
                	Session::flash('error','Invalid Password !');
                    return redirect()->back();
                endif;
            else:
                // $message = "Invalid User !";
                // return redirect()->back()->with('message', $message);
        		Session::flash('error','Invalid User !');
                return redirect()->back();
            endif;
        else:
            // $message = "User ID is Empty !";
            // return redirect()->back()->with('message', $message);
			Session::flash('error','User ID is Empty !');
            return redirect()->back();
        endif;

       return redirect()->route('home');
    }
}
