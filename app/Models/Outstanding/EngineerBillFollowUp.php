<?php

namespace App\Models\Outstanding;

use Illuminate\Database\Eloquent\Model;

class EngineerBillFollowUp extends Model
{
    // protected $fillable = array('');
	protected $table    = 'engineer_bill_follow_ups';
    protected $guarded  = ['_token','id'];

    public static $rules = [
        'client_id'                         => 'required',
        'client_bill_id'                    =>  'required',
        'next_pay_by_date'                  =>  'required',
        'bill_status'                       =>  'required',
        'bill_remarks'               		=>  'required',
	  ];

    public function client_bill() {
		return $this->belongsTo("App\Models\Outstanding\ClientBill", "client_bill_id")->where("status", 1);
	}

	public function engineer() {
		return $this->belongsTo("App\User", "engineer_id")->where("status", 1);
	}

	public function client() {
		return $this->belongsTo("App\Models\Client", "client_id")->where("status", 1);
	}

}
