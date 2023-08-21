<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = array('state_id','name');
	protected $table    = 'districts';
    protected $guarded  = ['_token'];

    public function state()
	{
	    return $this->belongsTo('App\Models\State', 'state_id');
	}
}
