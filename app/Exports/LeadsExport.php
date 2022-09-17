<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class LeadsExport implements FromCollection, WithHeadings, WithEvents
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
            'Organization Name',
            'Location',
            'Contact Person',
            'Product Type',
            'Account Type',
            'Last Activity',
            'Contact Detail',
            'Sales Person',
            'Status',
            'Lead Source',
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $index = 0;
        $data = collect($this->data)->map(function($lead, $Index) use($index) {
            if ($lead->lead_status == 1) { 
                $lead_status = "Hot";
            } elseif ($lead->lead_status == 2) { 
                $lead_status = "Warm";
            } elseif ($lead->lead_status == 3) { 
                $lead_status = "Cold";
            } else { 
                $lead_status = "";
            }
            $index = $index + 1;
            return collect([
                ($Index+1),
                $lead->lead_date ? date('d/m/Y h:i a', strtotime($lead->lead_date)) : "",
                $lead->organisation.PHP_EOL.$lead->industry_type,
                $lead->address.PHP_EOL.$lead->city.PHP_EOL.$lead->state,
                $lead->first_name.PHP_EOL.$lead->last_name.PHP_EOL.$lead->designation_name,
                $lead->product_type,
                $lead->account_type,
                $lead->created_time,
                $lead->contact_number.PHP_EOL.$lead->email_id,
                $lead->sales_person,
                $lead_status,
                $lead->source_type.PHP_EOL.$lead->source_value,
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
