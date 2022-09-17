<?php

namespace App\Jobs;

use App\LeadData;
use App\Mail\ManageActivityMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class ManageActivityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $leadData, $action, $activity_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(LeadData $leadData, $action = null, $activity_id = null)
    {
        $this->leadData = $leadData;
        $this->action = $action;
        $this->activity_id = $activity_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $Email = new ManageActivityMail($this->leadData, $this->action, $this->activity_id);
        Mail::to($this->leadData->salesPerson->email)->send($Email);
    }
}
