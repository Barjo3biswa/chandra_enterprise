<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IssueEngineerTransaction extends Model
{
	protected $fillable = array('engineer_sp_trans_id','engineer_id','spare_part_id','description','transaction_date','stock_in','stock_out');
    protected $table    = 'issue_engineer_transactions';
    protected $guarded  = ['_token','id'];

    public function user()
	{
	    return $this->belongsTo('App\User', 'engineer_id');
	}

	public function spare_part()
	{
	    return $this->belongsTo('App\Models\SparePart', 'spare_part_id');
	}
	
}
