@inject('User', 'App\User')
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
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h1 class="m-0 font-weight-bold text-primary head-font-size"></h1>
                    <a href="javascript:void(0);" id="openMySidenav" class="btn btn-primary btn-patient-box openMySidenav" role="button">
                        <i class="fas fa-plus fa-sm font-icn-color"></i>
                        Add Activity
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <form action="" class="w-100 mb-5" method="POST" id="search-form">
                            @csrf
                            <div class="row" style="width: 100%">
                                <div class="col-md-2">
                                    <label>Activity From Date</label>
                                    <input type="date" class="form-control-sm" value="@if(Request::get('from-search')){{Request::get('from-search')}}@endif" name="from-search" id="from-search">
                                </div>
                                <div class="col-md-2">
                                    <label>Activity To Date</label>
                                    <input type="date" class="form-control-sm" value="@if(Request::get('to-search')){{Request::get('to-search')}}@endif" name="to-search" id="to-search">
                                </div>
                                <div class="col-md-2">
                                    <label>Activity Type&nbsp;</label>
                                    <select id="activitytype" class="custom-select-medium" name="activity-filter">
                                        <option @if(!Request::get('activity-filter')) selected @endif value="">All</option>
                                        <?php foreach ($activity_types as $type) { ?>
                                            <option @if(Request::get('activity-filter') == $type->id) selected @endif value="{{ $type->id }}" >{{ $type->activity_name }} </option>
                                        <?php } ?>
                                    </select>                                    
                                </div>
                                <div class="col-md-2">
                                    <label>Lead Type&nbsp;</label>
                                    <select id="lead-filter" class="custom-select-medium" name="lead-filter">
                                        <option @if(!Request::get('lead-filter')) selected @endif value="">All</option>
                                        <option @if(Request::get('lead-filter') == '0') selected @endif value="0">Leads</option>
                                        <option @if(Request::get('lead-filter') == '1') selected @endif value="1">Opportunities</option>
                                    </select>                                    
                                </div>
                                @if(Gate::check('isCountryHead') || Gate::check('isZonalLevelAdmin') || Gate::check('isStateLevelAdmin') || Gate::check('isMember'))
                                <div class="col-md-2">
                                    <label>Employee Name</label>
                                    <div style="overflow: scroll; height:120px;" id="manager-wrapper">
                                    <?php foreach ($sales_person as $manager) { ?>
                                        <div style="height:20px">
                                            <input type="checkbox" name="managers[]" value="{{$manager->id}}" <?php if ($filtered_managers) { foreach ($filtered_managers as $user) { if($user == $manager->id) { ?>checked<?php }}}?>>
                                            <label for="manager{{$manager->id}}">{{$manager->name}}</label>
                                        </div>                                    
                                    <?php } ?>
                                    </div>
                                </div>
                                @endif                                
                                <div class="col-md-1">
                                    <input type="submit" class="btn btn-primary" form="search-form" id="">
                                </div>
                            </div>
                        </form>
                        <table class="table" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="white-space: nowrap;">Activity Date</th>
                                    <th style="white-space: nowrap;">Next Follow-up date</th>
                                    <th style="white-space: nowrap;">Next Follow-up Activity</th>                                    
                                    <th>Assignee</th>                                    
                                    <th style="white-space: nowrap;">Lead date</th>
                                    <th>Organisation name</th>
                                    <th>Location</th>
                                    <th style="white-space: nowrap;">Contact Person</th>
                                    <th style="white-space: nowrap;">Product Type</th>
                                    <th style="white-space: nowrap;">Account Type</th>
                                    <th style="white-space: nowrap;">Contact Details</th>
                                    <th>Status</th>
                                    <th style="white-space: nowrap;">Lead Source</th>
                                    <th style="white-space: nowrap;">Manage Activity</th>
                                    <th>History</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($leads) {
                                    foreach ($leads as $lead) {
                                        $lead_status = "";
                                        $lead_type = "";

                                        if ($lead->lead_status == 1) {
                                            $lead_status = 'Hot';
                                        } else if ($lead->lead_status == 2) {
                                            $lead_status = 'Warm';
                                        } else if ($lead->lead_status == 3) {
                                            $lead_status = 'Cold';
                                        }
                                        if ($lead->lead_type == 1) {
                                            $lead_type = 'New';
                                        } elseif ($lead->lead_type == 2) {
                                            $lead_type = 'Existing';
                                        }
                                        ?>
                                        <tr>
                                            <td>
                                                <span style="white-space: nowrap;">{{ $lead->from_date ? date('d/m/Y h:i a', strtotime($lead->from_date)) : "" }}</span>
                                            </td>                                            
                                            <td data-sort='YYYYMMDD'>
                                                <span style="white-space: nowrap;">{{ $lead->latest_date ? date('d/m/Y', strtotime($lead->latest_date)) : "" }}</span>
                                            </td>
                                            <td>
                                                <span style="white-space: nowrap;">{{ $lead->activity_name }}</span>
                                            </td>
                                            <td>
                                                <span>{{ $lead->salesperson }}</span>
                                            </td>                                            
                                            <td>
                                                <span style="white-space: nowrap;">{{ $lead->lead_date ? date('d/m/Y h:i a', strtotime($lead->lead_date)) : "" }}</span>
                                            </td>
                                            <td>
                                                <span class="first-bold" style="white-space: nowrap;">{{ $lead->organisation }}</span>
                                                <span>{{ $lead->industry_type }}</span>
                                            </td>
                                            <td>
                                                <span style="white-space: nowrap;" class="first-bold">{{ $lead->address }}</span>
                                                <span>{{$lead->city}}</span><br/>
                                                <span>{{ $lead->state }}</span>
                                            </td>
                                            <td>
                                                <span class="first-bold" style="white-space: nowrap;">{{ $lead->first_name }} {{ $lead->last_name }}</span>
                                                <span style="white-space: nowrap;">{{ $lead->designation_name }}</span>
                                            </td>
                                            <td>
                                                <span style="white-space: nowrap;">{{ $lead->product_type }}</span>
                                            </td>
                                            <td>
                                                <span style="white-space: nowrap;">{{ $lead->account_type }}</span>
                                            </td>
                                            <td>
                                                <span class="first-bold" style="white-space: nowrap;">{{ $lead->contact_number }}</span>
                                                <span>{{ $lead->email_id }}</span>
                                            </td>
                                            <td>
                                                <span>@if($lead->status == 'Open') {{ $lead->status }} @else <b>{{ $lead->status }}</b> @endif</span>
                                            </td>
                                            <td>
                                                <span class="first-bold">{{ $lead->source_type }}</span>
                                                <span>{{ $lead->source_value }}</span>
                                            </td>
                                            <td>
                                                <a data-toggle="modal" @if($lead->status == 'Open') href="#mangeavtivity{{ $lead->id }}" @endif class="btn @if($lead->status == 'Open') btn-primary @else btn-default @endif icon-btn"><i class="fa fa-rocket"></i></a>
                                            </td>
                                            <td>
                                                <a href="{{ url("/admin/history") }}/{{ $lead->id }}" class="btn btn-primary icon-btn"><i class="fa fa-history"></i></a>
                                            </td>
                                            <td>
                                                <a data-toggle="modal" href="#myModal{{ $lead->id }}" class="btn btn-primary icon-btn"><i class="fa fa-eye"></i></a>
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
<?php 
if ($leads) {
    foreach ($leads as $lead) { ?>
        {{-- view activity--}}
        @include('admin.activity.view')

        {{-- manage activity--}}
        @include('admin.activity.manage')
<?php
    }
}
?>
<!-- footer section sttart--->
<!-- footer section end--->
</div>
<!-- End of Main Content -->
</div>
<!-- End of Content Wrapper -->
</div>
<!-- End of Page Wrapper -->
<!-- Sidenav-->
{{-- manage activity--}}
@include('admin.activity.addactivity')
<!-- Sidenav-->

<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
<script>
// A $( document ).ready() block.
$(document).ready(function () {
    $("#channel_partner").change(function () {
        var kdf = $(this).val();
        if (kdf == 'Yes') {
            $('#channekadara').fadeIn();
        } else {
            $('#channekadara').fadeOut();
        }
    });
    $("#product_type").change(function () {
        var kdf = $(this).val();
        var urldata = '{{ SITEURL }}ajaxfiles/getdata.php?action=loadsubprodct&id=' + kdf;
        $("#sub_product").load(urldata);
    });
    $("#state_id").change(function () {
        var kdf = $(this).val();
        var urldata = '{{ SITEURL }}ajaxfiles/getdata.php?action=loadcity&stateid=' + kdf;
        $("#city_id").load(urldata);
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
    $('input[type="radio"]').click(function () {
        if ($(this).attr('name') != 'existing-lead') {
            var inputValue = $(this).attr("value");
            var targetBox = $("." + inputValue);
            $(".selectt").not(targetBox).hide();
            // if input value is Existing
            $('input[name="existing-lead"]').each(function () {
                if (inputValue == "Existing") {
                    $(this).attr("required", "");
                } else {
                    $(this).removeAttr("required");
                }
            });

            $('.New-selectt').each(function () {
                if (inputValue == "New") {
                    $(this).attr("required", "");
                } else {
                    $(this).removeAttr("required");
                }
            });

            $('.Pre-selectt').each(function () {
                if (inputValue == "Nolink") {
                    $(this).attr("required", "");
                } else {
                    $(this).removeAttr("required");
                }
            });

            $(targetBox).show();
        }

    });
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
    $('table.display').DataTable();
    $('#dataTable').dataTable({
        "order": []
    });
});
    $('input[name="leadfollow"]').change(function () {
        const radio_value = $(this).val();
        if (radio_value == "1") {
            $(this).closest('form').first().find('.transfer-to').first().css('display', 'block');
        } else {
            $(this).closest('form').first().find('.transfer-to').first().css('display', 'none');
        }
        if (radio_value == "2") {
            $(this).closest('form').first().find('.resheduled').find('input,select,textarea').attr('required', '');
            $(this).closest('form').first().find('.resheduled').first().css('display', 'block');
        } else {
            $(this).closest('form').first().find('.resheduled').find('input,select,textarea').removeAttr('required', '');
            $(this).closest('form').first().find('.resheduled').first().css('display', 'none');
        }

        if(radio_value == "1" || radio_value == "3") {
            $(this).closest('form').first().find('.comment_area').first().addClass('d-block');
            $(this).closest('form').first().find('.comment_area').find('textarea').first().attr('required', '');
            if(radio_value == "1") {
                $(this).closest('form').first().find('.custom-select').first().attr('required', '');
            }
        } else {
            $(this).closest('form').first().find('.custom-select').first().removeAttr('required', '');
            $(this).closest('form').first().find('.comment_area').find('textarea').first().removeAttr('required', '');
            $(this).closest('form').first().find('.comment_area').first().removeClass('d-block');
            $(this).closest('form').first().find('.comment_area').first().addClass('d-none');
        }
    });
</script>
@endsection
