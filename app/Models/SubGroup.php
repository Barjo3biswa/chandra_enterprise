<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubGroup extends Model
{
    protected $fillable = array('group_id','name','remarks');
	protected $table    = 'sub_groups';
    protected $guarded  = ['_token'];

    public function group()
	{
	    return $this->belongsTo('App\Models\Group', 'group_id');
	}



}
