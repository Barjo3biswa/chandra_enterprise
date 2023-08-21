<?php

namespace App\Models;

use App\Models\Assign\ComplaintAssignedToEngineer;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = array('complaint_no', 'complaint_call_date', 'client_id', 'contact_persons_value', 'contact_person_name', 'contact_person_email', 'contact_person_ph_no', 'group_id', 'product_id', 'complaint_master_id', 'not_in_the_list_detail', 'complaint_entry_date', 'complaint_entry_by', 'complaint_status', 'priority', 'complaint_details', 'assigned_to', 'last_updated_date', 'last_updated_remarks', 'last_remarks_by');

    protected $table   = 'complaints';
    protected $guarded = ['_token'];

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id');
    }

    public function group()
    {
        return $this->belongsTo('App\Models\Group', 'group_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function comp_master()
    {
        return $this->belongsTo('App\Models\ComplaintMaster', 'complaint_master_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'complaint_entry_by');
    }

    public function assigned_engineers()
    {
        return $this->hasMany(ComplaintAssignedToEngineer::class, 'complaint_id', 'id');
    }
    public function comp_transaction()
    {
        return $this->hasMany(ComplaintTransaction::class, "complaint_id");
    }

}
