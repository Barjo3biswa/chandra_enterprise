<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintTransaction extends Model
{
    protected $fillable = array('complaint_id','transaction_date','transaction_by','transaction_assigned_by','remarks','transaction_remarks');

	protected $table    = 'complaint_transactions';
    protected $guarded  = ['_token'];

    public function user()
	{
	    return $this->belongsTo('App\User', 'transaction_by');
	}
}
