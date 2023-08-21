<?php

namespace App\Models\Assign;

use App\Models\ClientAmcMaster;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AmcAssignedToEngineers extends Model
{
    use SoftDeletes;
    protected $fillable  = ['client_amc_master_id', 'engineer_id', "remark"];
    protected $table     = 'amc_assigned_to_engineers';
    public static $rules = [
        // "client_amc_master_id" => "required|exists:client_amc_masters,id",
        "engineer_id"          => "required|exists:users,id",
        "remark"               => "max:500"
    ];

    public function amc()
    {
        return $this->belongsTo(ClientAmcMaster::class, "client_amc_master_id");
    }
    public function engineer()
    {
        return $this->belongsTo(User::class, "engineer_id");
    }
}
