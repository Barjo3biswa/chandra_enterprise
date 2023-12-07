<?php

namespace App\Models\Assign;

use Illuminate\Database\Eloquent\Model;

class AssignEngineer extends Model
{
    protected $fillable = array('engineer_id','client_id','product_id','zone_id');
	protected $table    = 'assign_engineers';
    protected $guarded  = ['_token'];

    public function user()
	{
	    return $this->belongsTo('App\User', 'engineer_id');
	}

	public function client()
	{
	    return $this->belongsTo('App\Models\Client', 'client_id')->where('status',1);
	}

	public function zone()
	{
	    return $this->belongsTo('App\Models\Zone', 'zone_id');
	}

	
}
