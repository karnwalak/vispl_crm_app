<?php

namespace App\Http\Controllers;

use App\Exports\WAPReportExport;
use App\Exports\WCRReportExport;
use App\Exports\FunnelReportExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\ReportService;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function wapReport(Request $request) {
        $report_type = $request->get('report-type');
        $week_type = $request->get('week-type') ? $request->get('week-type') : "This Week";
        $data = ReportService::wapReport($report_type, $week_type);

        return view('reports.wap')->with(['data' => $data]);
    }

    public function wcrReport(Request $request) {
        $report_type = $request->get('report-type');
        $week_type = $request->get('week-type') ? $request->get('week-type') : "This Week";
        $data = ReportService::wcrReport($report_type, $week_type);

        return view('reports.wcr')->with(['data' => $data]);
    }

    public function funnelReport(Request $request) {
        $report_type = $request->get('report-type') ? $request->get('report-type') : "Oppurtunity";
        $data = ReportService::funnelReport($report_type);

        return view('reports.funnel')->with(['data' => $data]);
    }

    public function exportReport(Request $request) {
        $report_type = $request->get('report-type');
        $week_type = $request->get('week-type');
        $export_type = $request->get('export-type');
        $duration = $request->get('duration');
        $from_export = $request->get('from-export');
        $to_export = $request->get('to-export');

        if($request->get('report_type') == "wap") {
            $data = ReportService::wapReport($report_type, $week_type, $duration, $export_type, $from_export, $to_export);
            return Excel::download(new WAPReportExport($data), 'wap.xlsx');
        } else if($request->get('report_type') == "wcr") {
            $data = ReportService::wcrReport($report_type, $week_type, $duration, $export_type, $from_export, $to_export);
            return Excel::download(new WCRReportExport($data), 'wcr.xlsx');
        } else if($request->get('report_type') == "funnel") {
            $report_type = $report_type ? $report_type : "Oppurtunity";
            $data = ReportService::funnelReport($report_type, $week_type, $duration, $export_type, $from_export, $to_export);
            return Excel::download(new FunnelReportExport($data), 'funnel.xlsx');
        }

    }

    public function exportLeads(Request $request, $data) {
        return Excel::download(new FunnelReportExport($data), 'leads.xlsx');
    }
}
