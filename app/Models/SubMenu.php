<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubMenu extends Model
{
    protected $fillable = array('menu_id','name','route');
	protected $table    = 'sub_menus';
    protected $guarded  = ['_token'];

    public function menu()
	{
	    return $this->belongsTo('App\Models\Menu', 'menu_id');
	}
}
