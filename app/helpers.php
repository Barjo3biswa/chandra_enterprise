<?php

use App\Models\ClientAmcMaster;
use App\Models\ClientAmcTransaction;
use App\Models\DailyLog;
use App\Models\Dsr\DailyServiceReport;

function setActive($path)
{
    return Request::is($path . '*') ? ' active' :  '';
}
function getFinacialDate($date = null, $only_year  = false){
    if(!$date){
        $date = time();
    }else{
        $date = strtotime($date);
    }
    $monthOfThis = date("m", $date);
    $yearOfThis  = date("Y", $date);
    if($monthOfThis < 4){
        $firstDate = ($yearOfThis-1)."-04-01";
        $secondDate= ($yearOfThis)."-03-31";
    }else{
        $firstDate = ($yearOfThis)."-04-01";
        $secondDate = ($yearOfThis+1)."-03-31";
    }
    if($only_year){
    	return date("Y", strtotime($firstDate))."-".date("y", strtotime($secondDate));
    }
    return ['first' => $firstDate, 'second' => $secondDate];
}
function orginal_suffix($num){
    $num = $num % 100; // protect against large numbers
    if($num < 11 || $num > 13){
         switch($num % 10){
            case 1: return 'st';
            case 2: return 'nd';
            case 3: return 'rd';
        }
    }
    return 'th';
}
function dateFormat($dateTime, $format = "d-m-Y") {
    if($dateTime == "0000-00-00" || $dateTime == "0000-00-00 00:00:00"){
        return " ";
    }
    $date = strtotime($dateTime);
    if(date('d-m-Y', $date) != '01-01-1970'){
        return date($format, $date);
    }else{
        return " ";
    }
}
function getDuration($duration){
    $mod_duration = $duration%12;
    // dump("mode duration".$mod_duration);
    if($duration < 12){
        return $duration." Month";
    }else if($mod_duration == 0){
        return ($duration/12)." Year";
    }else {
        return (Int)($duration/12)." Year".' '.$mod_duration." Month";
    }
}

function saveLogs($user_id, $username, $guard, $activity) {
    $log = [];
    $log['user_id']   = $user_id;
    $log['username']  = $username;
    $log['guard']     = $guard;
    $log['activity']  = $activity;
    $log['url']       = Request::fullUrl();
    $log['method']    = Request::method();
    $log['ip']        = Request::ip();
    $log['agent']     = Request::header('user-agent');
    DailyLog::create($log);
}
function getFinancialYearList($start_year = 2019){
    $years  = [];
    for ($i=$start_year; $i <= date("Y"); $i++) { 
        $years[] = $i."-".substr($i+1, "-2");
    }
    return $years;
}
function getTotalAmc($assigned_product, $amc_list){
    if(isset($amc_list[$assigned_product->client_id.$assigned_product->product_id])){
        return $amc_list[$assigned_product->client_id.$assigned_product->product_id];
    }
    return 0;
}
function getTotalAmcCompleted($assigned_product, $amc_completed_list){
    if(isset($amc_completed_list[$assigned_product->client_id.$assigned_product->product_id])){
        return $amc_completed_list[$assigned_product->client_id.$assigned_product->product_id];
    }
    return 0;
}
function generateQuarterDate ($date){
    $start_date = "";
    $end_date   = "";
    $date_string = strtotime($date);
    $m           = date("m", $date_string);
    $y           = date("Y", $date_string);
    switch ($m) {
        case ($m >= 4 && $m <=6):
            // first quarter
            $start_date = "01-04-".$y;
            $end_date   = "30-06-".$y;
            break;
        
        case ($m >= 7 && $m <= 9):
            // second Quarter quarter
            $start_date = "01-07-".$y;
            $end_date   = "30-09-".$y;
            break;
        
        case ($m >= 10 && $m <= 12):
            // third Quarter quarter
            $start_date = "01-10-".$y;
            $end_date   = "31-12-".$y;
            break;

        default:
            // fourth Quarter quarter
            $start_date = "01-01-".$y;
            $end_date   = "31-03-".$y;
            break;
    }
    return [
        date("Y-m-d", strtotime($start_date)),
        date("Y-m-d", strtotime($end_date))
    ];
}

function monthlyAMC(){
    // ################### NEW QUERY IMPLIMENTED 16-12-2019 #########################

    $current_month_pending_amcs = [];
    $today = date("Y-m-d");
    list($date_from, $date_to) = generateQuarterDate($today);

    $current_month_pending_amcs = ClientAmcMaster::where(function($query) use ($today){
        return $query->where(function($sub_where) use ($today){
            return $sub_where->where("amc_start_date", "<=", $today)
                ->where("amc_end_date", ">=", $today);
        });
    })
    // added user wise
    ->where(function($where_query){
        $where_query->whereIn("client_id", function($query){
            return $query->select("client_id")
                ->from("assign_engineers")
                ->where("engineer_id", auth()->id())
                ->where("status", 1);
        })
        ->orWhereIn("id", function($select_ids){
            return $select_ids->from("amc_assigned_to_engineers")
                ->select("client_amc_master_id")->where("engineer_id", auth()->id());
        });
    })
    // end of user wise
    ->where("status", 1);
    
    $pending_amc_client_ids = $current_month_pending_amcs->distinct("client_id")
        ->select(["client_id"])
        ->pluck("client_id")
        ->toArray();
    $daily_service_reports = DailyServiceReport::where("status", 1)
    ->with(["dsr_products" => function($select_fields){
        return $select_fields->select(["id", "product_id", "daily_service_report_id"]);
    }])
    ->select(["id", "client_id", "maintenance_type"])
    ->has("dsr_products", ">", 0)
    ->where(function($query) use ($date_from, $date_to){
        return $query->whereDate("entry_datetime", ">=", $date_from)
        ->whereDate("entry_datetime", "<=", $date_to);

    })
    ->whereIn("client_id", $pending_amc_client_ids)
    ->where("maintenance_type", 2)
    ->get();
    // return [$daily_service_reports->toSql(), $daily_service_reports->getBindings()];
    // ################### END NEW QUERY IMPLIMENTED #########################

    $current_month_amc = ClientAmcTransaction::with(['client_master', 'client_master.client','client_master.amc_master_product', 'client_master.amc_master_product.product'])
                                                        ->whereHas('assigned_engineers', function($querry) use ($today){
                                                               return $querry->where('engineer_id',auth()->id())->where('status',1);
                                                        })
                                                        ->where("amc_month", date("F"))
                                                        ->where("amc_year",  date("Y"))
                                                        ->where("status", 1)
                                                        ->where("engineer_status",0)
                                                        ->get();


    // $current_month_amc = ClientAmcTransaction::with([
    //     'client_master', 'client_master.client', 
    //     'client_master.amc_master_product', 'client_master.amc_master_product.product'
    // ])->whereHas("client_master", function($client_master_query) use ($daily_service_reports, $today){
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
    //     })->where(function($current_month_pending_amcs) use ($daily_service_reports){
    //         if($daily_service_reports->count()){
    //             // single product assigned to single client so client id filter not added. 
    //             // product id filter is works like client 
    //             foreach($daily_service_reports as $dsr){
    //                 $current_month_pending_amcs->orWhere(function($orWhereQuery) use ($dsr){
    //                     foreach($dsr->dsr_products as $dsr_product){
    //                         return $orWhereQuery->whereDoesntHave("amc_master_product", function($amc_product_query) use ($dsr_product){
    //                             return $amc_product_query->where("product_id", $dsr_product->product_id);
    //                         });
    //                     }
    //                 });
    //             }
    //         }
    //     })->where(function($sub_where) use ($today){
    //         return $sub_where->where("amc_start_date", "<=", $today)
    //             ->where("amc_end_date", ">=", $today);
    //     });
    // })
    // ->where(function($date_query)  use ($date_from, $date_to){
    //     return $date_query->whereDate("amc_rqst_date",">=", $date_from)
    //     ->where("amc_rqst_date", "<=", $date_to);
    // })
    // // ->where("amc_month", date("F"))
    // // ->where("amc_year",  date("Y"))
    // ->where("engineer_status",0)
    // ->where("status", 1);
   
    return $current_month_amc;
}

function sendSMSNew($mobile_no, $message, $template_id = null)
{
    if (env("OTP_DRIVER") == "log") {
        \Log::info(["message" => $message, "mobile_no" => $mobile_no]);
        return true;
    }
   
    $url = "http://api.pinnacle.in/index.php/sms/send/";
    // $app_name = env('APP_NAME');
    $message = urlencode($message);
    
    if(strlen($mobile_no) > 10){
        $mobile_no = $mobile_no;
    }elseif(strlen($mobile_no) == 10){
        $mobile_no = "91" .$mobile_no;
    }
    $sender_id_new="CHAENS";
    $curl_url = $url.$sender_id_new."/".$mobile_no."/".$message."/TXT?apikey=5d25e6-3a770a-dee216-c4bdd6-74a26e&dltentityid=1101620290000070618&dlttempid=".$template_id;
    $smsInit = curl_init($curl_url);
     
    curl_setopt($smsInit, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($smsInit);
    // dd($res);
    \Log::info("SMS Sending to: ".$mobile_no);
    \Log::info("Message: ".$message);
    \Log::info($res);
    return $res;
}