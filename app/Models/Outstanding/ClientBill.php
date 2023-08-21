<?php

namespace App\Models\Outstanding;

use Illuminate\Database\Eloquent\Model;

class ClientBill extends Model
{
    // protected $fillable = array('');
	protected $table    = 'client_bills';
    protected $guarded  = ['_token','id'];

    public static $rules = [
        'client_id'                         => 'required',
        'company_id'                    =>  'required',
        'group_id'                  =>  'required',
        'bill_no'                        =>  'required|unique:client_bills',
        'bill_date'               =>  'required',
		'bill_amount'                      => 'required',
        'pay_by_date'                          =>  'required',
     ];

    public function bill_transaction() {
		return $this->hasMany("App\Models\Outstanding\ClientBillTransaction", "client_bill_id")->where("status", 1);
	}

	public function engg_bill_follow() {
		return $this->hasMany("App\Models\Outstanding\EngineerBillFollowUp", "client_bill_id")->where("status", 1);
	}

	public function client() {
		return $this->belongsTo("App\Models\Client", "client_id")->where("status", 1);
	}

	public function company() {
		return $this->belongsTo("App\Models\Company", "company_id")->where("status", 1);
	}

	public function group() {
		return $this->belongsTo("App\Models\Group", "group_id")->where("status", 1);
	}
}
