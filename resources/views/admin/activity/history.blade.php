@extends('layouts.layout')
@section('content')
<!-- End of Topbar -->
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Content Row -->
    <div class="row counters">
        <!-- Content Column -->
        <div class="col-12 mb-4">
            <!-- Project Card Example -->
            <div class="card shadow  mb-4">
                @if (Session::has('sucmessage'))
                <div class="alert alert-success"  style="font-size:12px; text-align:center;" role="alert">
                    {{ Session::get('sucmessage') }}
                </div>
                @endif
                @if (Session::has('message'))
                <div class="alert alert-danger" style="font-size:12px; text-align:center;" role="alert">
                    {{ Session::get('message') }}
                </div>
                @endif
                <div class="card-header py-3 d-flex flex-row align-items-center">
                    <a href="{{ route('activitymanagement')}}"><b>Go Back</b></a>
                </div>
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <span><b>Lead Date:</b> {{ $lead_counts[0]->lead_date ? date('d/m/Y H:i', strtotime($lead_counts[0]->lead_date)) : "" }}</span>
                    <span><b>Organization:</b> {{ $lead_counts[0]->organisation }}</span>
                    <span><b>Meetings:</b> {{ $lead_counts[0]->meetings }}</span>
                    <span><b>Calls:</b> {{ $lead_counts[0]->calls }}</span>
                    <span><b>Emails:</b> {{ $lead_counts[0]->emails }}</span>
                    <span><b>Lead Transferred:</b> {{ $lead_counts[0]->transferred }}</span>
                    <span><b>Lead Status Changed:</b> {{ $lead_counts[0]->status }}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <form action="" class="w-100 mb-5" method="POST" id="search-form">
                            @csrf
                            <div class="row" style="width: 100%">
                                <div class="col-md-4">
                                    <input type="date" class="form-control" value="@if(Request::get('history-from')){{Request::get('history-from')}}@endif" name="history-from" id="">
                                </div>
                                <div class="col-md-4">
                                    <input type="date" class="form-control" value="@if(Request::get('history-to')){{Request::get('history-to')}}@endif" name="history-to" id="">
                                </div>
                                <div class="col-md-4">
                                    <input type="submit" class="btn btn-primary" form="search-form" id="">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <br>Activity Type
                                    <select id="activitytype" class="custom-select form-control" name="history-activity">
                                        <option @if(!Request::get('history-activity')) selected @endif value="">All</option>
                                        <?php foreach ($activity_types as $type) { ?>
                                            <option @if(Request::get('history-activity') == $type->id) selected @endif value="{{ $type->id }}" >{{ $type->activity_name }} </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>                            
                        </form>
                        <table class="table" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="white-space: nowrap;">Lead Type</th>
                                    <th style="white-space: nowrap;">Activity Date</th>
                                    <th style="white-space: nowrap;">Activity Type</th>
                                    <th style="white-space: nowrap;">Sales Person</th>
                                    <th style="white-space: nowrap;">Accompanied By</th>
                                    <th>Link</th>
                                    <th style="white-space: nowrap;">Contact Person</th>
                                    <th style="white-space: nowrap;">Mobile number/Landline Number</th>
                                    <th style="white-space: nowrap;">Email</th>
                                    <th style="white-space: nowrap;">State</th>
                                    <th style="white-space: nowrap;">Lead Status</th>
                                    <th style="white-space: nowrap;">Remarks</th>
                                    <th style="white-space: nowrap;">Opportunity Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                foreach ($leads as $lead) {
                                    $lead_type = "";
                                    $link_to = "";
                                    if ($lead->lead_type == 1) {
                                        $lead_type = 'New';
                                    } elseif ($lead->lead_type == 2) { 
                                        $lead_type = 'Existing';
                                    }
                                    if ($lead->link_to == "Nolink") {
                                        $link_to = 'Pre Sales';
                                    } else {
                                        $link_to = $lead->link_to;
                                    }
                                ?>
                                <tr>
                                    <td>
                                        <span> {{ $lead_type }} </span>
                                    </td>
                                    <td>
                                        <span style="white-space: nowrap;"> {{ $lead->from_date ? date('d/m/Y h:i a', strtotime($lead->from_date)) : "" }}</span>
                                    </td>
                                    <td>
                                        <span style="white-space: nowrap;"> {{ $lead->activity_name }} </span>
                                    </td>
                                    <td>
                                        <span style="white-space: nowrap;"> {{ $lead->sales_person }} </span>
                                    </td>
                                    <td>
                                        <span> {{ $lead->accompanied_by_rh }} </span>
                                    </td>
                                    <td>
                                        <span style="white-space: nowrap;"> {{ $link_to }} </span>
                                    </td>
                                    <td>
                                        <span> {{ $lead->first_name }} {{ $lead->last_name }} </span>
                                    </td>
                                    <td>
                                        <span> {{ $lead->contact_number }} </span>
                                    </td>
                                    <td>
                                        <span> {{ $lead->email_id }} </span>
                                    </td>
                                    <td>
                                        <span style="white-space: nowrap;"> {{ $lead->state }} </span>
                                    </td>
                                    <td>
                                        <span>{{ $lead->lead_status }}
                                        </span>
                                    </td>
                                    <td>
                                        <span style="white-space: nowrap;"> {{ $lead->activity_details }} </span>
                                    </td>
                                    <td>
                                        @if($lead->is_opportunity)
                                        {{$lead->opportunity_status}}
                                        @else
                                        N/A
                                        @endif
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
<!-- footer section sttart--->

<!-- footer section end--->
</div>
<!-- End of Main Content -->
</div>
<!-- End of Content Wrapper -->
</div>
<!-- End of Page Wrapper -->
<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
<script>
// A $( document ).ready() block.
$(document).ready(function () {
    $('#dataTable').dataTable({
      aaSorting: [[1, 'desc']]
    });
});
</script>
@endsection
