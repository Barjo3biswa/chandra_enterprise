<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SparePartTransaction extends Model
{
	protected $fillable = array('spare_part_master_id','spare_parts_id','description','transaction_date','transaction_type','purchase_quantity','issued_quantity','supplied_quantity','last_transaction_by');

    protected $table    = 'spare_part_transactions';
    protected $guarded  = ['_token','id'];

    // public static $rules = [
    //          'spare_parts_id[]'                            =>  'required',
    //          'issue_quantity[]'                     =>  'required',
    //      ];


    public function user()
	{
	    return $this->belongsTo('App\User', 'last_transaction_by');
	}

	public function spare_part()
	{
	    return $this->belongsTo('App\Models\SparePart', 'spare_parts_id');
	}

	public function spare_part_master()
	{
	    return $this->belongsTo('App\Models\SparePartMaster', 'spare_part_master_id');
	}

}
