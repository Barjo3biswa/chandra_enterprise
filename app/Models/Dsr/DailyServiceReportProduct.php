<?php

namespace App\Models\Dsr;

use App\Models\Group;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyServiceReportProduct extends Model
{
    use SoftDeletes;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', "updated_at", "deleted_at"];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', "id");
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function dsr_report()
    {
        return $this->belongsTo(DailyServiceReport::class, "daily_service_report_id", "id");
    }
}
