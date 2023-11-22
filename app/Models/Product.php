<?php

namespace App\Models;

use App\Models\Assign\AssignProductToClient;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = array('name','product_code','date_of_purchase','serial_no','manufacture_date','warranty','brand','model_no','equipment_no','group_id','subgroup_id','company_id','isAssigned');
	protected $table    = 'products';
    protected $guarded  = ['_token'];


    public function brand()
	{
	    return $this->belongsTo('App\Models\Brand', 'brand_id');
	}

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

    public function getProductCodeAttribute($product_code)
	{
		return (String)$product_code;
	}

    public function getSerialNoAttribute($serial_no)
	{
		return (String)$serial_no;
	}

    public function getModelNoAttribute($model_no)
	{
		return (String)$model_no;
	}

	public function assigned_product_to_client()
	{
		return $this->hasOne(AssignProductToClient::class, 'product_id', 'id')
		->where('status',1);
		// ->orderBy("id", "ASC");
	}
	public function assigned_branch()
	{
		return $this->hasManyThrough(Client::class, AssignProductToClient::class,
			'product_id', // Foreign key on AssignProductToClient table...
			'id', // Foreign key on clients table...
			'id', // Local key on products table...
			'client_id' // Local key on AssignProductToClient table...
		);
	}

	public function newAssigtnedBranch(){
		return $this->hasOne(AssignProductToClient::class, 'product_id', 'id')->where('status',1);
	}
}
