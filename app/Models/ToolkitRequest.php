<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ToolkitRequest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable  = [
        'request_by_id', 'toolkit_id', 'remarks', 
        'issued_by_id', "issued_at", "status", 
        "issued_remarks", "request_for", "client_id",
        "request"
    ];
    public static $rules = [
        "request_by_id" => "required|exists:users,id",
        "toolkit_id"    => "nullable|exists:tool_kits,id",
        "issued_by_id"  => "nullable|exists:users,id",
        "remarks"       => "nullable|max:500",
        "request_for"   => "required|in:spare_part,toolkit"
    ];
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    // protected $guarded = ["id"];
    public function requested_by()
    {
        return $this->belongsTo(User::class, "request_by_id");
    }

    public function issued_by()
    {
        return $this->belongsTo(User::class, 'issued_by_id');
    }

    public function item()
    {
        if($this->toolkit_id){
            return $this->belongsTo(ToolKit::class, 'toolkit_id');
        }
        return $this->belongsTo(SparePart::class, 'toolkit_id');
    }
    public function client(){
        return $this->belongsTo(Client::class);
    }
}
