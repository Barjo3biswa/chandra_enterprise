<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = array('name','address','remarks');
	protected $table    = 'regions';
    protected $guarded  = ['_token'];
}
