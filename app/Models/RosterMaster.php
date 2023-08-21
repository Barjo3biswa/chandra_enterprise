<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RosterMaster extends Model
{
    protected $fillable = array('roster_name','roster_count');
    protected $table    = 'roster_masters';
    protected $guarded  = ['_token','id'];
}
