@extends('layouts.layout')
@section('content')
<div class="card">
    <div class="card-body">
        <form id="form" class="w-100">
            <div class="row">
                <div class="col-md-3">
                    <b>Report Type :</b>
                </div>
                <div class="col-md-8">
                    <input type="radio" @if(request('report-type') == "Lead") checked @endif name="report-type" onchange="document.getElementById('form').submit();" value="Lead">Lead &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    <input type="radio" @if(request('report-type') == "Oppurtunity" || !request('report-type')) checked @endif name="report-type" onchange="document.getElementById('form').submit();" value="Oppurtunity">Oppurtunity &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    <input type="radio" @if(request('report-type') == "All") checked @endif name="report-type" onchange="document.getElementById('form').submit();" value="All">All
                </div>
            </div>
        </form>
        <hr/>
        <form id="form" action="{{ route('export-report')}}" class="w-100 mt-3">
            <input type="hidden" name="report_type" value="funnel">
            <div class="row">
                <div class="col-md-3">
                    <input type="radio" @if(request('export-type') == "date") checked @endif name="export-type" id="export-type-date" value="date">&nbsp;<b>Export Type Date:</b>
                </div>
                <div class="col-md-8">
                    <input type="date" placeholder="From Date"  value="@if(Request::get('from-export')){{Request::get('from-export')}}@endif" name="from-export" id="from-export">
                    <input type="date" placeholder="To Date"  value="@if(Request::get('to-export')){{Request::get('to-export')}}@endif" name="to-export" id="to-export">
                </div>
                <div class="col-md-3">
                    <input type="radio" @if(request('export-type') == "period" || !request('export-type')) checked @endif name="export-type" id="export-type-period" value="period">&nbsp;<b>Export Type:</b>
                </div>
                <div class="col-md-8">
                    <input type="radio" @if(request('duration') == "3Months") checked @endif name="duration" id="duration3m" onchange="" value="3Months">3Months &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    <input type="radio" @if(request('duration') == "6Months") checked @endif name="duration" id="duration6m" onchange="" value="6Months">6Months &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    <input type="radio" @if(request('duration') == "1Year") checked @endif name="duration" id="duration1y" onchange="" value="1Year">1Year &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    <input type="radio" @if(request('duration') == "All" || !request('duration')) checked @endif name="duration" id="durationall" onchange="" value="1Year">All&nbsp; &nbsp; &nbsp;
                    <button type="submit" class="btn btn-primary">Export</button>
                </div>
            </div>
        </form>
        <hr/>
        <div class="row mt-3">
            <div class="table-responsive data-list">
                <table class="table" id="table">
                    <thead>
                        <tr>
                            <th style="white-space: nowrap;">Lead Date</th>
                            <th style="white-space: nowrap;">Region</th>
                            <th style="white-space: nowrap;">Account Manager</th>
                            <th style="white-space: nowrap;">Reporting To</th>
                            <th style="white-space: nowrap;">Prospect Name</th>
                            <th style="white-space: nowrap;">Customer(New/Existing)</th>
                            <th style="white-space: nowrap;">Account Type</th>
                            <th style="white-space: nowrap;">Contact Person</th>
                            <th style="white-space: nowrap;">Mobile No.</th>
                            <th style="white-space: nowrap;">Email Id</th>
                            <th style="white-space: nowrap;">Designation</th>
                            <th style="white-space: nowrap;">Group</th>
                            <th style="white-space: nowrap;">Department</th>
                            <th style="white-space: nowrap;">Other</th>
                            <th style="white-space: nowrap;">Funnel Status</th>
                            <th style="white-space: nowrap;">Competitor </th>
                            <th style="white-space: nowrap;">Remarks</th>
                            <th style="white-space: nowrap;">Product</th>
                            <th style="white-space: nowrap;">Product Category</th>
                            <th style="white-space: nowrap;">Expected Order Value p.m(In Lakhs)</th>
                            <th style="white-space: nowrap;">Status(Hot/Warm/Cold)</th>
                            <th style="white-space: nowrap;">Channel partner involved(Yes/No)</th>
                            <th style="white-space: nowrap;">Channel Partner name</th>
                            <th style="white-space: nowrap;">Total Annual Budget (In Lakhs)</th>
                            <th style="white-space: nowrap;">EDC(Expected Date of Closure)</th>
                            <th style="white-space: nowrap;">Order Close Value</th>
                            <th style="white-space: nowrap;">Funnel Creation Date</th>
                            <th style="white-space: nowrap;">Funnel Closure Date</th>
                            <th style="white-space: nowrap;">Last meeting date</th>
                            <th style="white-space: nowrap;">Next Follow Up Date</th>
                            <th style="white-space: nowrap;">Support Required</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $lead)
                        <tr>
                            <td style="white-space: nowrap;">{{$lead->lead_date ? date('d M Y', strtotime($lead->lead_date)) : "" }}</td>
                            <td style="white-space: nowrap;">{{$lead->regionname ?? 'N/A'}}</td>
                            <td style="white-space: nowrap;">{{$lead->salesperson}}</td>
                            <td style="white-space: nowrap;">{{$lead->role == 'Member' ? $lead->zonal_admin : $lead->country_head}}</td>
                            <td style="white-space: nowrap;">{{$lead->organisation ?? 'N/A'}}</td>
                            <td style="white-space: nowrap;">{{$lead->lead_type ?? 'N/A'}}</td>
                            <td style="white-space: nowrap;">{{$lead->account_type?? 'N/A'}}</td>
                            <td style="white-space: nowrap;">{{$lead->first_name}} {{$lead->last_name}}</td>                            
                            <td style="white-space: nowrap;">{{$lead->contact_number}}</td>
                            <td style="white-space: nowrap;">{{$lead->email_id}}</td>
                            <td style="white-space: nowrap;">{{$lead->designation_name}}</td>
                            <td style="white-space: nowrap;">{{$lead->industry_type}}</td>
                            <td style="white-space: nowrap;">{{$lead->department}}</td>
                            <td style="white-space: nowrap;">{{$lead->payment_type}}</td>
                            <td style="white-space: nowrap;">{{$lead->opp_status}}</td>
                            <td style="white-space: nowrap;">{{$lead->competitor_name}}</td>                            
                            <td style="white-space: nowrap;">{{$lead->rm_remarks ? $lead->rm_remarks : $lead->remarks}}</td>
                            <td style="white-space: nowrap;">{{$lead->product_type}}</td>
                            <td style="white-space: nowrap;">{{$lead->subproduct_type}}</td>
                            <td style="text-align: center; white-space: nowrap;">{{$lead->expected_value ? round(($lead->expected_value/100000),2) : ""}}</td>
                            <td style="text-align: center; white-space: nowrap;">{{$lead->lead_status}}</td>
                            <td style="text-align: center; white-space: nowrap;">{{$lead->channel_partner == '' || $lead->channel_partner == 'No' ? 'No' : $lead->channel_partner}}</td>
                            <td style="white-space: nowrap;">{{$lead->channel_competitor}}</td>
                            <td style="text-align: center; white-space: nowrap;">{{$lead->annual_budget ? round(($lead->annual_budget/100000),2) : "N/A"}}</td>
                            <td style="text-align: center; white-space: nowrap;">{{$lead->expected_date_of_closure ? date('d M Y', strtotime($lead->expected_date_of_closure)) : "" }} </td>
                            <td style="white-space: nowrap;">{{$lead->closed_value ? round(($lead->closed_value/100000),2) : "N/A"}}</td>
                            <td style="white-space: nowrap;">{{$lead->opportunity_creation_date ? date('d/m/Y h:i:s', strtotime($lead->opportunity_creation_date)) : "" }}</td>
                            <td style="white-space: nowrap;">{{$lead->opportunity_closure_date ? date('d/m/Y h:i:s', strtotime($lead->opportunity_closure_date)) : ""}}</td>
                            <td style="text-align: center; white-space: nowrap;">{{$lead->previous_date ? date('d M Y', strtotime($lead->previous_date)) : "" }}</td>
                            <td style="text-align: center; white-space: nowrap;">{{$lead->latest_date ? date('d M Y', strtotime($lead->latest_date)) : "" }}</td>
                            <td style="white-space: nowrap;">
                                <span style="white-space: nowrap;">{{ $lead->accompanied_by_sh == 'Yes' ? "Yes, Sales Head" : ""}}</span>
                                <span style="white-space: nowrap;">{{ $lead->accompanied_by_rh == 'Yes' ? "Yes, Regional Head" : ""}}</span>
                                <span style="white-space: nowrap;">{{ $lead->accompanied_by_others == 'Yes' ? "Yes, Others" : ""}}</span>
                                <span style="white-space: nowrap;">{{ $lead->accompanied_by_ph ? $lead->accompanied_by_ph : ""}}</span>                                
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
<script>
$(document).ready(function () {
    $('#table').dataTable({
        "order": []
    });        
});
</script>
@endsection


