<?php

namespace App\Mail;

use App\LeadData;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;

class OpportunityCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    private $lead;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(LeadData $leadData)
    {
        $this->lead = $leadData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Get Zonal Admin Email
        $zonal_admin = DB::table('users')
                    ->join('users as zonal', function($join) {
                            $join->on('zonal.zone', '=', 'users.zone');
                            $join->where('zonal.role','=', 'Zonal Level Admin');
                        })
                    ->where('users.id', '=', $this->lead->sales_person)
                    ->first();

        return $this->subject('Lead '.$this->lead->organisation.' Converted Into Opportunity')
                ->cc(array($zonal_admin->email, 'priyanka.sharma@vispl.in'))
                ->view('mail.opportunity-created')
                ->with(['lead' => $this->lead]);
    }
}
