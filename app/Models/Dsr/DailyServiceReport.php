<?php

namespace App\Models\Dsr;

use Illuminate\Database\Eloquent\Model;

class DailyServiceReport extends Model
{
    protected $fillable = array('client_id','contact_person_details','contact_person_name','contact_person_ph_no','maintanance_type','call_receive_date','call_attend_date','product_id','model_no','serial_no','group_id','nature_of_complaint_by_customer','fault_observation_by_engineer','action_taken_by_engineer','remarks','complaint_id','complaint_no','complaint_status','latitude','longitude','location','entry_datetime','entry_by','scr_no');
	protected $table    = 'daily_service_reports';
    protected $guarded  = ['_token','id'];

    public function dsr_transaction() {
		return $this->hasMany("App\Models\Dsr\DailyServiceReportTransaction", "daily_service_report_id")->where("status", 1);
	}

	public function client()
	{
	    return $this->belongsTo('App\Models\Client', 'client_id');
	}

	public function product()
	{
	    return $this->belongsTo('App\Models\Product', 'product_id');
	}

	public function engineer()
	{
	    return $this->belongsTo('App\User', 'entry_by');
	}

	public function complaint()
	{
	    return $this->belongsTo('App\Models\Complaint', 'complaint_id')->where("status", 1);
	}
	public function getScrNoAttribute($value)
	{
		return $value ? $value : 0;
	}

	public function dsr_products()
	{
		return $this->hasMany(DailyServiceReportProduct::class, "daily_service_report_id", "id");
	}
}
