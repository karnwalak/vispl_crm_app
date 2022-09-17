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
                                        <option @if(Request::get('status-filter') == 'Win') selected @endif value="Win">Win</option>
                                        <option @if(Request::get('status-filter') == 'Shelved') selected @endif value="Shelved">Shelved</option>
                                        <option @if(Request::get('status-filter') == 'Lost') selected @endif value="Lost">Lost</option>
                                        <option @if(Request::get('status-filter') == 'WIP') selected @endif value="WIP">WIP</option>
                                    </select>
                                    <label for="EDCV" style="margin-left:5px;">EDCV</label>
                                    <select class="custom-select-medium" id="edcv-filter" name="edcv-filter">
                                        <option value="" @if(!Request::get('edcv-filter')) selected @endif>All</option>
                                        <option value="expected_date_of_closure" @if(Request::get('edcv-filter') == 'expected_date_of_closure') selected @endif>Expected Date of Closure</option>
                                        <option value="order_closed_date" @if(Request::get('edcv-filter') == 'order_closed_date') selected @endif>Order Closed Date</option>
                                    </select>
                                    <input type="submit" class="btn btn-primary" style="margin-left: 10px;" form="search-form" id="">
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
                                    <th style="white-space: nowrap;">Contact Person</th>
                                    <th style="white-space: nowrap;">Product Type</th>
                                    <th style="white-space: nowrap;">Account Type</th>
                                    <th style="white-space: nowrap;">Op Status</th>
                                    <th style="white-space: nowrap;">Total Annual Budget</th>
                                    <th>EDCV</th>
                                    <th>ODCV</th>
                                    <th style="white-space: nowrap;">Lead Type</th>
                                    <th style="white-space: nowrap;">Pay Type</th>
                                    <th style="white-space: nowrap;">Remarks</th>
                                    <th style="white-space: nowrap;">RM Remarks</th>
                                    <th style="white-space: nowrap;">Last Activity</th>
                                    <th style="white-space: nowrap;">Contact Detail</th>
                                    <th style="white-space: nowrap;">Sales Person</th>
                                    <th>Status</th>
                                    <th>Lead Source</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($leads as $lead) {
                                    if ($lead->lead_status == 1) {
                                        $lead_status = 'Hot';
                                    } else if ($lead->lead_status == 2) {
                                        $lead_status = 'Warm';
                                    } else if ($lead->lead_status == 3) {
                                        $lead_status = 'Cold';
                                    }
                                    if ($lead->lead_type == 1) {
                                        $lead_type = 'New';
                                    } else {
                                        $lead_type = 'Existing';
                                    }
                                    ?>
                                    <tr>
                                        <td>
                                            <span style="white-space: nowrap;">{{ date('d/m/Y H:i', strtotime($lead->lead_date)) }} </span>
                                            <!-- <a href="#" class="con_btn">Convert</a>-->
                                        </td>
                                        <td>
                                            <span class="first-bold" style="white-space: nowrap;">{{ $lead->organisation }}</span>
                                            <span>{{ $lead->department }}</span>
                                        </td>
                                        <td>
                                            <span>{{ $lead->city }}</span>
                                        </td>
                                        <td>
                                            <span class="first-bold" style="white-space: nowrap;">{{ $lead->first_name }}</span>
                                            <span style="white-space: nowrap;">{{ $lead->designation_name }}</span>
                                        </td>
                                        <td>
                                            <span> {{ $lead->product_type }} </span>
                                        </td>
                                        <td>
                                            <span>{{ $lead->account_type }}</span>
                                        </td>
                                        <td>
                                            <span>{{ $lead->opp_status }}</span>
                                        </td>
                                        <td>
                                            <span style="white-space: nowrap;">{{ $lead->annual_budget ? ($lead->annual_budget/100000)." Lakhs": "" }}</span>
                                        </td>
                                        <td>
                                            <span style="white-space: nowrap;">{{ $lead->expected_date_of_closure }} {{ $lead->expected_value ? "| ".($lead->expected_value/100000)." Lakhs": "" }}</span>
                                        </td>
                                        <td>
                                            <span style="white-space: nowrap;">{{ $lead->order_closed_date ? $lead->order_closed_date : "" }} {{ $lead->closed_value ? "| ".($lead->closed_value/100000)." Lakhs" : "" }}</span>
                                        </td>
                                        <td>
                                            <span>{{ $lead_type }}</span>
                                        </td>
                                        <td>
                                            <span>{{ $lead->payment_type }}</span>
                                        </td>                                        
                                        <td style="white-space: nowrap;">
                                            @php  $details =  explode('.', $lead->remarks);  @endphp
                                            @if (count($details) > 1)
                                            <span>{{ $details[0] ? $details[0] . '.' : 'N/A' }}</span>
                                            <span>{{ $details[1] ? $details[1] . '...' : '' }}</span>
                                            @else
                                            <span>{{ $lead->remarks ? str_limit($lead->remarks, 100) : 'N/A' }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $lead->rm_remarks ? str_limit($lead->rm_remarks, 100) : 'N/A' }}</td>
                                        <td style="white-space: nowrap;"> 
                                            <span> {{ $lead->created_time }}</span>
                                        </td>
                                        <td style="white-space: nowrap;">
                                            <span class="first-bold" style="white-space: nowrap;">{{ $lead->contact_number }}</span>
                                            <span>{{ $lead->email_id }}</span>
                                        </td>
                                        <td style="white-space: nowrap;">
                                            <span>{{ $lead->sales_person }}</span>
                                        </td>
                                        <td>
                                        @if($lead->opp_status == 'Win' || $lead->opp_status == 'Lost')
                                            {{ $lead->opp_status }}
                                        @else
                                            <select name="opp_status" id="opp_status" onchange="changeStatus(this, '{{$lead->id}}')">
                                                <option @if($lead->opp_status == 'Win') selected @endif value="Win">Win</option>
                                                <option @if($lead->opp_status == 'Shelved') selected @endif value="Shelved">Shelved</option>
                                                <option @if($lead->opp_status == 'Lost') selected @endif value="Lost">Lost</option>
                                                <option @if($lead->opp_status == 'WIP') selected @endif value="WIP">WIP</option>
                                            </select>
                                        @endif
                                            <!-- Win Opportunity Modal  -->
                                            <div id="myModalChange{{ $lead->id }}" class="modal fade" role="dialog">
                                                <div class="modal-dialog modal-lg">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header d-inline">
                                                            <h4 class="modal-title d-flex justify-content-between">
                                                                Change Status
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">X</button>
                                                            </h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="POST" action="{{ route('updateOpportinity')}}">
                                                                @csrf
                                                                <div class="row">
                                                                    <input type="hidden" name="id" value="{{$lead->id}}">
                                                                    <input type="hidden" name="opp_status" value="" class="opp_status">
                                                                    <div class="col-md-12">
                                                                        <label>Remarks</label>
                                                                        <input type="text" required name="rm_remarks" id="" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12 d-none is-show">
                                                                        <label>Order Closed date</label>
                                                                        <input type="date" name="order_closed_date" value="{{$lead->order_closed_date}}" class="form-control" required>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12 d-none is-show">
                                                                        <label>Closed Value</label>
                                                                        <input type="text" name="closed_value" value="{{$lead->closed_value}}" class="form-control" required minlength="4" maxlength="10" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g, '')" placeholder="Closed Value in INR e.g. 50000 OR 150000">
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-3">
                                                                    <div class="col-md-12">
                                                                        <input type="submit" value="Submit" class="btn btn-primary">
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="first-bold">{{ $lead->source_type }}</span>
                                            <span>{{ $lead->source_value }}</span>
                                        </td>
                                        <td>
                                            <form method="POST" id="deleteopp{{$lead->id}}" action="{{ route('deleteOpportinity')}}">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$lead->id}}">
                                            </form>
                                            <a href="javascript:void(0)" onclick="checkDelete({{$lead->id}})" class="btn btn-primary icon-btn"><i class="fa fa-trash"></i></a>

                                            <a data-toggle="modal" href="#myModal{{ $lead->id }}" class="btn btn-primary icon-btn"><i class="fa fa-eye"></i></a>
                                            {{-- <a data-toggle="modal" href="#convertModel{{ $lead->id }}" class="btn btn-primary icon-btn">Convert</a> --}}
                                            @if($lead->opp_status != 'Win')
                                                <a data-toggle="modal" href="#editopportunity{{ $lead->id }}" class="btn btn-primary icon-btn"><i class="fa fa-edit"></i></a>
                                            @endif
                                            <!-- Modal -->
                                            <div id="myModal{{ $lead->id }}" class="modal fade" role="dialog">
                                                <div class="modal-dialog modal-lg">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header d-inline">
                                                            <h4 class="modal-title d-flex justify-content-between">
                                                                <span>{{ $lead->first_name.' '.$lead->last_name }}</span>
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">X</button>
                                                            </h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table table-bordered">
                                                                <tbody>
                                                                    <tr> <th>Lead Date</th> <td> <?php $cls_date = new DateTime($lead->lead_date); echo $cls_date->format('D d, M Y'); ?></td> </tr>
                                                                    <tr> <th>Salutation</th> <td>{{ $lead->salutation ?? "N/A" }}</td> </tr>
                                                                    <tr> <th>First Name</th> <td>{{ $lead->first_name ?? "N/A" }}</td> </tr>
                                                                    <tr> <th>Last Name</th> <td>{{ $lead->last_name ?? "N/A" }}</td> </tr>
                                                                    <tr> <th>Mobile Number/Landline Number</th> <td>{{ $lead->contact_number ?? "N/A" }}</td> </tr>
                                                                    <tr> <th>User Email</th> <td>{{ $lead->email_id ?? "N/A" }}</td> </tr>
                                                                    <tr> <th>Compittor Name</th> <td>{{ $lead->competitor_name ?? "N/A" }}</td> </tr>
                                                                    <tr> <th>Organisation</th> <td>{{ $lead->organisation ?? "N/A" }}</td> </tr>
                                                                    <tr> <th>Account Type</th> <td>{{ $lead->account_type ?? "N/A" }}</td> </tr>
                                                                    <tr> <th>Industry Type</th> <td>{{ $lead->industry_type ?? "N/A" }}</td> </tr>
                                                                    <tr> <th>Designation 222</th> <td>{{ $lead->designation_name ?? "N/A" }}</td> </tr>
                                                                    <tr> <th>User Department</th> <td>{{ $lead->department}}</td> </tr>
                                                                    <tr> <th>Sale Person</th> <td>{{ $lead->sales_person ?? "N/A" }}</td> </tr>
                                                                    <tr> <th>Lead Details</th> <td>{{ $lead->lead_details ?? "N/A" }}</td> </tr>
                                                                    <tr> <th>Source Type</th> <td>{{ $lead->source_type ?? "N/A" }}</td> </tr>
                                                                    <tr> <th>Source Value</th> <td>{{ $lead->source_value ?? "N/A" }}</td> </tr>
                                                                    <tr> <th>Product</th> <td>{{ $lead->product_type ?? "N/A" }}</td> </tr>
                                                                    <tr> <th>Sub Product </th> <td>{{ $lead->subproduct_type ?? "N/A" }}</td> </tr>
                                                                    <tr> <th>Lead Type</th> <td>{{ $lead_type ?? "N/A" ?? "N/A" }}</td> </tr>
                                                                    <tr> <th>Payment Type</th> <td>{{ $lead->payment_type ?? "N/A" }}</td> </tr>
                                                                    <tr> <th>Chennel Partner</th> <td>{{ $lead->channel_partner ?? "N/A" }}</td> </tr>
                                                                    <tr> <th>Chennel Competitor</th> <td>{{ $lead->channel_competitor ?? "N/A" }}</td> </tr>
                                                                    <tr> <th>Lead Status</th> <td>{{ $lead_status ?? "N/A" ?? "N/A" }}</td> </tr>
                                                                    <tr> <th>Address</th> <td>{{ $lead->address ?? "N/A" }}</td> </tr>
                                                                    <tr> <th>State</th> <td>{{ $lead->state ?? "N/A" }}</td> </tr>
                                                                    <tr> <th>City</th> <td>{{ $lead->city ?? "N/A" }}</td> </tr>
                                                                    <tr> <th>Pincode</th> <td>{{ $lead->pincode ?? "N/A" }}</td> </tr>
                                                                    <tr> <th>Created Time</th> <td>{{ $lead->created_time ?? "N/A" }}</td> </tr>
                                                                    <tr> <th>Opportunity Status</th> <td>{{ $lead->opp_status ?? "N/A" }}</td> </tr>
                                                                    <tr> <th>Expected Date of Closure</th> <td>{{ $lead->expected_date_of_closure ? date('d-m-Y',strtotime($lead->expected_date_of_closure)) : 'N/A' }}</td> </tr>
                                                                    <tr> <th>Order Closed date</th> <td>{{ $lead->order_closed_date ? date('d-m-Y',strtotime($lead->order_closed_date)) : 'N/A' }}</td> </tr>
                                                                    <tr> <th>Total Annual Budget</th> <td>{{ $lead->annual_budget ? "INR.$lead->annual_budget" : "N/A" }}</td> </tr>
                                                                    <tr> <th>Expected Value</th> <td>{{ $lead->expected_value ? "INR.$lead->expected_value" : "N/A" }}</td> </tr>
                                                                    <tr> <th>Closed Value</th> <td>{{ $lead->closed_value ? "INR.$lead->closed_value" : "N/A" }}</td> </tr>
                                                                    <tr> <th>Remarks</th> <td>{{ $lead->remarks ?? "N/A" }}</td> </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Modal For Edit Opportunity-->
                                            <div id="editopportunity{{ $lead->id }}" class="modal fade" role="dialog">
                                                <div class="modal-dialog modal-lg">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">

                                                        <div class="modal-header d-inline">
                                                            <h4 class="modal-title d-flex justify-content-between">
                                                                <span>Update Opportunity</span>
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">X</button>
                                                            </h4>
                                                        </div>
                                                        <form method="post" action="{{ SITEURL }}admin/editOpportunity" class="pb-3 form-fields">
                                                            <div class="modal-body">                                                            
                                                                <table class="table table-bordered">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>Remarks<span style="display:inline-block; font-size:16px; color:#f12605 "> * </span></td>
                                                                            <td>
                                                                                <textarea class="form-control" name="remarks" id="" cols="30" rows="10" required>{{ $lead->remarks }}</textarea> 
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Status<span style="display:inline-block; font-size:16px; color:#f12605 "> * </span></td>
                                                                            <td>
                                                                                @if($lead->opp_status == 'Win' || $lead->opp_status == 'Lost')
                                                                                    {{ $lead->opp_status }}
                                                                                @else
                                                                                    <select name="opp_status" id="opp_status" class="form-control" required>
                                                                                        <option value="">Select Status</option>
                                                                                        <option value="Win" @if($lead->opp_status == 'Win') selected @endif>Win</option>
                                                                                        <option value="Shelved" @if($lead->opp_status == 'Shelved') selected @endif>Shelved</option>
                                                                                        <option value="Lost" @if($lead->opp_status == 'Lost') selected @endif>Lost</option>
                                                                                        <option value="WIP" @if($lead->opp_status == 'WIP') selected @endif>WIP</option>
                                                                                    </select>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Total Annual Budget</td>
                                                                            <td class="d-flex justify-content-between">
                                                                                <input class="form-control" name="annual_budget" id="annual_budget" value="{{ $lead->annual_budget }}" type="text" minlength="4" maxlength="10" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g, '')"  placeholder="Total Annual Budget in INR e.g. 50000 OR 150000" style="width: 90%"><span>INR</span>
                                                                            </td>
                                                                        </tr>                                                                        
                                                                        <tr>
                                                                            <td>
                                                                                Expected Date of Closure<span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                                                                            </td>
                                                                            <td>
                                                                                <input class="form-control" name="expected_date_of_closure" type="date" id="expected_date_of_closure" value="{{ date('Y-m-d',strtotime($lead->expected_date_of_closure)) }}" required>
                                                                            </td>
                                                                        </tr>
                                                                        @if($lead->order_closed_date != '' )
                                                                        <tr>
                                                                            <td>
                                                                                Order Closed date<span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                                                                            </td>
                                                                            <td>
                                                                                <input class="form-control" name="order_closed_date" type="date" id="order_closed_date" value="{{ date('Y-m-d',strtotime($lead->order_closed_date)) }}" required @if($lead->opp_status != 'Win') disabled @endif>
                                                                            </td>
                                                                        </tr>
                                                                        @else
                                                                        <tr>
                                                                            <td>
                                                                                Order Closed date<span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                                                                            </td>
                                                                            <td>
                                                                                <input class="form-control" name="order_closed_date" type="date" id="order_closed_date" value="" required @if($lead->opp_status != 'Win') disabled @endif>
                                                                            </td>
                                                                        </tr>
                                                                        @endif
                                                                        <tr>
                                                                            <td>
                                                                                Expected Value<span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                                                                            </td>
                                                                            <td class="d-flex justify-content-between">
                                                                                <input class="form-control" id="expected_value" name="expected_value" type="text" minlength="4" maxlength="10" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g, '')"  placeholder="Expected Value in INR e.g. 50000 OR 150000"  style="width: 90%"  value="{{ $lead->expected_value }}" required><span>INR</span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                Closed Value<span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                                                                            </td>
                                                                            <td class="d-flex justify-content-between">
                                                                                <input class="form-control" id="closed_value" type="text" name="closed_value" minlength="4" maxlength="10" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g, '')"  placeholder="Closed Value in INR e.g. 50000 OR 150000" style="width: 90%"  value="{{ $lead->closed_value }}" required @if($lead->opp_status != 'Win') disabled @endif><span>INR</span>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="submit" class="btn btn-primary" value="Update">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                            </div>
                                                            {!! csrf_field() !!}
                                                            <input type="hidden" name="convertid" id="convertid" value="{{ $lead->id }}">
                                                            <input type="hidden" name="oldstatus" id="oldstatus" value="{{ $lead->opp_status }}">
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- convert model section start --}}
                                            <div id="convertModel{{ $lead->id }}" class="modal fade" role="dialog">
                                                <div class="modal-dialog modal-lg">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">

                                                        <div class="modal-header d-inline">
                                                            <h4 class="modal-title d-flex justify-content-between">
                                                                <span>Convert Lead To Opportunity</span>
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">X</button>
                                                            </h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="post" action="{{ SITEURL }}admin/saveOpportunity" class="pb-3 form-fields">
                                                                <table class="table table-bordered">
                                                                    <tbody>
                                                                        <tr> <th>Remarks</th> <td><textarea class="form-control" name="remarks" id="" cols="30" rows="10"></textarea> </td> </tr>
                                                                        <tr> <th>Status</th> <td>
                                                                            <select name="status" id="opp_status" class="form-control">
                                                                                <option value="win">Win</option>
                                                                                <option value="shelved">Shelved</option>
                                                                                <option value="lost">Lost</option>
                                                                                <option value="wip">WIP</option>
                                                                            </select>
                                                                        </tr>
                                                                        <tr> <th>Expected Date of Closure</th> <td><input class="form-control" id="expected_date_of_closure" name="expected_date_of_closure" type="date"></td> </tr>
                                                                        <tr> <th>Order Closed date</th> <td><input class="form-control" name="order_closed_date" type="date" id="order_closed_date"></td> </tr>
                                                                        <tr> <th>Expected Value</th> <td class="d-flex justify-content-between"><input class="form-control" id="expected_value" name="expected_value" type="text" placeholder="Expected Value in INR" style="width: 90%"><span>INR</span></td> </tr>
                                                                        <tr> <th>Closed Value</th> <td class="d-flex justify-content-between"><input class="form-control" id="closed_value" type="text" name="closed_value" placeholder="Closed Value in INR" style="width: 90%"><span>INR</span></td> </tr>
                                                                    </tbody>
                                                                </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="submit" class="btn btn-primary" value="Convert">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        </div>
                                                        {!! csrf_field() !!}
                                                    </form>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- convert model section end --}}
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
<!-- Sidenav-->
<div id="mySidenav" class="sidenav shadow width-560 mbr-sidenav">
    <div class="card-header d-flex flex-row align-items-center justify-content-between card-header-bckcolor">
        <h6 class="m-0 font-weight-bold text-primary">Add Lead</h6>
        <a href="javascript:void(0);" class="closeMySidenav" id="closeMySidenav">
            <i class="fas fa-times fa-sm"></i>
        </a>
    </div>
    <div class="card-body">
        <form method="post" action="{{ SITEURL }}admin/savelead" class="pb-3 form-fields">
            <div class="row">
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Lead Date <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span> </label>
                        <div class="input-group">
                            <input type="text" class="form-control datetimepicker" id="lead_date" name="lead_date" placeholder="Lead Date" required>
                        </div>
                    </div>
                </div>
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Organisation <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span></label>
                        <div class="input-group">
                            <input type="text" class="form-control " id="organisation" name="organisation"  placeholder="Organisation" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="inputfirst_name">Contact Person <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span></label>
                </div>
            </div>
            <div class="row">
                <div class="form-group remove-botttom-space col-6">
                    <div class="row">
                        <div class="col-4" style="padding-right: 0;">
                            <select name="salutation" id="" class="custom-select form-control" required>
                                <option value="" >Salutation</option>
                                <option value="Mr">Mr</option>
                                <option value="Mrs">Mrs</option>
                                <option value="Ms">Ms</option>
                                <option value="Miss">Miss</option>
                                <option value="Dr.">Dr.</option>
                            </select>
                        </div>
                        <div class="input-group col-8">
                            <input type="text" class="form-control" id="first_name"  name="first_name" aria-describedby="first_nameHelp" placeholder="First Name" required>
                        </div>
                    </div>
                </div>
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <input type="text" class="form-control" id="lastname" name="lastname" aria-describedby="LastNameHelp" placeholder="Last Name" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Mobile number/Landline Number <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span> </label>
                        <div class="input-group">
                            <input type="text" class="form-control " id="mobile" maxlength="10" onkeyup="if (/\D/g.test(this.value))
                                        this.value = this.value.replace(/\D/g, '')" name="mobile" placeholder="Mobile number/Landline Number" required>
                        </div>
                    </div>
                </div>
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Email Address <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span></label>
                        <div class="input-group">
                            <input type="email" class="form-control " id="youremail"  name="youremail"  placeholder="Email Address" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Competitor</label>
                        <div class="input-group">
                            <input type="text" class="form-control " id="competitor" name="competitor" placeholder="Competitor">
                        </div>
                    </div>
                </div>
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Account Type</label>
                        <div class="input-group">
                            <select id="account_type" name="account_type"class="custom-select form-control">
                                <option value="">Select Account Type</option>
                                <?php foreach ($account_types as $type) { ?>
                                <option value="{{ $type->accounttype }}">{{ $type->accounttype }}</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Industry Type <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span> </label>
                        <div class="input-group">
                            <select name="industrytypoe" id="industrytypoe" class="custom-select form-control" required>
                                <option value="">Select Industry Type</option>
                                <?php foreach ($industry_types as $industry) { ?>
                                <option value="{{ $industry->id }}" >{{ $industry->industrytype }} </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Designation <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span></label>
                        <div class="input-group">
                            <select name="designatoin" id="designatoin"  class="custom-select form-control" required>
                                <option value="">Select Designation</option>
                                <?php foreach ($designations as $designation) { ?>
                                <option value="{{ $designation->id }}">{{ $designation->designationname }}</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Department <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span></label>
                        <div class="input-group">
                            <select  name="department"  id="department"  class="custom-select form-control" required>
                                <option value="">Select Department</option>
                                <?php foreach ($departments as $department) { ?>
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Sales Person <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span> </label>
                        <div class="input-group">
                            <select  name="saleperson"  id="saleperson" class="custom-select form-control" required>
                                @if(auth()->user()->role != "Member")<option value="">Select Sales Person</option>@endif
                                <?php foreach ($sales_person as $person) { ?>
                                <option value="{{ $person->id }}" <?php if ($person->id == $userid) { echo "selected"; } ?>>{{ $person->name }}</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group remove-botttom-space col-12">
                    <label for="inputfirst_name">Lead Details</label>
                    <div class="input-group">
                        <textarea type="text" class="form-control " id="lead_details" name="lead_details"  placeholder="Lead Details"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Source Type</label>
                        <div class="input-group">
                            <select name="source_type" id="source_type" class="custom-select form-control">
                                <option value="">Select Source Type</option>
                                <?php foreach ($source_types as $type) { ?>
                                <option value="{{ $type->id }}">{{ $type->sourcetype }}</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Source Value</label>
                        <div class="input-group">
                            <select id="source_value" name="source_value" class="custom-select form-control">
                                <option value="">Select Source Type</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Product Type <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span> </label>
                        <div class="input-group">
                            <select id="product_type" name="product_type" class="custom-select form-control" required>
                                <option value="">Select Product Type</option>
                                <?php foreach ($product_types as $type) { ?>
                                <option value="{{ $type->id }}">{{ $type->producttype }}</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Lead Status <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span></label>
                        <div class="input-group">
                            <select id="lead_status" name="lead_status" class="custom-select form-control" required>
                                <option value="">Select Lead Status</option>
                                <option value="1">Hot</option>
                                <option value="2">Warm</option>
                                <option value="3">Cold</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Sub Product</label>
                        <div class="input-group">
                            <select id="sub_product" name="sub_product" class="custom-select form-control">
                                <option value="">Select Sub Product</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Payment Type</label>
                        <div class="input-group">
                            <select id="payment_type" name="payment_type" class="custom-select form-control">
                                <option value="">Select Payment Type</option>
                                <option value="Prepaid">Prepaid</option>
                                <option value="Postpaid">Postpaid</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Channel Partner Involved</label>
                        <div class="input-group">
                            <select name="channel_partner" id="channel_partner" class="custom-select form-control">
                                <option value="">Select Channel Partner Involved </option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group remove-botttom-space col-6" id="channekadara" style="display:none;">
                    <div class="input-group">
                        <label for="inputfirst_name">Channel Partner Name</label>
                        <div class="input-group">
                            <input type="text" class="form-control " name="channel_competitor" id="channel_competitor" placeholder="Channel Partner Name"  >
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Lead Type <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span> </label>
                        <div class="input-group">
                            <select  name="lead_type" id="lead_type" class="custom-select form-control" required>
                                <option value="">Select Lead Type</option>
                                <option value="1">New</option>
                                <option value="2">Existing</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="mbr_address col-12">
                    <h2 class="btn btn-primary btn-patient-box openMySidenav">Address</h2>
                </div>
            </div>
            <div class="row">
                <div class="form-group remove-botttom-space col-12">
                    <div class="input-group">
                        <label for="inputfirst_name">Address <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span> </label>
                        <div class="input-group">
                            <input type="text" class="form-control " id="address" name="address" placeholder="Address" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">State <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span> </label>
                        <div class="input-group">
                            <select name="state_id" id="state_id" class="custom-select form-control" required>
                                <option value="">Select State </option>
                                <?php foreach ($states as $state) {?>
                                <option value="{{ $state->id }}" >{{ $state->name }} </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">City <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span> </label>
                        <div class="input-group">
                            <select id="city_id" name="city_id" class="custom-select form-control" required>
                                <option value="">Select City</option>

                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group remove-botttom-space col-12">
                    <div class="input-group">
                        <label for="inputfirst_name">Pin Code <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span></label>
                        <div class="input-group">
                            <input type="text" class="form-control " onkeyup="if (/\D/g.test(this.value))
                                        this.value = this.value.replace(/\D/g, '')" name="pincode" id="pincode" maxlength="6" placeholder="Pin Code" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <button type="reset" class="btn btn-secondary mr-3 btn-patient-box closeMySidenav">Cancel</button>
                <button type="submit" class="btn btn-primary btn-patient-box">Submit</button>
                <input type="hidden" name="userid" id="userid" value="{{ $userid }}">
            </div>
            {!! csrf_field() !!}
        </form>
    </div>
</div>
<!-- Sidenav-->
<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
<script>
    // A $( document ).ready() block.
    $(document).ready(function () {
        $('#sourcetype').on('change', function () {
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
        $('#edcv-filter').change(function () {
            if ($('#from-search').val() && $('#to-search').val()) {
                $('#search-form').submit();
            } else {
                alert('Please select From and To Date');
            }
        });
    });

    $('select[name="opp_status"]').change(function () {
        if ($(this).val() != "Win") {
            $("input[name=expected_date_of_closure]").removeAttr('disabled');
            $("input[name=expected_value]").removeAttr('disabled');
            $("input[name=order_closed_date]").attr("disabled", 'disabled');
            $("input[name=closed_value]").attr("disabled", 'disabled');
            //alert($(this).val());
        }else {
            $("input[name=order_closed_date]").removeAttr('disabled');
            $("input[name=closed_value]").removeAttr('disabled');
            $("input[name=expected_date_of_closure]").attr("disabled", 'disabled');
            $("input[name=expected_value]").attr("disabled", 'disabled');
        }
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

var ChangeModal = null;
var ChangeModalValue = null;
function changeStatus(element, id) {
    $('#myModalChange' + id).modal('show');
    ChangeModal = id;
    ChangeModalValue = element.value;
    const form = $('#myModalChange' + id).find('form');
    if(ChangeModalValue == "Win") {
        form.find('.is-show').removeClass('d-none');
        $("input[name=order_closed_date]").attr("required", 'required');
        $("input[name=closed_value]").attr("required", 'required');
    } else {
        form.find('.is-show').addClass('d-none');
        $("input[name=order_closed_date]").removeAttr('required');
        $("input[name=closed_value]").removeAttr('required');
    }
    form.find('.opp_status').first().val(ChangeModalValue);
}

function checkDelete(id){
    con = confirm('Are you sure you want to delete this opportunity?');
    if(con) {
        $('#deleteopp' + id).submit();
    }
}

// function submitForm(form) {  }
</script>
@endsection
