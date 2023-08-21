<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IssueEngineer extends Model
{
	protected $fillable = array('engineer_id','spare_part_id','stock_in_hand','last_updated_at');
    protected $table    = 'issue_engineers';
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
