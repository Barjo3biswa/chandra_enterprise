<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = array('unique_no','name','email','ph_no','state','district','pin_code','address','gst_no','pan_card_no','remarks');

	protected $table    = 'companies';
    protected $guarded  = ['_token'];

    public function state()
	{
	    return $this->belongsTo('App\Models\State', 'state');
	}

	public function district()
	{
	    return $this->belongsTo('App\Models\District', 'district');
	}
}
