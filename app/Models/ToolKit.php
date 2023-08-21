<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToolKit extends Model
{
    protected $fillable = array('name','quantity_to_be_issued','tool_kit_code','printable_status','remarks');
	protected $table    = 'tool_kits';
    protected $guarded  = ['_token'];
}
