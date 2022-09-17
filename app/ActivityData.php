<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityData extends Model
{
    protected $table ="activities";

    protected $fillable = [
        'leadid', 'activity_details', 'createdby', 'createdtime', 'activity_type_id', 'sale_person_id',
        'from_date', 'to_date', 'link_to', 'accompanied_by_rh', 'accompanied_by_sh', 'accompanied_by_others',
        'accompanied_by_ph', 'followup_date', 'lead_status', 'opportunity_status', 'followup_time'
    ];

    public $timestamps = false;

    public function lead() {
        return $this->belongsTo(LeadData::class, 'leadid');
    }
}
