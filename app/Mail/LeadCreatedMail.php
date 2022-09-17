<?php

namespace App\Mail;

use App\LeadData;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class LeadCreatedMail extends Mailable
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
        $lead = DB::table('leads')
        ->select('leads.lead_date', 'leads.organisation', 'leads.first_name', 'leads.last_name', 'leads.contact_number',
        'leads.email_id', 'leads.competitor_name', 'leads.lead_details', 'leads.payment_type', 'leads.channel_partner',
        'leads.channel_competitor', 'leads.address', 'leads.created_time', 'leads.sales_person as salesperson',
        'departmentdata.name AS department', 'leads.created_time', 'leads.account_type', 'cities.name AS city',
        'states.name AS state', 'designation.designationname AS designation_name', 'producttype.producttype as product_type',
        'subproducttype.producttype as subproduct_type','users.name as sales_person', 'sourcetype.sourcetype as source_type',
        'sourcevalue.sourcevalue as source_value', 'industrytype.industrytype AS industry_type')
        ->join('departmentdata', 'departmentdata.id', '=', 'leads.department')
        ->join('cities', 'cities.id', '=', 'leads.city_id')
        ->join('states', 'states.id', '=', 'leads.state_id')
        ->join('designation', 'designation.id', '=', 'leads.designation')
        ->join('producttype', 'producttype.id', '=', 'leads.product_type')
        ->join('producttype AS subproducttype', 'subproducttype.id', '=', 'leads.sub_product')
        ->join('users', 'users.id', '=', 'leads.sales_person')
        ->join('industrytype', 'industrytype.id', '=', 'leads.industry_type')
        ->leftJoin('sourcetype', 'sourcetype.id', '=', 'leads.source_type')
        ->leftJoin('sourcevalue', 'sourcevalue.id', '=', 'leads.source_value')
        ->where('leads.id', $this->lead->id)
        ->first();

        // Get Zonal Admin Email
        $zonal_admin = DB::table('users')
                    ->join('users as zonal', function($join) {
                            $join->on('zonal.zone', '=', 'users.zone');
                            $join->where('zonal.role','=', 'Zonal Level Admin');
                        })
                    ->where('users.id', '=', $lead->salesperson)
                    ->first();

        return $this->subject('New Lead Generated')
                ->cc(array($zonal_admin->email, 'priyanka.sharma@vispl.in'))
                ->view('mail.lead-created')
                ->with(['lead' => $lead]);
    }
}
