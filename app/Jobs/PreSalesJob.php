<?php

namespace App\Jobs;

use App\LeadData;
use App\Mail\PreSalesMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class PreSalesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $leadData;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(LeadData $leadData)
    {
        $this->leadData = $leadData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $Email = new PreSalesMail($this->leadData);
        Mail::to($this->leadData->salesPerson->email)->send($Email);
    }
}
