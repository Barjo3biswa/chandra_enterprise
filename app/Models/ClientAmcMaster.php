<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Assign\AmcAssignedToEngineers;

class ClientAmcMaster extends Model
{
    protected $fillable = array('client_id','roster_id','amc_start_date','amc_end_date','amc_duration','financial_year','amc_amount');
    protected $table    = 'client_amc_masters';
    protected $guarded  = ['_token','id'];

    public static $rules = [
        'client_id'                         => 'required',
        'roster_id'                    =>  'required',
        'amc_start_date'                  =>  'required',
        'amc_duration'               =>  'required',
		'amc_demand'                      => 'required'
    ];

    public function client()
	{
	    return $this->belongsTo('App\Models\Client', 'client_id');
	}

	public function roster()
	{
	    return $this->belongsTo('App\Models\RosterMaster', 'roster_id');
	}

	public function amc_master_transaction()
	{
	    return $this->hasMany('App\Models\ClientAmcTransaction', 'client_amc_masters_id')->where('status',1);
	}

	public function amc_master_product()
	{
	    return $this->hasMany('App\Models\ClientAmcProduct', 'client_amc_masters_id')->whereHas("product", function($query){
			return $query->where("status", 1);
		})->where('status',1);
	}

	public function amc_bill()
	{
	    return $this->hasMany('App\Models\AmcBillRaise', 'client_amc_masters_id')->where('status',1);
	}

	public function assigned_engineers()
	{
	    return $this->hasMany(AmcAssignedToEngineers::class, 'client_amc_master_id', "id");
	}

}
