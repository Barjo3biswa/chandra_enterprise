<?php

namespace App\Models\Assign;

use App\Models\Complaint;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComplaintAssignedToEngineer extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['complaint_id', "engineer_id", "remark"];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ["deleted_at", "updated_at"];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class, 'complaint_id');
    }

    public function engineer()
    {
        return $this->belongsTo(User::class, 'engineer_id', 'id');
    }
}
