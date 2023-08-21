<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintMaster extends Model
{
	protected $fillable = array('group_id','complaint_details','remarks');

	protected $table    = 'complaint_masters';
    protected $guarded  = ['_token'];
}
