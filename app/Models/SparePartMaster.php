<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SparePartMaster extends Model
{
    protected $fillable = array('engineer_id','dsr_id','date_of_transaction','purchased_from','remarks','trans_type');
    protected $table    = 'spare_part_masters';
    protected $guarded  = ['_token','id'];

    public function spare_part()
	{
	    return $this->belongsTo('App\Models\SparePart', 'spare_part_id');
	}

	public function user()
	{
	    return $this->belongsTo('App\User', 'engineer_id');
	}

	public function spare_part_transaction()
	{
	    return $this->hasMany('App\Models\SparePartTransaction', 'spare_part_master_id');
	}
	
}
