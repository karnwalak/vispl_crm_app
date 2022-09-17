<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class FunnelReportExport implements FromCollection, WithHeadings, WithEvents
{
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }
    public function headings(): array
    {
        return [
            'S.No',
            'Lead Date',
            'Region',
            'Account Manager',
            'Reporting To',
            'Prospect Name',
            'Customer(New/Existing)',
            'Account Type',
            'Contact Person',
            'Mobile No.',
            'Email Id',
            'Designation',
            'Group',
            'Department',
            'Other',
            'Funnel Status',
            'Competitor',
            'Remarks',
            'Product',
            'Product Category',
            'Expected Order Value p.m(In Lakhs)',
            'Status(Hot/Warm/Cold)',
            'Channel partner involved(Yes/No)',
            'Channel Partner name',
            'Total Annual Budget (In Lakhs)',
            'EDC(Expected Date of Closure)',
            'Order Close Value',
            'Funnel Creation Date',
            'Funnel Closure Date',
            'Last meeting date',
            'Next Follow Up Date',
            'Support Required',
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $index = 0;
        $data = collect($this->data)->map(function($activity, $Index) use($index) {
            $support_required = "";
            if ($activity->accompanied_by_sh == 'Yes') {
                $support_required .= "Yes, Sales Head  ";
            } elseif ($activity->accompanied_by_rh == 'Yes') {
                $support_required .= "Yes, Regional Head  ";
            } elseif ($activity->accompanied_by_others == 'Yes') {
                $support_required .= "Yes, Others  ";
            } elseif ($activity->accompanied_by_ph != '') {
                $support_required .= $activity->accompanied_by_ph;
            }
            $index = $index + 1;
            return collect([
                ($Index+1),
                $activity->lead_date ? date('d M Y', strtotime($activity->lead_date)) : "",
                $activity->regionname ?? 'N/A',
                $activity->salesperson,
                $activity->role == 'Member' ? $activity->zonal_admin : $activity->country_head,
                $activity->organisation ?? 'N/A',
                $activity->lead_type ?? 'N/A',
                $activity->account_type?? 'N/A',
                $activity->first_name.' ' .$activity->last_name,                            
                $activity->contact_number,
                $activity->email_id,
                $activity->designation_name,
                $activity->industry_type,
                $activity->department,
                $activity->payment_type,
                $activity->opp_status,
                $activity->competitor_name,                            
                $activity->rm_remarks ? $activity->rm_remarks : $activity->remarks,
                $activity->product_type,
                $activity->subproduct_type,
                $activity->expected_value ? round(($activity->expected_value/100000),2) : "",
                $activity->lead_status,
                $activity->channel_partner,
                $activity->channel_competitor,
                $activity->annual_budget ? round(($activity->annual_budget/100000),2) : "",
                $activity->expected_date_of_closure ? date('d M Y', strtotime($activity->expected_date_of_closure)) : "",
                $activity->closed_value ? round(($activity->closed_value/100000),2) : "",
                $activity->opportunity_creation_date ? date('d/m/Y h:i:s', strtotime($activity->opportunity_creation_date)) : "",
                $activity->opportunity_closure_date ? date('d/m/Y h:i:s', strtotime($activity->opportunity_closure_date)) : "",
                $activity->previous_date ? date('d M Y', strtotime($activity->previous_date)) : "",
                $activity->latest_date ? date('d M Y', strtotime($activity->latest_date)) : "",
                $support_required,
            ]);
        });
        return collect($data);
    }

    public function registerEvents(): array
    {
        return [

            AfterSheet::class    => function(AfterSheet $event) {
                $columns = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", 
                            "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "AA", "AB", "AC", "AD", "AE"];
                foreach ($columns as $column) {
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }

            }
        ];
    }
}
