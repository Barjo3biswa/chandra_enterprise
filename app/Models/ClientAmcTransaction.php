<?php

namespace App\Models;

use App\Models\Assign\AmcAssignedToEngineers;
use Illuminate\Database\Eloquent\Model;

class ClientAmcTransaction extends Model
{
    // protected $fillable = array('client_amc_masters_id','amc_month','amc_year','amc_rqst_date','amc_end_date','amc_demand','amc_demand_date','amc_demand_collected','amc_demand_collected_date','amc_status','amc_done_on','engineer_id','remarks','amc_transaction_remarks');
    protected $table    = 'client_amc_transactions';
    protected $guarded  = ['_token','id'];

    public static $rules = [
        'amc_rqst_date'                         => 'required'
    ];

    public function client_master()
	{
	    return $this->belongsTo('App\Models\ClientAmcMaster', 'client_amc_masters_id');
	}

    public function assigned_engineers()
	{
	    return $this->hasMany(AmcAssignedToEngineers::class, 'client_amc_trans_id', "id");
	}

}
