<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dsr\DailyServiceReport;

class ClientAmcProduct extends Model
{
    protected $fillable = array('client_amc_masters_id','product_id');
    protected $table    = 'client_amc_products';
    protected $guarded  = ['_token','id'];

    public static $rules = [
        'product_detail'                         => 'required'
    ];

    public function product()
	{
	    return $this->belongsTo(Product::class, 'product_id');
	}
    public function client_master()
	{
	    return $this->belongsTo(ClientAmcMaster::class, 'client_amc_masters_id');
	}

	public function product_amc_completed()
	{
        // preventive = 2, breakdown = 1
        return $this->belongsTo(DailyServiceReport::class, 'product_id')
            ->where("maintenance_type", 2);
	}
}
