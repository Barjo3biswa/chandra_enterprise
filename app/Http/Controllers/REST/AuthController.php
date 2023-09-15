<?php
namespace App\Http\Controllers\REST;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Assign\AmcAssignedToEngineers;
use DB,Hash,Auth;
use JWTAuth;
use Validator;
use App\User;

use Config;

use App\Models\Complaint, App\Models\Assign\AssignEngineer, App\Models\ClientAmcMaster, App\Models\ClientAmcTransaction, App\Models\Dsr\DailyServiceReport, App\Models\Outstanding\EngineerBillFollowUp;
class AuthController extends Controller
{
/**
* API Login, on success return JWT Auth token
* @param Request $request
* @return JsonResponse
*/

// public function __construct()
// {
//     $this->middleware('jwt.auth',['except' => ['authenticate']);
// }

public function login(Request $request)
{
    $credentials = $request->only('emp_code', 'password');
    $credentials['status'] = 1;

    // return $credentials['user_type'];

    $json_arr = array();
    $rules = [
        'emp_code' => 'required',
        'password' => 'required|min:6',
    ];

    $validator = Validator::make($credentials, $rules);
    if($validator->fails()) {
        return response()->json([
            'status' => false, 
            'message' => $validator->messages()
        ]);
    }
    $emp_cd = $request->emp_code;
    $user_type = 0;
    $emp_user = User::where('emp_code',$emp_cd)->first();
    if($emp_user){
        $user_type = $emp_user->user_type;
    }
    if ($user_type == 2) {
        $credentials['user_type'] = 2;
    }
    if ($user_type == 3) {
        $credentials['user_type'] = 3;
    }
    if ($user_type == 1) {
       return response()->json([
            'status' =>false, 
            'message' => 'We can`t find an account with this credentials'
        ], 200); 
    }
    try {
// Attempt to verify the credentials and create a token for the user
        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'status' =>false, 
                'message' => 'We can`t find an account with this credentials'
            ], 200);
        }
    } catch (JWTException $e) {
// Something went wrong with JWT Auth.
        return response()->json([
            'status' => false, 
            'message' => 'Failed to login, please try again.'
        ], 200);
    }
// All good so return the token

    $user =  JWTAuth::toUser($token);

    if (isset($user)) {

        $engg_tot_closed_complaints = Complaint::where('assigned_to',$user->id)->where('complaint_status',3)->where('status',1)->count();

        if (isset($engg_tot_closed_complaints)) {
            $json_arr['status'] = true;
            $json_arr['engg_tot_closed_complaints'] = $engg_tot_closed_complaints;
        }

        $engg_tot_assigned_complaints = Complaint::where('assigned_to',$user->id)->where('complaint_status','!=',3)->where('status',1)->count();
        if (isset($engg_tot_assigned_complaints)) {
            $json_arr['status'] = true;
            $json_arr['engg_tot_assigned_complaints'] = $engg_tot_assigned_complaints;
        }

        $engg_tot_complaints = Complaint::where('assigned_to',$user->id)->where('status',1)->count();

        if (isset($engg_tot_complaints)) {
            $json_arr['status'] = true;
            $json_arr['engg_tot_complaints'] = $engg_tot_complaints;
        }

        $tot_dsr = DailyServiceReport::where('entry_by',$user->id)->where('status',1)->count();

        if (isset($tot_dsr)) {
            $json_arr['status'] = true;
            $json_arr['tot_dsr'] = $tot_dsr;
        }

        $ass_engg = AssignEngineer::where('engineer_id',$user->id)->where('status',1)->get()->toArray();

// dd($ass_engg);

        $amcs = [];
        if (isset($ass_engg)) {


            foreach ($ass_engg as $key => $clnt_amc) {
                array_push($amcs, $clnt_amc['client_id']);
            }

            if (isset($amcs)) {


                $client_amc1 = ClientAmcMaster::with('client','roster','amc_master_transaction')->whereIn('client_id',$amcs)->where('status',1)->get()->toArray();

                $client_amc = ClientAmcMaster::with('client','roster','amc_master_transaction')->whereIn('client_id',$amcs)->where('status',1)->get();



                if (isset($client_amc)) {
                    $json_arr['status'] = true;
                    $json_arr['client_amc'] = $client_amc;
                }

                $tot_client_amc = ClientAmcMaster::with('client','roster','amc_master_transaction')->whereIn('client_id',$amcs)->where('status',1)->count();

                if ($tot_client_amc) {
                    $json_arr['status'] = true;
                    $json_arr['tot_client_amc'] = $tot_client_amc;
                }
                else{
                    $json_arr['status'] = false;
                    $json_arr['tot_client_amc'] = 0;
                }


                $trans = [];

                //dd($client_amc1);

                // if ($client_amc1 != null) {
                //     foreach ($client_amc1 as $key => $clnt_trns) {
                //         array_push($trans, $clnt_trns['id']);
                //     }


                //     // if (isset($trans)) {
                //         // foreach ($trans as $key => $transaction) {

                //             $amc_trans = ClientAmcTransaction::whereIn('client_amc_masters_id',$trans)->where('status',1)->get()->toArray();

                //             $amc_trans1 = ClientAmcTransaction::whereIn('client_amc_masters_id',$trans)->where('status',1)->get();

                //             // dd($amc_trans);


                //             $amc_month = "";

                //      $today = date('F');

                //      $check_amc_month = ClientAmcTransaction::whereIn('client_amc_masters_id',$trans)->where('amc_month',$today)->where('status',1)->first();

                //     // return($check_amc_month);

                //     if ($check_amc_month) {

                //         $monthly_amcs = ClientAmcTransaction::with('client_master')->where('status',1)->whereIn('client_amc_masters_id',$trans)->where('amc_month',$today)->count();

                //         if (isset($amc_month)) {
                //             $json_arr['status'] = true;
                //             $json_arr['monthly_amcs'] = $monthly_amcs;
                //         }else{
                //             $json_arr['status'] = false;
                //             $json_arr['monthly_amcs'] = 0;
                //         }
                //     }else{
                //         $json_arr['status'] = false;
                //         $json_arr['monthly_amcs'] = 0;
                //     }

                // }else{
                //     $json_arr['monthly_amcs'] = 0;

                // }
                // $eng_assigned_to_amc_count = ClientAmcTransaction::whereHas("client_master", function($client_master_query){
                //     return $client_master_query->where(function($where_query){
                //         $where_query->whereIn("client_id", function($query){
                //             return $query->select("client_id")
                //                 ->from("assign_engineers")
                //                 ->where("engineer_id", auth()->id())
                //                 ->where("status", 1);
                //         })
                //         ->orWhereIn("id", function($select_ids){
                //             return $select_ids->from("amc_assigned_to_engineers")
                //                 ->select("client_amc_master_id")->where("engineer_id", auth()->id());
                //         });
                //     });
                // })
                // ->where("amc_month", date("F"))
                // ->where("amc_year",  date("Y"))
                // ->where("status", 1)
                // ->count();

                $eng_assigned_to_amc_count = ClientAmcTransaction::with(['client_master', 'client_master.client'])
                                                        ->whereHas('assigned_engineers', function($querry) use ($user){
                                                               return $querry->where('engineer_id',auth()->id())->where('status',1);
                                                        })
                                                        ->where("status", 1)
                                                        ->where("engineer_status",0)
                                                        ->count();

                $json_arr['monthly_amcs'] = $eng_assigned_to_amc_count; 
                if($eng_assigned_to_amc_count){
                    $json_arr['status'] = true;
                }else{
                    $json_arr['status'] = false;
                }

            }

        }

        $engg_bill_follow_count = EngineerBillFollowUp::with('client_bill','engineer')->where('engineer_id',$user->id)->where('status',1)->count();

        if ($engg_bill_follow_count) {
           $json_arr['engg_bill_follow_count'] = $engg_bill_follow_count; 
        }else{
           $json_arr['engg_bill_follow_count'] = 0; 
        }

        $user_name = $user->first_name.' '.$user->middle_name.' '.$user->last_name;


        $permissions = DB::table('model_has_permissions')
                        ->leftJoin('permissions','permissions.id','model_has_permissions.permission_id')
                        ->leftJoin('users','users.id','model_has_permissions.model_id')
                        ->where('model_id',$user->id)
                        ->select('permissions.name as permission_name')
                        ->get();

        if ($permissions->count()) {
           $json_arr['permissions'] = $permissions; 
        }else{
           $json_arr['permissions'] = []; 
        }
        saveLogs(auth()->id(), auth()->user()->emp_code, auth()->user()->role, "Logged in");


        $json_arr['status'] = true;
        $json_arr['token']  = $token;
        $json_arr['user']   = $user;
        $json_arr['today']  = date('Y-m-d');
        $json_arr['version'] = (Int)env("APP_VERSION_API");
        //my variable

       

        return response()->json($json_arr);}else{
            return response()->json([
                'status' => false
            ]);
        }
    }

/**
* Logout
* Invalidate the token. User have to relogin to get a new token.
* @param Request $request 'header'
*/
public function logout(Request $request) 
{
// Get JWT Token from the request header key "Authorization"
    $token = $request->header('Authorization');


// Invalidate the token
    try {
        saveLogs(auth()->id(), auth()->user()->emp_code, auth()->user()->role, "Logged out");
        JWTAuth::invalidate($token);
        return response()->json([
            'status' => true, 
            'message'=> "User successfully logged out."
        ]);

    } catch (JWTException $e) {
// something went wrong whilst attempting to encode the token
        return response()->json([
            'status' => false, 
            'message' => 'Failed to logout, please try again.'
        ], 500);
    }
}

}