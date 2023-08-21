<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignToolKit extends Model
{
    protected $fillable = array('user_id','tool_kit_id','quantity_to_be_issued','remarks');
	protected $table    = 'assign_tool_kits';
    protected $guarded  = ['_token'];

    public function user()
	{
	    return $this->belongsTo('App\User', 'user_id');
	}

	public function toolkit()
	{
	    return $this->belongsTo('App\Models\ToolKit', 'tool_kit_id');
	}

}
