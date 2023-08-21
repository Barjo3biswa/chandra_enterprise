<?php

namespace App\Models\Outstanding;

use Illuminate\Database\Eloquent\Model;

class ClientBillTransaction extends Model
{
    // protected $fillable = array('');
	protected $table    = 'client_bill_transactions';
    protected $guarded  = ['_token','id'];

    public function client_bill() {
		return $this->belongsTo("App\Models\Outstanding\ClientBill", "client_bill_id")->where("status", 1);
	}

}
