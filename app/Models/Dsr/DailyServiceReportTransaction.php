<?php

namespace App\Models\Dsr;

use Illuminate\Database\Eloquent\Model;

class DailyServiceReportTransaction extends Model
{
    protected $fillable = array('daily_service_report_id','spare_part_id','spare_part_quantity','spare_part_stock_in_hand','spare_part_taken_back','spare_part_taken_back_quantity','unit_price_free','unit_price_chargeable','labour_free','labour_chargeable');
	protected $table    = 'daily_service_report_transactions';
    protected $guarded  = ['_token','id'];

    public function spare_part()
	{
	    return $this->belongsTo('App\Models\SparePart', 'spare_part_id');
	}

}
