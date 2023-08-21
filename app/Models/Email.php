<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable = array('email','status');
    protected $table    = 'emails';
    protected $guarded  = ['_token','id'];
}
