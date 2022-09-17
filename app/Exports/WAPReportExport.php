<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class WAPReportExport implements FromCollection, WithHeadings, WithEvents
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
            'Meeting date',
            'Activity',
            'A/C Manager',
            'Accompanied by Regional Head',
            'Accompanied by Sales Head',
            'Accompanied by Product Head',
            'Accompanied by Others',
            'Account Name',
            'Customer Type',
            'Person to meet',
            'Designation',
            'Contact Number',
            'Email Address',
            'Sub product',
            'Total Annual Budget (In Lakhs)',
            'Remarks',
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $index = 0;
        $data = collect($this->data)->map(function($activity, $Index) use($index) {
            $index = $index + 1;
            return collect([
                ($Index+1),
                $activity->regionname ?? 'N/A',
                $activity->followup_date ? date('d M Y', strtotime($activity->followup_date)) : 'N/A',
                $activity->activity_name,
                $activity->sales_person,
                $activity->accompanied_by_rh ?? 'N/A',
                $activity->accompanied_by_sh ?? 'N/A',
                $activity->accompanied_by_ph ?? 'N/A',
                $activity->accompanied_by_others?? 'N/A',
                $activity->organisation,
                $activity->link_to ?? 'N/A',
                $activity->first_name .' ' . $activity->last_name,
                $activity->designation_name,
                $activity->contact_number,
                $activity->email_id,
                $activity->subproduct_type,
                $activity->annual_budget ? round(($activity->annual_budget/100000),2) : "",
                $activity->activity_details,
            ]);
        });
        return collect($data);
    }

    public function registerEvents(): array
    {
        return [

            AfterSheet::class    => function(AfterSheet $event) {
                $columns = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P"];
                foreach ($columns as $column) {
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }

            }
        ];
    }
}
