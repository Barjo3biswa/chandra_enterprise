<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmcBillRaise extends Model
{
    protected $fillable = array('client_amc_masters_id','bill_name','bill_no','bill_from_date','bill_to_date','bill_date','bill_amount','bill_remarks','paid_on_date','amount_paid','last_follow_up_by','last_follow_up_remarks','last_follow_up_date');
    protected $table    = 'amc_bill_raises';
    protected $guarded  = ['_token','id'];

    public function user()
	{
	    return $this->belongsTo('App\User', 'last_follow_up_by');
	}
}
