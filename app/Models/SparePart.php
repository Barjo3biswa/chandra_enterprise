<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SparePart extends Model
{
    // protected $fillable = array('sp_code','group_id','subgroup_id','company_id','brand','name','part_no','tech_specification','opening_balance','with_effect_from','transaction_date','last_transaction_by','remarks');
	protected $table    = 'spare_parts';
    protected $guarded  = ['_token','id'];

    public function group()
	{
	    return $this->belongsTo('App\Models\Group', 'group_id');
	}

	public function subgroup()
	{
	    return $this->belongsTo('App\Models\SubGroup', 'subgroup_id');
	}

	public function company()
	{
	    return $this->belongsTo('App\Models\Company', 'company_id');
	}

	public function getBrandAttribute($brand)
	{
		return $brand ?? "";
	}
}
