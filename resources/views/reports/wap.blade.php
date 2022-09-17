@extends('layouts.layout')
@section('content')
<div class="card">
    <div class="card-body">
        <form name="filter_form" id="filter_form" class="w-100">
            <div class="row">
                <div class="col-md-3">
                    <b>Report Type :</b>
                </div>
                <div class="col-md-8">
                    <input type="radio" name="report-type" onchange="document.getElementById('filter_form').submit();" value="Lead" @if(request('report-type') == "Lead") checked @endif>Lead &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    <input type="radio" name="report-type" onchange="document.getElementById('filter_form').submit();" value="Oppurtunity" @if(request('report-type') == "Oppurtunity") checked @endif>Oppurtunity &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    <input type="radio" name="report-type" onchange="document.getElementById('filter_form').submit();" value="All" @if(request('report-type') == "All" || !request('report-type')) checked @endif>All
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <b>Week Type :</b>
                </div>
                <div class="col-md-8">
                    <input type="radio" name="week-type" onchange="document.getElementById('filter_form').submit();" value="This Week" @if(request('week-type') == "This Week"  || !request('week-type')) checked @endif>This Week &nbsp; &nbsp;
                    <input type="radio" name="week-type" onchange="document.getElementById('filter_form').submit();" value="Next Week" @if(request('week-type') == "Next Week") checked @endif>Next Week &nbsp; &nbsp; &nbsp; &nbsp;
                </div>
            </div>
        </form>
        <hr/>
        <form name="export_form" id="export_form" action="{{ route('export-report')}}" class="w-100 mt-3">
            <input type="hidden" name="report_type" value="wap">
            <div class="row">
                <div class="col-md-3">
                    <input type="radio" @if(request('export-type') == "date") checked @endif name="export-type" id="export-type-date" value="date">&nbsp;<b>Export By Date:</b>
                </div>
                <div class="col-md-8">
                    <label for="From Date">From&nbsp;</label>
                    <input type="date" placeholder="From Date"  value="@if(Request::get('from-export')){{Request::get('from-export')}}@endif" name="from-export" id="from-export">
                    <label for="From Date">To&nbsp;</label>
                    <input type="date" placeholder="To Date"  value="@if(Request::get('to-export')){{Request::get('to-export')}}@endif" name="to-export" id="to-export">
                </div>                
                <div class="col-md-3" style="margin-top:10px">
                    <input type="radio" @if(request('export-type') == "period" || !request('export-type')) checked @endif name="export-type" id="export-type-period" value="period">&nbsp;<b>Export By Period:</b>
                </div>
                <div class="col-md-8" style="margin-top:10px">
                    <input type="radio" name="duration" id="duration3m" value="3Months" @if(request('duration') == "3Months" || !request('duration')) checked @endif>3Months &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    <input type="radio" name="duration" id="duration6m" value="6Months" @if(request('duration') == "6Months") checked @endif>6Months &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    <input type="radio" name="duration" id="duration1y" value="1Year"   @if(request('duration') == "1Year") checked @endif>1Year &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    <button type="submit" class="btn btn-primary exportbutton">Export</button>
                </div>
            </div>
        </form>
        <hr/>
        <div class="row mt-3">
            <div class="table-responsive data-list">
                <table class="table" id="table">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th style="white-space: nowrap;">Region</th>
                            <th style="white-space: nowrap;">Meeting Date</th>
                            <th>Activity</th>
                            <th style="white-space: nowrap;">A/C Manager</th>
                            <th style="white-space: nowrap;">Accompanied by Regional Head</th>
                            <th style="white-space: nowrap;">Accompanied by Sales Head</th>
                            <th style="white-space: nowrap;">Accompanied by Product Head</th>
                            <th style="white-space: nowrap;">Accompanied by Others</th>
                            <th>Account Name</th>
                            <th style="white-space: nowrap;">Customer Type</th>
                            <th style="white-space: nowrap;">Person to meet</th>
                            <th style="white-space: nowrap;">Designation</th>
                            <th style="white-space: nowrap;">Contact Number</th>
                            <th style="white-space: nowrap;">Email</th>
                            <th style="white-space: nowrap;">Sub product</th>
                            <th style="white-space: nowrap;">Total Annual Budget (In Lakhs)</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $activity)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td style="white-space: nowrap;">{{$activity->regionname ?? 'N/A'}}</td>
                            <td style="white-space: nowrap;">{{$activity->followup_date ? date('d M Y', strtotime($activity->followup_date)) : 'N/A'}}</td>
                            <td style="white-space: nowrap;">{{$activity->activity_name}}</td>
                            <td style="white-space: nowrap;">{{$activity->sales_person}}</td>
                            <td style="white-space: nowrap;">{{$activity->accompanied_by_rh ?? 'N/A'}}</td>
                            <td style="white-space: nowrap;">{{$activity->accompanied_by_sh ?? 'N/A'}}</td>
                            <td style="white-space: nowrap;">{{$activity->accompanied_by_ph ?? 'N/A'}}</td>
                            <td style="white-space: nowrap;">{{$activity->accompanied_by_others?? 'N/A'}}</td>
                            <td style="white-space: nowrap;">{{$activity->organisation}}</td>
                            <td style="white-space: nowrap;">{{$activity->link_to ?? 'N/A'}}</td>
                            <td style="white-space: nowrap;">{{$activity->first_name}} {{$activity->last_name}}</td>
                            <td style="white-space: nowrap;">{{$activity->designation_name}}</td>
                            <td style="white-space: nowrap;">{{$activity->contact_number}}</td>
                            <td style="white-space: nowrap;">{{$activity->email_id}}</td>
                            <td style="white-space: nowrap;">{{$activity->subproduct_type}}</td>
                            <td style="text-align: center; white-space: nowrap;">{{$activity->annual_budget ? round(($activity->annual_budget/100000),2) : ""}}</td>
                            <td style="white-space: nowrap;">{!! nl2br(e($activity->activity_details)) !!}</td>
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
    $('#table').dataTable();
 
    var dtToday = new Date();
    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();

    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();

    var maxDate = year + '-' + month + '-' + day;
    $('#from-export').attr('min', maxDate);
    $('#to-export').attr('min', maxDate);
});
$('input[name="export-type"]').click(function () {
    if ($(this).val() == 'date') {
        $('input[name="duration"]').each(function () {
            $(this).prop("checked", false);
        });
        $("#export-type-period").prop("checked", false);
    } else if ($(this).val() == 'period') {
        $('#from-export').val('');
        $('#to-export').val('');
    }
});
$('input[name="duration"]').click(function () {
    $("#export-type-date").prop("checked", false);
    $("#export-type-period").prop("checked", true);
    $('#from-export').removeAttr("required");
    $('#to-export').removeAttr("required");
    $('#from-export').val('');
    $('#to-export').val('');    
});
$('input[name="from-export"]').click(function () {
    $(this).attr("required", "");
    $('#to-export').attr("required", "");
    $("#export-type-period").prop("checked", false);
    $("#export-type-date").prop("checked", true);
    $('input[name="duration"]').each(function () {
        $(this).prop("checked", false);
    });    
});
$('input[name="to-export"]').click(function () {
    $(this).attr("required", "");
    $('#to-export').attr("required", "");
    $("#export-type-period").prop("checked", false);
    $("#export-type-date").prop("checked", true);
    $('input[name="duration"]').each(function () {
        $(this).prop("checked", false);
    });    
});
function daysdifference(firstDate, secondDate){  
    var startDay = new Date(firstDate);  
    var endDay = new Date(secondDate);  
  
// Determine the time difference between two dates     
    var millisBetween = startDay.getTime() - endDay.getTime();  
  
// Determine the number of days between two dates  
    var days = millisBetween / (1000 * 3600 * 24);  
  
// Show the final number of days between dates     
    return Math.round(Math.abs(days));
}
$('.exportbutton').click(function () {
    var days = daysdifference($('#from-export').val(), $('#to-export').val());
    // Check date difference
    if (days > 365) {
        Swal.fire('Please select total tenure less than or equals to 1 Year only!', '','error');
        return false;
    } else {
        return true;
    }
});

</script>
@endsection


