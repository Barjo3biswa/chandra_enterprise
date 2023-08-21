<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ClientAmcMaster;

class Client extends Model
{
    protected $fillable = array('branch_name','name','zone_id','region_id','email','ph_no','state','district','pin_code','contact_person_1_name','contact_person_1_email','contact_person_1_ph_no','contact_person_2_name','contact_person_2_email','contact_person_2_ph_no','address','isAssignedToEngineer','remarks');

	protected $table    = 'clients';
    protected $guarded  = ['_token'];

    public function zone()
	{
	    return $this->belongsTo('App\Models\Zone', 'zone_id');
	}

	public function region()
	{
	    return $this->belongsTo('App\Models\Region', 'region_id');
	}

	public function state()
	{
	    return $this->belongsTo('App\Models\State', 'state');
	}

	public function district()
	{
	    return $this->belongsTo('App\Models\District', 'district');
	}

	public function assigned_products() {
		return $this->hasMany("App\Models\Assign\AssignProductToClient", "client_id")->where("status", 1);
	}

	public function amc() {
		return $this->hasMany(ClientAmcMaster::class, "client_id");
	}
	public function amc_active() {
		return $this->hasMany(ClientAmcMaster::class, "client_id", "id")->where("status", 1);
	}

}
