<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = array('name','remarks');
	protected $table    = 'groups';
    protected $guarded  = ['_token'];

    public function products()
    {
        return $this->hasMany(Product::class, "group_id", "id");
    }
}

