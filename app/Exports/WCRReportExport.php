<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class WCRReportExport implements FromCollection, WithHeadings, WithEvents
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
            'Region',
            'Activity Date',
            'Activity',
            'Account Name',
            'Account Manager',
            'Person to meet',
            'Designation',
            'Contact Number',
            'Email',
            'Total Annual Budget (In Lakhs)',
            'Expected Order Value p.m(In Lakhs)',
            'Product',
            'Subproduct',
            'Next Steps',
            'Next Follow Up Date',
            'EDC(Expected Date of Closure)',
            'Sales Person Remarks',
            'Accompanied by Regional Head',
            'Accompanied by Sales Head',
            'Accompanied by Product Head',
            'Accompanied by Others',
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $index = 0;
        $data = collect($this->data)->map(function($report, $Index) use($index) {
            $index = $index + 1;
            return collect([
                ($Index+1),
                $report->regionname ?? 'N/A',
                $report->from_date ? date('d M Y h:i:s', strtotime($report->from_date)) : "",
                $report->activity_name ?? 'N/A',
                $report->organisation ?? 'N/A',
                $report->sales_person ?? 'N/A',
                $report->first_name . " " .$report->last_name,
                $report->designation_name ?? 'N/A',
                $report->contact_number ?? 'N/A',
                $report->email_id ?? 'N/A',
                $report->annual_budget ? round(($report->annual_budget/100000),2) : "",
                $report->expected_value ? round(($report->expected_value/100000),2) : "",
                $report->product ?? 'N/A',
                $report->subproduct_type ?? 'N/A',
                $report->next_step ?? 'N/A',
                $report->next_date ? date('d M Y', strtotime($report->next_date)) : "",
                $report->expected_date_of_closure ? date('d M Y', strtotime($report->expected_date_of_closure)) : "",
                $report->activity_details ? $report->activity_details : '',
                $report->accompanied_by_rh ?? 'N/A',
                $report->accompanied_by_sh ?? 'N/A',
                $report->accompanied_by_ph ?? 'N/A',
                $report->accompanied_by_others ?? 'N/A',
            ]);
        });
        return collect($data);
    }

    public function registerEvents(): array
    {
        return [

            AfterSheet::class    => function(AfterSheet $event) {
                $columns = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T"];
                foreach ($columns as $column) {
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }

            }
        ];
    }    
}
