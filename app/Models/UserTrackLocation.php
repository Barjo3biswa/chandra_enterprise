<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTrackLocation extends Model
{
    // protected $fillable = array('');
    protected $table   = 'user_track_locations';
    protected $guarded = ['_token', 'id'];

    public static $bulk_insert_rules = [
        "locations"             => "required|array|min:1",
        "locations.*.engineer_id" => "required|exists:users,id",
        "locations.*.latitude"    => "required",
        "locations.*.longitude"   => "required",
        "locations.*.location"    => "nullable",
        "locations.*.track_date"  => "required",
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'engineer_id');
    }

}
