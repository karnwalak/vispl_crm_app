<?php

namespace App\Mail;

use App\LeadData;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class ManageActivityMail extends Mailable
{
    use Queueable, SerializesModels;
    private $lead;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(LeadData $leadData, $action = null, $activity_id = null)
    {
        $this->lead = $leadData;
        $this->action = $action;
        $this->activity_id = $activity_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $lead = DB::table('leads')
        ->select('leads.*', 'activities.id', 'activities.activity_details', 'activities.activity_type_id', 'activities.from_date',
                 'activities.to_date', 'activities.followup_date', 'activitytype.activity_name', 'saleperson.name as sales_person',
                 'closer.name as closed_by'
        )
        ->join('activities', function($join) {
            $join->on('activities.leadid', '=', 'leads.id');
            $join->where('activities.id','=', $this->activity_id);
        })
        ->join('activitytype', 'activitytype.id', '=', 'activities.activity_type_id')
        ->leftJoin('users AS saleperson', 'saleperson.id', '=', 'activities.sale_person_id')
        ->leftJoin('users AS closer', 'closer.id', '=', 'leads.lead_closed_by')
        ->where('leads.id', $this->lead->id)
        ->first();

        // Get Zonal Admin Email
        $zonal_admin = DB::table('users')
                    ->join('users as zonal', function($join) {
                            $join->on('zonal.zone', '=', 'users.zone');
                            $join->where('zonal.role','=', 'Zonal Level Admin');
                        })
                    ->where('users.id', '=', $this->lead->salesperson->id)
                    ->first();
        $View = "";
        $Message = "";
        if($this->action == 3) {
            $View = "mail.lead-closed";
            $Message = "Lead ".$lead->organisation." has been closed.";
        } else if($this->action == 2) {
            $View = "mail.lead-rescheduled";
            $Message = "Lead ".$lead->organisation." has been re-scheduled.";
        } else{
            $View = "mail.lead-transferred";
            $Message = "Lead ".$lead->organisation." has been transferred.";
        }
        return $this->subject($Message)
                ->cc(array($zonal_admin->email, 'priyanka.sharma@vispl.in'))
                ->view($View)
                ->with(['lead' => $lead]);
    }
}
