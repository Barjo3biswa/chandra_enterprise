<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = array('name','route');
	protected $table    = 'menus';
    protected $guarded  = ['_token'];

    public function submenu()
	{
		return $this->hasMany('App\Models\SubMenu');
	}

}
