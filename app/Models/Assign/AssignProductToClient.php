<?php

namespace App\Models\Assign;

use Illuminate\Database\Eloquent\Model;

class AssignProductToClient extends Model
{
    protected $fillable = array('client_id','company_id','product_id','date_of_install');
	protected $table    = 'assign_product_to_client';
    protected $guarded  = ['_token'];

    public function client()
	{
	    return $this->belongsTo('App\Models\Client', 'client_id');
	}

	public function company()
	{
	    return $this->belongsTo('App\Models\Company', 'company_id');
	}

	public function product()
	{
	    return $this->belongsTo('App\Models\Product', 'product_id');
	}

}
