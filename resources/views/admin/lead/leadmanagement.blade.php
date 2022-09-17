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
                @if (Session::has('failure'))
                <div class="alert alert-danger"  style="font-size:12px; text-align:center;" role="alert">
                    {{ Session::get('failure') }}
                </div>
                @endif
                @if (Session::has('message'))
                <div class="alert alert-danger" style="font-size:12px; text-align:center;" role="alert">
                    {{ Session::get('message') }}
                </div>
                @endif
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h1 class="m-0 font-weight-bold text-primary head-font-size"></h1>
                    <a href="javascript:void(0);" id="openMySidenav" class="btn btn-primary btn-patient-box openMySidenav" role="button">
                        <i class="fas fa-plus fa-sm font-icn-color"></i>
                        Add lead
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <form action="" class="w-100 mb-2" method="POST" id="search-form">
                            @csrf
                            <div class="row" style="width: 100%">
                                <div class="col-md-11">
                                    <label for="from_date">From</label>
                                    <input type="date" placeholder="From date" class="form-control-sm" value="@if(Request::get('from-search')){{Request::get('from-search')}}@endif" required name="from-search" id="from-search">
                                    <label for="to_date" style="margin-left:5px;">To</label>
                                    <input type="date" placeholder="To Date" class="form-control-sm" value="@if(Request::get('to-search')){{Request::get('to-search')}}@endif" required name="to-search" id="to-search">
                                    <label for="status" style="margin-left:5px;">Status</label>
                                    <select class="custom-select-medium" id="table-filter" name="status-filter" onchange="document.getElementById('search-form').submit();">
                                        <option @if(!Request::get('status-filter')) selected @endif value="">All</option>
                                        <option @if(Request::get('status-filter') == 1) selected @endif value="1">Hot</option>
                                        <option @if(Request::get('status-filter') == 2) selected @endif value="2">Warm</option>
                                        <option @if(Request::get('status-filter') == 3) selected @endif value="3">Cold</option>
                                    </select>
                                    <label for="activity_type" style="margin-left:5px;">Product Type</label>
                                    <select class="custom-select-lg" id="product-filter" name="product-filter" onchange="document.getElementById('search-form').submit();">
                                        <option @if(!Request::get('product-filter')) selected @endif value="">All</option>
                                        <?php foreach ($product_types as $type) { ?>
                                        <option value="{{ $type->id }}" {{ $type->id == Request::get('product-filter') ? 'selected' : '' }}>{{ $type->producttype }}</option>
                                        <?php } ?>
                                    </select>
                                    <input type="submit" class="btn btn-primary" style="margin-left: 10px;" form="search-form" name="action" id="submit" value="Submit">
                                    <button type="submit" class="btn btn-primary exportbutton" style="margin-left: 10px;" name="action" id="export" value="Export">Export</button>
                                </div>
                            </div>
                            <div style="margin-top:20px;"></div>
                        </form>
                        <table class="table" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Lead Date</th>
                                    <th>Organization Name</th>
                                    <th>Location</th>
                                    <th>Contact Person</th>
                                    <th>Product Type</th>
                                    <th style="white-space: nowrap;">Account Type</th>
                                    <th>Last Activity</th>
                                    <th>Contact Detail</th>
                                    <th>Sales Person</th>
                                    <th>Status</th>
                                    <th style="white-space: nowrap;">Lead Source</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($leads) {
                                    foreach ($leads as $lead) {
                                        ?>
                                        <tr>
                                            <td style="white-space: nowrap;">{{ $lead->lead_date ? date('d/m/Y h:i a', strtotime($lead->lead_date)) : "" }}</td>
                                            <td style="white-space: nowrap;"><b>{{ $lead->organisation }}</b><br/>{{ $lead->industry_type }}</td>
                                            <td style="white-space: nowrap;"><b>{{ $lead->address }}</b><br/>{{$lead->city}}<br/>{{ $lead->state }}</td>
                                            <td style="white-space: nowrap;"><b>{{ $lead->first_name }} {{ $lead->last_name }}</b><br/>{{ $lead->designation_name }}</td>
                                            <td style="white-space: nowrap;">{{ $lead->product_type }}</td>
                                            <td style="white-space: nowrap;">{{ $lead->account_type }}</td>
                                            <td style="white-space: nowrap;">{{ $lead->created_time }}</td>
                                            <td style="white-space: nowrap;"><b>{{ $lead->contact_number }}</b><br/>{{ $lead->email_id }}</td>
                                            <td style="white-space: nowrap;">{{ $lead->sales_person }}</td>
                                            <td>
                                                <a class="btn confirm-btn  <?php if ($lead->lead_status == 1) { ?> btn-hot <?php } else { ?>btn-primary <?php } ?> btn-sm" href="{{ SITEURL }}admin/leadtatus/{{ $lead->id }}?stauts=1&lead_status={{ $lead->lead_status }}">  Hot </a>
                                                <a class="btn confirm-btn <?php if ($lead->lead_status == 2) { ?> btn-warm <?php } else { ?>btn-primary <?php } ?>  btn-sm" href="{{ SITEURL }}admin/leadtatus/{{ $lead->id }}?stauts=2&lead_status={{ $lead->lead_status }}">  Warm </a>
                                                <a class="btn confirm-btn <?php if ($lead->lead_status == 3) { ?> btn-success <?php } else { ?>btn-primary <?php } ?>  btn-sm" href="{{ SITEURL }}admin/leadtatus/{{ $lead->id }}?stauts=3&lead_status={{ $lead->lead_status }}">  Cold </a>
                                            </td>
                                            <td style="white-space: nowrap;"><b>{{ $lead->source_type }}</b><br/>{{ $lead->source_value }}</td>
                                            <td>
                                                <!-- view lead -->
                                                <a data-toggle="modal" href="#myModal{{ $lead->id }}" class="btn btn-primary icon-btn"><i class="fa fa-eye"></i></a>
                                                <!-- convert lead -->
                                                <?php if ($lead->is_opportunity == '0') { ?>
                                                <a data-toggle="modal" href="#convertModel{{ $lead->id }}" class="btn btn-primary icon-btn">Convert</a>
                                                <?php } ?>
                                                <!-- edit lead -->
                                                <?php if ($lead->status == 'Open') { ?>
                                                <a href="{{ url("/admin/editlead") }}/{{ $lead->id }}" class="btn btn-success icon-btn"><i class="fa fa-edit"></i></a>
                                                <?php } ?>
                                                <!-- duplicate lead -->
                                                <a href="{{ url("/admin/duplicate") }}/{{ $lead->id }}" class="btn btn-primary icon-btn duplicate-btn" id="source{{ $lead->id }}"><i class="fa fa-clone"></i></a>

                                                {{-- view lead--}}
                                                @include('admin.lead.view')

                                                {{-- convert lead--}}
                                                @include('admin.lead.convert')
                                            </td>
                                        </tr>
                                    <?php } ?>
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
<!-- Sidenav-->
{{-- create lead --}}
@include('admin.lead.create')
<!-- Sidenav-->
<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
<script>
$(document).ready(function () {
    $("#channel_partner").change(function () {
        var kdf = $(this).val();
        if (kdf == 'Yes') {
            $('#channekadara').fadeIn();
        } else {
            $('#channekadara').fadeOut();
        }
    });

    $("#state_id").change(function () {
        var kdf = $(this).val();
        var urldata = '{{ SITEURL }}ajaxfiles/getdata.php?action=loadcity&stateid=' + kdf;
        $("#city_id").load(urldata);
    });

    $("#product_type").change(function () {
        var kdf = $(this).val();
        var urldata = '{{ SITEURL }}ajaxfiles/getdata.php?action=loadsubprodct&id=' + kdf;
        $("#sub_product").load(urldata);
    });

    $('#dataTable').DataTable( {
        "order": []
    } );        
});

$('select[name="opp_status"]').change(function () {
    if ($(this).val() != "Win") {
        $("input[name=expected_date_of_closure]").removeAttr('disabled');
        $("input[name=expected_value]").removeAttr('disabled');
        $("input[name=order_closed_date]").attr("disabled", 'disabled');
        $("input[name=closed_value]").attr("disabled", 'disabled');
    }else {
        $("input[name=order_closed_date]").removeAttr('disabled');
        $("input[name=closed_value]").removeAttr('disabled');
        $("input[name=expected_date_of_closure]").attr("disabled", 'disabled');
        $("input[name=expected_value]").attr("disabled", 'disabled');
    }
});

$('#source_type').on('change', function () {
    $.ajax({
        type: "GET",
        url: "{{ route('getSourceValue')}}",
        data: {
            source_type_id: $(this).val(),
        },
        success: function (response) {
            const data = response.data;
            let selectValues = '<option value="">Select the Source Value</option>';
            data.forEach((d) => {
                selectValues = selectValues + `<option value='${d.id}'>${d.sourcevalue}</option>`
            });
            $('#source_value').html(selectValues);
        }
    });
});

$(function(){
    var dtToday = new Date();
    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();

    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();

    var maxDate = year + '-' + month + '-' + day;
    $('#lead_date').attr('max', maxDate);
});

$('.confirm-btn').click(function(e){
    e.preventDefault();
    con = confirm("Are you sure you want to change the status?");
    if(con) {
        window.location.href = $(this).attr('href');
    }
})

$('.duplicate-btn').click(function(e){
    e.preventDefault();
    con = confirm("Are you sure you want to duplicate this lead?");
    if(con) {
        window.location.href = $(this).attr('href');
    }
})

$('.exportbutton').click(function () {
    $('#from-search').removeAttr("required");
    $('#to-search').removeAttr("required");
    document.getElementById('search-form').submit();
});
</script>
@endsection
