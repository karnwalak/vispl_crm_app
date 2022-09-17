@extends('layouts.layout')
@section('content')
<div class="container-fluid">
    @if(Gate::check('isCountryHead') || Gate::check('isZonalLevelAdmin') || Gate::check('isStateLevelAdmin') || Gate::check('isMember'))
    <!-- Content Row -->
    <div class="row counters">
        <!-- Product Report -->        
        <div class="col-12 mb-4">
            <!-- Product Wise Summary -->
            <div class="card shadow  mb-4">
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <form action="" class="w-100 mb-2" method="POST" id="search-form">
                            @csrf
                            <div class="row" style="width: 100%">
                                <div class="col-md-4">
                                    <input type="date" placeholder="From date" class="form-control" value="@if(Request::get('from-search')){{Request::get('from-search')}}@endif" name="from-search" id="">
                                </div>
                                <div class="col-md-4">
                                    <input type="date" placeholder="To Date" class="form-control" value="@if(Request::get('to-search')){{Request::get('to-search')}}@endif" name="to-search" id="">
                                </div>
                                <div class="col-md-2">
                                    <input type="submit" class="btn btn-primary" form="search-form" id="">
                                </div>
                            </div>
                            @if(Gate::check('isCountryHead') || Gate::check('isZonalLevelAdmin') || Gate::check('isStateLevelAdmin'))
                            <div class="row mb-5">
                                <div class="col-md-4">
                                    <br>Zones
                                    <select class="form-control" id="zone-filter" name="zone-filter">
                                        <option @if(!Request::get('zone-filter')) selected @endif value="">All</option>
                                        <?php foreach ($zones as $zone) { ?>
                                        <option value="{{ $zone->id }}" {{$zone->id == Request::get('zone-filter') ? 'selected' : '' }}>{{ $zone->regionname }}</option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <br>Employee Name <br>
                                    <div style="overflow: scroll; height:120px;" id="manager-wrapper">
                                    <?php foreach ($sales_person as $person) { ?>
                                        <div style="height:20px">
                                            <input type="checkbox" name="managers[]" value="{{$person->id}}" <?php if ($filtered_managers) { foreach ($filtered_managers as $user) { if($user == $person->id) { ?>checked<?php }}}?>>
                                            <label for="manager{{$person->id}}">{{$person->name}}</label>
                                        </div>                                    
                                    <?php } ?>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </form>                        
                        <div><h4><a name='product'>Product Wise Summary (In Lakhs)</a></h4></div>
                        <table class="table" id="product-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="white-space: nowrap;">Account Manager</th>
                                    <?php foreach ($product_types as $type) { ?>
                                        <th>{{ $type->producttype }}</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($product_summary as $key => $summary) { ?>
                                    <tr>
                                        <td>
                                            <span style="white-space: nowrap;">{{ $summary->name }}</span>
                                        </td>                                        
                                        <?php foreach ($product_types as $type) {
                                            // Get product type in small case and blanks replaced with underscore
                                            $product_name = (string) trim(strtolower(str_replace(" ","_",$type->producttype)));
                                        ?>
                                            <td>
                                                <span style="white-space: nowrap;">{{ $summary->$product_name ? round(($summary->$product_name/100000),2) : "0.00" }}</span>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>                        
                    </div>                    
                </div>
            </div>
            <!-- WIP – Product Wise -->
            <div class="card shadow  mb-4">
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <div><h4>WIP – Product Wise (In Lakhs)</h4></div>
                        <table class="table" id="product-wip-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="white-space: nowrap;">Account Manager</th>
                                    <?php foreach ($product_types as $type) { ?>
                                        <th>{{ $type->producttype }}</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($wip_summary as $key => $summary) { ?>
                                    <tr>
                                        <td>
                                            <span style="white-space: nowrap;">{{ $summary->name }}</span>
                                        </td>
                                        <?php foreach ($product_types as $type) {
                                            // Get product type in small case and blanks replaced with underscore
                                            $product_name = (string) trim(strtolower(str_replace(" ","_",$type->producttype)));
                                        ?>
                                            <td>
                                                <span style="white-space: nowrap;">{{ $summary->$product_name ? round(($summary->$product_name/100000),2) : "0.00" }}</span>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>                    
            <!-- Win – Product Wise -->
            <div class="card shadow  mb-4">
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <div><h4>Win – Product Wise (In Lakhs)</h4></div>
                        <table class="table" id="product-win-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="white-space: nowrap;">Account Manager</th>
                                    <?php foreach ($product_types as $type) { ?>
                                        <th>{{ $type->producttype }}</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($win_summary as $key => $summary) { ?>
                                    <tr>
                                        <td>
                                            <span style="white-space: nowrap;">{{ $summary->name }}</span>
                                        </td>
                                        <?php foreach ($product_types as $type) {
                                            // Get product type in small case and blanks replaced with underscore
                                            $product_name = (string) trim(strtolower(str_replace(" ","_",$type->producttype)));
                                        ?>
                                            <td>
                                                <span style="white-space: nowrap;">{{ $summary->$product_name ? round(($summary->$product_name/100000),2) : "0.00" }}</span>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>                    
            <!-- Lost – Product Wise -->
            <div class="card shadow  mb-4">
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <div><h4>Lost – Product Wise (In Lakhs)</h4></div>
                        <table class="table" id="product-lost-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="white-space: nowrap;">Account Manager</th>
                                    <?php foreach ($product_types as $type) { ?>
                                        <th>{{ $type->producttype }}</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lost_summary as $key => $summary) { ?>
                                    <tr>
                                        <td>
                                            <span style="white-space: nowrap;">{{ $summary->name }}</span>
                                        </td>
                                        <?php foreach ($product_types as $type) {
                                            // Get product type in small case and blanks replaced with underscore
                                            $product_name = (string) trim(strtolower(str_replace(" ","_",$type->producttype)));
                                        ?>
                                            <td>
                                                <span style="white-space: nowrap;">{{ $summary->$product_name ? round(($summary->$product_name/100000),2) : "0.00" }}</span>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>                    
            <!-- Shelved – Product Wise -->
            <div class="card shadow  mb-4">
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <div><h4>Shelved – Product Wise (In Lakhs)</h4></div>
                        <table class="table" id="product-shelved-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="white-space: nowrap;">Account Manager</th>
                                    <?php foreach ($product_types as $type) { ?>
                                        <th>{{ $type->producttype }}</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($shelved_summary as $key => $summary) { ?>
                                    <tr>
                                        <td>
                                            <span style="white-space: nowrap;">{{ $summary->name }}</span>
                                        </td>
                                        <?php foreach ($product_types as $type) {
                                            // Get product type in small case and blanks replaced with underscore
                                            $product_name = (string) trim(strtolower(str_replace(" ","_",$type->producttype)));
                                        ?>
                                            <td>
                                                <span style="white-space: nowrap;">{{ $summary->$product_name ? round(($summary->$product_name/100000),2) : "0.00" }}</span>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>                    
            <!-- Opportunity Status Wise Summary -->
            <div class="card shadow  mb-4">
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <div><h4><a name='opportunity'>Opportunity Status Wise Summary (In Lakhs)</a></h4></div>
                        <table class="table" id="opportunity-status-summary" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="text-align: center">Status (Win/WIP/Lost/Shelved)</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Hot</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Warm</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Cold</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Grand Total</th>
                                </tr>
                                <tr>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <th><span style="white-space: nowrap;"><B>Count of Leads</B></span></th>
                                    <th><span style="white-space: nowrap;"><B>Expected Order Value</B></span></th>
                                </tr>                                
                            </thead>
                            <tbody>
                                <?php 
                                $tatal_count = 0;
                                $tatal_value = 0;
                                foreach ($opportunity_lead_summary as $summary) {
                                    $tatal_count = $summary->wip_hot_count + $summary->wip_warm_count + $summary->wip_cold_count;
                                    $tatal_value = $summary->wip_hot_order_value + $summary->wip_warm_order_value + $summary->wip_cold_order_value;
                                ?>
                                    <tr>
                                        <td><span style="white-space: nowrap;">{{ $summary->opp_status }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_hot_count }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_hot_order_value ? round(($summary->wip_hot_order_value/100000),2) : "0.00" }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_warm_count }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_warm_order_value ? round(($summary->wip_warm_order_value/100000),2) : "0.00" }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_cold_count }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_cold_order_value ? round(($summary->wip_cold_order_value/100000),2) : "0.00" }}</td>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_count }}</B></td>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_value ? round(($tatal_value/100000),2) : "0.00" }}</B></td>
                                    </tr>
                                <?php } ?>                                    
                            </tbody>                            
                            <tfoot>
                                <tr>
                                    <th>Grand Total:</th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- KAM – WIP Summary -->
            <div class="card shadow  mb-4">
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <div><h4>KAM – WIP Summary (In Lakhs)</h4></div>
                        <table class="table" id="opportunity-manager-wip-summary" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="white-space: nowrap;">WIP</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Hot</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Warm</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Cold</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Grand Total</th>
                                </tr>
                                <tr>
                                    <th><span style="white-space: nowrap;">Account Manager</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <th><span style="white-space: nowrap;"><B>Count of Leads</B></span></th>
                                    <th><span style="white-space: nowrap;"><B>Expected Order Value</B></span></th>
                                </tr>                                
                            </thead>
                            <tbody>
                                <?php
                                $tatal_count = 0;
                                $tatal_value = 0;
                                foreach ($manager_wip_summary as $key=>$summary) {                                    
                                    $tatal_count = $summary->wip_hot_count + $summary->wip_warm_count + $summary->wip_cold_count;
                                    $tatal_value = $summary->wip_hot_expected_value + $summary->wip_warm_expected_value + $summary->wip_cold_expected_value;
                                    ?>
                                    <tr>
                                        <td><span style="white-space: nowrap;">{{ $summary->name }}</span></td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_hot_count }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_hot_expected_value ? round(($summary->wip_hot_expected_value/100000),2) : "0.00" }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_warm_count }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_warm_expected_value ? round(($summary->wip_warm_expected_value/100000),2) : "0.00" }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_cold_count }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_cold_expected_value ? round(($summary->wip_cold_expected_value/100000),2) : "0.00" }}</td>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_count }}</B></td>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_value ? round(($tatal_value/100000),2) : "0.00" }}</B></td>
                                    </tr>
                                <?php } ?>                                    
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Grand Total:</th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                </tr>
                            </tfoot>                            
                        </table>
                    </div>
                </div>
            </div>
            <!-- KAM – Win Summary -->
            <div class="card shadow  mb-4">
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <div><h4>KAM – Win Summary (In Lakhs)</h4></div>
                        <table class="table" id="opportunity-manager-win-summary" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="white-space: nowrap;">Win</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Hot</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Warm</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Cold</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Grand Total</th>
                                </tr>
                                <tr>
                                    <th><span style="white-space: nowrap;">Account Manager</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <th><span style="white-space: nowrap;"><B>Count of Leads</B></span></th>
                                    <th><span style="white-space: nowrap;"><B>Expected Order Value</B></span></th>
                                </tr>                                
                            </thead>
                            <tbody>
                                <?php
                                $tatal_count = 0;
                                $tatal_value = 0;
                                foreach ($manager_win_summary as $key=>$summary) {
                                    $tatal_count = $summary->wip_hot_count + $summary->wip_warm_count + $summary->wip_cold_count;
                                    $tatal_value = $summary->wip_hot_expected_value + $summary->wip_warm_expected_value + $summary->wip_cold_expected_value;
                                    ?>
                                    <tr>
                                        <td><span style="white-space: nowrap;">{{ $summary->name }}</span></td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_hot_count }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_hot_expected_value ? round(($summary->wip_hot_expected_value/100000),2) : "0.00" }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_warm_count }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_warm_expected_value ? round(($summary->wip_warm_expected_value/100000),2) : "0.00" }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_cold_count }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_cold_expected_value ? round(($summary->wip_cold_expected_value/100000),2) : "0.00" }}</td>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_count }}</B></td>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_value ? round(($tatal_value/100000),2) : "0.00" }}</B></td>
                                    </tr>
                                <?php } ?>                                    
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Grand Total:</th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                </tr>
                            </tfoot>                            
                        </table>
                    </div>
                </div>
            </div>
            <!-- KAM - Lost Summary -->
            <div class="card shadow  mb-4">
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <div><h4>KAM - Lost Summary (In Lakhs)</h4></div>
                        <table class="table" id="opportunity-manager-lost-summary" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="white-space: nowrap;">Lost</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Hot</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Warm</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Cold</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Grand Total</th>
                                </tr>
                                <tr>
                                    <th><span style="white-space: nowrap;">Account Manager</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <th><span style="white-space: nowrap;"><B>Count of Leads</B></span></th>
                                    <th><span style="white-space: nowrap;"><B>Expected Order Value</B></span></th>
                                </tr>                                
                            </thead>
                            <tbody>
                                <?php
                                $tatal_count = 0;
                                $tatal_value = 0;
                                foreach ($manager_lost_summary as $key=>$summary) {
                                    $tatal_count = $summary->wip_hot_count + $summary->wip_warm_count + $summary->wip_cold_count;
                                    $tatal_value = $summary->wip_hot_expected_value + $summary->wip_warm_expected_value + $summary->wip_cold_expected_value;
                                    ?>
                                    <tr>
                                        <td><span style="white-space: nowrap;">{{ $summary->name }}</span></td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_hot_count }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_hot_expected_value ? round(($summary->wip_hot_expected_value/100000),2) : "0.00" }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_warm_count }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_warm_expected_value ? round(($summary->wip_warm_expected_value/100000),2) : "0.00" }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_cold_count }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_cold_expected_value ? round(($summary->wip_cold_expected_value/100000),2) : "0.00" }}</td>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_count }}</B></td>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_value ? round(($tatal_value/100000),2) : "0.00" }}</B></td>
                                    </tr>
                                <?php } ?>                                    
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Grand Total:</th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                </tr>
                            </tfoot>                            
                        </table>
                    </div>
                </div>
            </div>
            <!-- KAM - Shelved Summary -->
            <div class="card shadow  mb-4">
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <div><h4>KAM - Shelved Summary (In Lakhs)</h4></div>
                        <table class="table" id="opportunity-manager-shelved-summary" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="white-space: nowrap;">Shelved</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Hot</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Warm</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Cold</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Grand Total</th>
                                </tr>
                                <tr>
                                    <th><span style="white-space: nowrap;">Account Manager</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <th><span style="white-space: nowrap;"><B>Count of Leads</B></span></th>
                                    <th><span style="white-space: nowrap;"><B>Expected Order Value</B></span></th>
                                </tr>                                
                            </thead>
                            <tbody>
                                <?php
                                $tatal_count = 0;
                                $tatal_value = 0;
                                foreach ($manager_shelved_summary as $key=>$summary) {
                                    $tatal_count = $summary->wip_hot_count + $summary->wip_warm_count + $summary->wip_cold_count;
                                    $tatal_value = $summary->wip_hot_expected_value + $summary->wip_warm_expected_value + $summary->wip_cold_expected_value;
                                    ?>
                                    <tr>
                                        <td><span style="white-space: nowrap;">{{ $summary->name }}</span></td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_hot_count }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_hot_expected_value ? round(($summary->wip_hot_expected_value/100000),2) : "0.00" }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_warm_count }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_warm_expected_value ? round(($summary->wip_warm_expected_value/100000),2) : "0.00" }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_cold_count }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->wip_cold_expected_value ? round(($summary->wip_cold_expected_value/100000),2) : "0.00" }}</td>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_count }}</B></td>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_value ? round(($tatal_value/100000),2) : "0.00" }}</B></td>
                                    </tr>
                                <?php } ?>                                    
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Grand Total:</th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Account Type Summary -->
            <div class="card shadow  mb-4">
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <div><h4><a name='account'>Account Type Summary (In Lakhs)</a></h4></div>
                        <input type="hidden" name="account_types_count" id="account_types_count" value="{{ $account_types->count() }}">
                        <table class="table" id="account-type-summary-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="white-space: nowrap;"></th>
                                    <?php foreach ($account_types as $type) { ?>
                                        <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">{{ $type->accounttype }}</th>
                                    <?php } ?>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Grand Total</th>
                                </tr>
                                <tr>
                                    <th><span style="white-space: nowrap;">Account Manager</span></th>
                                    <?php foreach ($account_types as $type) { ?>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <?php } ?>
                                    <th><span style="white-space: nowrap;"><B>Count of Leads</B></span></th>
                                    <th><span style="white-space: nowrap;"><B>Expected Order Value</B></span></th>                                  
                                </tr>                                
                            </thead>
                            <tbody>
                                <?php
                                $net_count = 0;
                                $net_value = 0;                                
                                foreach ($account_types_summary as $key=>$summary) {
                                    $tatal_count = 0;
                                    $tatal_value = 0;
                                    ?>
                                    <tr>
                                        <td><span style="white-space: nowrap;">{{ $summary->name }}</span></td>
                                        <?php foreach ($account_types as $type) {
                                            // Get product type in small case and blanks replaced with underscore
                                            $account_type_count = (string) trim(strtolower(str_replace(array(' ','-'),"_",$type->accounttype)).'_counts');                                            
                                            $account_type_name = (string) trim(strtolower(str_replace(array(' ','-'),"_",$type->accounttype)).'_expected_value'); 
                                        ?>                                        
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->$account_type_count }} @php $tatal_count += $summary->$account_type_count @endphp</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->$account_type_name ? round(($summary->$account_type_name/100000),2) : "0.00" }} @php $tatal_value += $summary->$account_type_name @endphp</td>
                                        <?php } ?>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_count }}</B></td>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_value ? round(($tatal_value/100000),2) : "0.00" }}</B></td>                                        
                                    </tr>
                                <?php } ?>                                    
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Grand Total:</th>
                                    <?php for ($i = 0; $i < $account_types->count(); $i++) { ?>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <?php } ?>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                </tr>                                
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Account Type - WIP Summary -->
            <div class="card shadow  mb-4">
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <div><h4>Account Type - WIP Summary (In Lakhs)</h4></div>
                        <table class="table" id="account-type-wip-summary-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="white-space: nowrap;">WIP</th>
                                    <?php foreach ($account_types as $type) { ?>
                                        <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">{{ $type->accounttype }}</th>
                                    <?php } ?>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Grand Total</th>
                                </tr>
                                <tr>
                                    <th><span style="white-space: nowrap;">Account Manager</span></th>
                                    <?php foreach ($account_types as $type) { ?>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <?php } ?>
                                    <th><span style="white-space: nowrap;"><B>Count of Leads</B></span></th>
                                    <th><span style="white-space: nowrap;"><B>Expected Order Value</B></span></th>                                  
                                </tr>                                
                            </thead>
                            <tbody>
                                <?php
                                $net_count = 0;
                                $net_value = 0;                                
                                foreach ($account_types_wip_summary as $key=>$summary) {
                                    $tatal_count = 0;
                                    $tatal_value = 0;
                                    ?>
                                    <tr>
                                        <td><span style="white-space: nowrap;">{{ $summary->name }}</span></td>
                                        <?php foreach ($account_types as $type) {
                                            // Get product type in small case and blanks replaced with underscore
                                            $account_type_count = (string) trim(strtolower(str_replace(array(' ','-'),"_",$type->accounttype)).'_counts');                                            
                                            $account_type_name = (string) trim(strtolower(str_replace(array(' ','-'),"_",$type->accounttype)).'_expected_value'); 
                                        ?>                                        
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->$account_type_count }} @php $tatal_count += $summary->$account_type_count @endphp</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->$account_type_name ? round(($summary->$account_type_name/100000),2) : "0.00" }} @php $tatal_value += $summary->$account_type_name @endphp</td>
                                        <?php } ?>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_count }}</B></td>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_value ? round(($tatal_value/100000),2) : "0.00" }}</B></td>                                        
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Grand Total:</th>
                                    <?php for ($i = 0; $i < $account_types->count(); $i++) { ?>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <?php } ?>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                </tr>                                
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Account Type - Win Summary -->
            <div class="card shadow  mb-4">
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <div><h4>Account Type - Win Summary (In Lakhs)</h4></div>
                        <table class="table" id="account-type-win-summary-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="white-space: nowrap;">Win</th>
                                    <?php foreach ($account_types as $type) { ?>
                                        <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">{{ $type->accounttype }}</th>
                                    <?php } ?>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Grand Total</th>
                                </tr>
                                <tr>
                                    <th><span style="white-space: nowrap;">Account Manager</span></th>
                                    <?php foreach ($account_types as $type) { ?>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <?php } ?>
                                    <th><span style="white-space: nowrap;"><B>Count of Leads</B></span></th>
                                    <th><span style="white-space: nowrap;"><B>Expected Order Value</B></span></th>                                  
                                </tr>                                
                            </thead>
                            <tbody>
                                <?php
                                $net_count = 0;
                                $net_value = 0;                                
                                foreach ($account_types_win_summary as $key=>$summary) {
                                    $tatal_count = 0;
                                    $tatal_value = 0;
                                    ?>
                                    <tr>
                                        <td><span style="white-space: nowrap;">{{ $summary->name }}</span></td>
                                        <?php foreach ($account_types as $type) {
                                            // Get product type in small case and blanks replaced with underscore
                                            $account_type_count = (string) trim(strtolower(str_replace(array(' ','-'),"_",$type->accounttype)).'_counts');                                            
                                            $account_type_name = (string) trim(strtolower(str_replace(array(' ','-'),"_",$type->accounttype)).'_expected_value'); 
                                        ?>                                        
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->$account_type_count }} @php $tatal_count += $summary->$account_type_count @endphp</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->$account_type_name ? round(($summary->$account_type_name/100000),2) : "0.00" }} @php $tatal_value += $summary->$account_type_name @endphp</td>
                                        <?php } ?>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_count }}</B></td>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_value ? round(($tatal_value/100000),2) : "0.00" }}</B></td>                                        
                                    </tr>
                                <?php } ?>                                    
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Grand Total:</th>
                                    <?php for ($i = 0; $i < $account_types->count(); $i++) { ?>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <?php } ?>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                </tr>                                
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Account Type - Lost Summary -->
            <div class="card shadow  mb-4">
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <div><h4>Account Type - Lost Summary (In Lakhs)</h4></div>
                        <table class="table" id="account-type-lost-summary-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="white-space: nowrap;">Lost</th>
                                    <?php foreach ($account_types as $type) { ?>
                                        <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">{{ $type->accounttype }}</th>
                                    <?php } ?>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Grand Total</th>
                                </tr>
                                <tr>
                                    <th><span style="white-space: nowrap;">Account Manager</span></th>
                                    <?php foreach ($account_types as $type) { ?>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <?php } ?>
                                    <th><span style="white-space: nowrap;"><B>Count of Leads</B></span></th>
                                    <th><span style="white-space: nowrap;"><B>Expected Order Value</B></span></th>                                  
                                </tr>                                
                            </thead>
                            <tbody>
                                <?php
                                $net_count = 0;
                                $net_value = 0;                                
                                foreach ($account_types_lost_summary as $key=>$summary) {
                                    $tatal_count = 0;
                                    $tatal_value = 0;
                                    ?>
                                    <tr>
                                        <td><span style="white-space: nowrap;">{{ $summary->name }}</span></td>
                                        <?php foreach ($account_types as $type) {
                                            // Get product type in small case and blanks replaced with underscore
                                            $account_type_count = (string) trim(strtolower(str_replace(array(' ','-'),"_",$type->accounttype)).'_counts');                                            
                                            $account_type_name = (string) trim(strtolower(str_replace(array(' ','-'),"_",$type->accounttype)).'_expected_value'); 
                                        ?>                                        
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->$account_type_count }} @php $tatal_count += $summary->$account_type_count @endphp</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->$account_type_name ? round(($summary->$account_type_name/100000),2) : "0.00" }} @php $tatal_value += $summary->$account_type_name @endphp</td>
                                        <?php } ?>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_count }}</B></td>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_value ? round(($tatal_value/100000),2) : "0.00" }}</B></td>                                        
                                    </tr>
                                <?php } ?>                                    
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Grand Total:</th>
                                    <?php for ($i = 0; $i < $account_types->count(); $i++) { ?>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <?php } ?>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                </tr>                                
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Account Type -  Shelved Summary -->
            <div class="card shadow  mb-4">
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <div><h4>Account Type -  Shelved Summary (In Lakhs)</h4></div>
                        <table class="table" id="account-type-shelved-summary-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="white-space: nowrap;">Shelved</th>
                                    <?php foreach ($account_types as $type) { ?>
                                        <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">{{ $type->accounttype }}</th>
                                    <?php } ?>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Grand Total</th>
                                </tr>
                                <tr>
                                    <th><span style="white-space: nowrap;">Account Manager</span></th>
                                    <?php foreach ($account_types as $type) { ?>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <?php } ?>
                                    <th><span style="white-space: nowrap;"><B>Count of Leads</B></span></th>
                                    <th><span style="white-space: nowrap;"><B>Expected Order Value</B></span></th>                                  
                                </tr>                                
                            </thead>
                            <tbody>
                                <?php
                                $net_count = 0;
                                $net_value = 0;                                
                                foreach ($account_types_shelved_summary as $key=>$summary) {
                                    $tatal_count = 0;
                                    $tatal_value = 0;
                                    ?>
                                    <tr>
                                        <td><span style="white-space: nowrap;">{{ $summary->name }}</span></td>
                                        <?php foreach ($account_types as $type) {
                                            // Get product type in small case and blanks replaced with underscore
                                            $account_type_count = (string) trim(strtolower(str_replace(array(' ','-'),"_",$type->accounttype)).'_counts');                                            
                                            $account_type_name = (string) trim(strtolower(str_replace(array(' ','-'),"_",$type->accounttype)).'_expected_value'); 
                                        ?>                                        
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->$account_type_count }} @php $tatal_count += $summary->$account_type_count @endphp</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->$account_type_name ? round(($summary->$account_type_name/100000),2) : "0.00" }} @php $tatal_value += $summary->$account_type_name @endphp</td>
                                        <?php } ?>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_count }}</B></td>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_value ? round(($tatal_value/100000),2) : "0.00" }}</B></td>                                        
                                    </tr>
                                <?php } ?>                                    
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Grand Total:</th>
                                    <?php for ($i = 0; $i < $account_types->count(); $i++) { ?>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <?php } ?>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Customer Type Summary -->
            <div class="card shadow  mb-4">
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <div><h4><a name='customer'>Customer Type Summary (In Lakhs)<a></h4></div>
                        <table class="table" id="customer-type-summary-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="white-space: nowrap;"></th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">New</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Existing</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Grand Total</th>
                                </tr>
                                <tr>
                                    <th><span style="white-space: nowrap;">Account Manager</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <th><span style="white-space: nowrap;"><B>Count of Leads</B></span></th>
                                    <th><span style="white-space: nowrap;"><B>Expected Order Value</B></span></th>
                                </tr>                                
                            </thead>
                            <tbody>
                                <?php
                                $tatal_count = 0;
                                $tatal_value = 0;

                                foreach ($customer_type_summary as $key=>$summary) {
                                    $tatal_count = $summary->customer_new_count + $summary->customer_existing_count;
                                    $tatal_value = $summary->customer_new_value + $summary->customer_existing_value;
                                    ?>
                                    <tr>
                                        <td><span style="white-space: nowrap;">{{ $summary->name }}</span></td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->customer_new_count }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->customer_new_value ? round(($summary->customer_new_value/100000),2) : "0.00" }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->customer_existing_count }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->customer_existing_value ? round(($summary->customer_existing_value/100000),2) : "0.00" }}</td>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_count }}</B></td>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_value ? round(($tatal_value/100000),2) : "0.00" }}</B></td>
                                    </tr>
                                <?php } ?>                                    
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Grand Total:</th>
                                    <?php for ($i = 0; $i < 2; $i++) { ?>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <?php } ?>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>            
            <!--  Customer Type - WIP Summary -->
            <div class="card shadow  mb-4">
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <div><h4> Customer Type - WIP Summary (In Lakhs)</h4></div>
                        <table class="table" id="customer-type-wip-summary-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="white-space: nowrap;">WIP</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">New</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Existing</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Grand Total</th>
                                </tr>
                                <tr>
                                    <th><span style="white-space: nowrap;">Account Manager</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <th><span style="white-space: nowrap;"><B>Count of Leads</B></span></th>
                                    <th><span style="white-space: nowrap;"><B>Expected Order Value</B></span></th>
                                </tr>                                
                            </thead>
                            <tbody>
                                <?php
                                $tatal_count = 0;
                                $tatal_value = 0;

                                foreach ($customer_wip_summary as $key=>$summary) {                                    
                                    $tatal_count = $summary->customer_new_count + $summary->customer_existing_count;
                                    $tatal_value = $summary->customer_new_value + $summary->customer_existing_value;
                                    ?>
                                    <tr>
                                        <td><span style="white-space: nowrap;">{{ $summary->name }}</span></td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->customer_new_count }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->customer_new_value ? round(($summary->customer_new_value/100000),2) : "0.00" }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->customer_existing_count }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->customer_existing_value ? round(($summary->customer_existing_value/100000),2) : "0.00" }}</td>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_count }}</B></td>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_value ? round(($tatal_value/100000),2) : "0.00" }}</B></td>
                                    </tr>
                                <?php } ?>                                    
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Grand Total:</th>
                                    <?php for ($i = 0; $i < 2; $i++) { ?>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <?php } ?>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>            
            <!-- Customer Type - Win Summary -->
            <div class="card shadow  mb-4">
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <div><h4>Customer Type - Win Summary (In Lakhs)</h4></div>
                        <table class="table" id="customer-type-win-summary-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="white-space: nowrap;">Win</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">New</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Existing</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Grand Total</th>
                                </tr>
                                <tr>
                                    <th><span style="white-space: nowrap;">Account Manager</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <th><span style="white-space: nowrap;"><B>Count of Leads</B></span></th>
                                    <th><span style="white-space: nowrap;"><B>Expected Order Value</B></span></th>
                                </tr>                                
                            </thead>
                            <tbody>
                                <?php
                                $tatal_count = 0;
                                $tatal_value = 0;

                                foreach ($customer_win_summary as $key=>$summary) {                                    
                                    $tatal_count = $summary->customer_new_count + $summary->customer_existing_count;
                                    $tatal_value = $summary->customer_new_value + $summary->customer_existing_value;
                                    ?>
                                    <tr>
                                        <td><span style="white-space: nowrap;">{{ $summary->name }}</span></td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->customer_new_count }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->customer_new_value ? round(($summary->customer_new_value/100000),2) : "0.00" }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->customer_existing_count }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->customer_existing_value ? round(($summary->customer_existing_value/100000),2) : "0.00" }}</td>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_count }}</B></td>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_value ? round(($tatal_value/100000),2) : "0.00" }}</B></td>
                                    </tr>
                                <?php } ?>                                    
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Grand Total:</th>
                                    <?php for ($i = 0; $i < 2; $i++) { ?>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <?php } ?>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Customer Type - Lost Summary -->
            <div class="card shadow  mb-4">
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <div><h4>Customer Type - Lost Summary (In Lakhs)</h4></div>
                        <table class="table" id="customer-type-lost-summary-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="white-space: nowrap;">Lost</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">New</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Existing</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Grand Total</th>
                                </tr>
                                <tr>
                                    <th><span style="white-space: nowrap;">Account Manager</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <th><span style="white-space: nowrap;"><B>Count of Leads</B></span></th>
                                    <th><span style="white-space: nowrap;"><B>Expected Order Value</B></span></th>
                                </tr>                                
                            </thead>
                            <tbody>
                                <?php
                                $tatal_count = 0;
                                $tatal_value = 0;

                                foreach ($customer_lost_summary as $key=>$summary) {                                    
                                    $tatal_count = $summary->customer_new_count + $summary->customer_existing_count;
                                    $tatal_value = $summary->customer_new_value + $summary->customer_existing_value;
                                    ?>
                                    <tr>
                                        <td><span style="white-space: nowrap;">{{ $summary->name }}</span></td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->customer_new_count }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->customer_new_value ? round(($summary->customer_new_value/100000),2) : "0.00" }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->customer_existing_count }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->customer_existing_value ? round(($summary->customer_existing_value/100000),2) : "0.00" }}</td>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_count }}</B></td>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_value ? round(($tatal_value/100000),2) : "0.00" }}</B></td>
                                    </tr>
                                <?php } ?>                                    
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Grand Total:</th>
                                    <?php for ($i = 0; $i < 2; $i++) { ?>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <?php } ?>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Customer Type - Shelved Summary -->
            <div class="card shadow  mb-4">
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <div><h4>Customer Type - Shelved Summary (In Lakhs)</h4></div>
                        <table class="table" id="customer-type-shelved-summary-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="white-space: nowrap;">Shelved</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">New</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Existing</th>
                                    <th colspan="2" style="text-align: center" <span style="white-space: nowrap;">Grand Total</th>
                                </tr>
                                <tr>
                                    <th><span style="white-space: nowrap;">Account Manager</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                    <th><span style="white-space: nowrap;"><B>Count of Leads</B></span></th>
                                    <th><span style="white-space: nowrap;"><B>Expected Order Value</B></span></th>
                                </tr>                                
                            </thead>
                            <tbody>
                                <?php
                                $tatal_count = 0;
                                $tatal_value = 0;

                                foreach ($customer_shelved_summary as $key=>$summary) {                                    
                                    $tatal_count = $summary->customer_new_count + $summary->customer_existing_count;
                                    $tatal_value = $summary->customer_new_value + $summary->customer_existing_value;
                                    ?>
                                    <tr>
                                        <td><span style="white-space: nowrap;">{{ $summary->name }}</span></td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->customer_new_count }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->customer_new_value ? round(($summary->customer_new_value/100000),2) : "0.00" }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->customer_existing_count }}</td>
                                        <td style="text-align: center;white-space: nowrap;">{{ $summary->customer_existing_value ? round(($summary->customer_existing_value/100000),2) : "0.00" }}</td>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_count }}</B></td>
                                        <td style="text-align: center;white-space: nowrap;"><B>{{ $tatal_value ? round(($tatal_value/100000),2) : "0.00" }}</B></td>
                                    </tr>
                                <?php } ?>                                    
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Grand Total:</th>
                                    <?php for ($i = 0; $i < 2; $i++) { ?>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <?php } ?>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                    <th style="text-align: center;white-space: nowrap;"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- KAM Summary -->
            <div class="card shadow  mb-4">
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <div><h4><a name='kam'>KAM Summary (In Lakhs)</a></h4></div>
                        <table class="table" id="account-manager-summary-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th><span style="white-space: nowrap;">Account Manager</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                </tr>                                
                            </thead>
                            <tbody>
                                <?php foreach ($account_manager_summary as $key=>$summary) { ?>
                                    <tr>
                                        <td style="white-space: nowrap;">{{ $summary->name }}</td>
                                        <td style="white-space: nowrap;">{{ $summary->customer_leads }}</td>
                                        <td style="white-space: nowrap;">{{ $summary->customer_existing_value ? round(($summary->customer_existing_value/100000),2) : "0.00" }}</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Grand Total:</th>
                                    <th style="white-space: nowrap;"></th>
                                    <th style="white-space: nowrap;"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- KAM – WIP Summary -->
            <div class="card shadow  mb-4">
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <div><h4>KAM – WIP Summary (In Lakhs)</h4></div>
                        <table class="table" id="account-manager-wip-summary-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th><span style="white-space: nowrap;">Account Manager</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                </tr>                                
                            </thead>
                            <tbody>
                                <?php foreach ($account_manager_wip_summary as $key=>$summary) { ?>
                                    <tr>
                                        <td style="white-space: nowrap;">{{ $summary->name }}</td>
                                        <td style="white-space: nowrap;">{{ $summary->customer_leads }}</td>
                                        <td style="white-space: nowrap;">{{ $summary->customer_existing_value ? round(($summary->customer_existing_value/100000),2) : "0.00" }}</td>
                                    </tr>
                                <?php } ?> 
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Grand Total:</th>
                                    <th style="white-space: nowrap;"></th>
                                    <th style="white-space: nowrap;"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- KAM - Win Summary -->
            <div class="card shadow  mb-4">
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <div><h4>KAM - Win Summary (In Lakhs)</h4></div>
                        <table class="table" id="account-manager-win-summary-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th><span style="white-space: nowrap;">Account Manager</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                </tr>                                
                            </thead>
                            <tbody>
                                <?php foreach ($account_manager_win_summary as $key=>$summary) { ?>
                                    <tr>
                                        <td style="white-space: nowrap;">{{ $summary->name }}</td>
                                        <td style="white-space: nowrap;">{{ $summary->customer_leads }}</td>
                                        <td style="white-space: nowrap;">{{ $summary->customer_existing_value ? round(($summary->customer_existing_value/100000),2) : "0.00" }}</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Grand Total:</th>
                                    <th style="white-space: nowrap;"></th>
                                    <th style="white-space: nowrap;"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- KAM - Lost Summary -->
            <div class="card shadow  mb-4">
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <div><h4>KAM - Lost Summary (In Lakhs)</h4></div>
                        <table class="table" id="account-manager-lost-summary-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th><span style="white-space: nowrap;">Account Manager</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                </tr>                                
                            </thead>
                            <tbody>
                                <?php foreach ($account_manager_lost_summary as $key=>$summary) { ?>
                                    <tr>
                                        <td style="white-space: nowrap;">{{ $summary->name }}</td>
                                        <td style="white-space: nowrap;">{{ $summary->customer_leads }}</td>
                                        <td style="white-space: nowrap;">{{ $summary->customer_existing_value ? round(($summary->customer_existing_value/100000),2) : "0.00" }}</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Grand Total:</th>
                                    <th style="white-space: nowrap;"></th>
                                    <th style="white-space: nowrap;"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- KAM - Shelved Summary -->
            <div class="card shadow  mb-4">
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <div><h4>KAM - Shelved Summary (In Lakhs)</h4></div>
                        <table class="table" id="account-manager-shelved-summary-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th><span style="white-space: nowrap;">Account Manager</span></th>
                                    <th><span style="white-space: nowrap;">Count of Leads</span></th>
                                    <th><span style="white-space: nowrap;">Expected Order Value</span></th>
                                </tr>                                
                            </thead>
                            <tbody>
                                <?php foreach ($account_manager_shelved_summary as $key=>$summary) { ?>
                                    <tr>
                                        <td style="white-space: nowrap;">{{ $summary->name }}</td>
                                        <td style="white-space: nowrap;">{{ $summary->customer_leads }}</td>
                                        <td style="white-space: nowrap;">{{ $summary->customer_existing_value ? round(($summary->customer_existing_value/100000),2) : "0.00" }}</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Grand Total:</th>
                                    <th style="white-space: nowrap;"></th>
                                    <th style="white-space: nowrap;"></th>
                                </tr>
                            </tfoot>                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#product-table').DataTable();
    $('#product-wip-table').DataTable();
    $('#product-win-table').DataTable();
    $('#product-lost-table').DataTable();
    $('#product-shelved-table').DataTable();

    $('#opportunity-status-summary,\n\
       #opportunity-manager-wip-summary,\n\
       #opportunity-manager-win-summary,\n\
       #opportunity-manager-lost-summary,\n\
       #opportunity-manager-shelved-summary').DataTable( {
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api();
             // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ? i.replace(/[\ <b> </b>]/g, '')*1 : typeof i === 'number' ? i : 0;
            };
            // Opportunity count and expected value
            for (idx = 1; idx <= 6; idx++) {
                var total = api.column( idx, { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                if (idx%2 == 0) {
                    $(api.column(idx).footer()).html(parseFloat(total).toFixed(2));
                } else {
                    $(api.column(idx).footer()).html(total);
                }
            }

            // Grand Total
            var grand_count = api.column((idx), { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
            var grand_value = api.column((idx+1), { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
            $(api.column(idx).footer() ).html(grand_count);
            $(api.column(idx+1).footer() ).html(parseFloat(grand_value).toFixed(2));
        }
    });

    $('#account-type-summary-table,\n\
       #account-type-wip-summary-table,\n\
       #account-type-win-summary-table,\n\
       #account-type-lost-summary-table,\n\
       #account-type-shelved-summary-table').DataTable( {
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api();
             // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ? i.replace(/[\<b> </b>]/g, '')*1 : typeof i === 'number' ? i : 0;
            };
            // Account types count and expected value
            for (idx = 1; idx <= ($("#account_types_count").val() * 2); idx++) {
                var total = api.column( idx, { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                if (idx%2 == 0) {
                    $(api.column(idx).footer()).html(parseFloat(total).toFixed(2));
                } else {
                    $(api.column(idx).footer()).html(total);
                }
            }

            // Grand Total
            var grand_count = api.column((idx), { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
            var grand_value = api.column((idx+1), { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
            $(api.column(idx).footer() ).html(grand_count);
            $(api.column(idx+1).footer() ).html(parseFloat(grand_value).toFixed(2));
        }
    });

    $('#customer-type-summary-table,\n\
       #customer-type-wip-summary-table,\n\
       #customer-type-win-summary-table,\n\
       #customer-type-lost-summary-table,\n\
       #customer-type-shelved-summary-table').DataTable( {
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api();
             // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ? i.replace(/[\<b> </b>]/g, '')*1 : typeof i === 'number' ? i : 0;
            };
            // Customer types count and expected value
            for (idx = 1; idx <= 4; idx++) {
                var total = api.column( idx, { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                if (idx%2 == 0) {
                    $(api.column(idx).footer()).html(parseFloat(total).toFixed(2));
                } else {
                    $(api.column(idx).footer()).html(total);
                }
            }

            // Grand Total
            var grand_count = api.column((idx), { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
            var grand_value = api.column((idx+1), { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
            $(api.column(idx).footer() ).html(grand_count);
            $(api.column(idx+1).footer() ).html(parseFloat(grand_value).toFixed(2));
        }
    });

    $('#account-manager-summary-table,\n\
       #account-manager-wip-summary-table,\n\
       #account-manager-win-summary-table,\n\
       #account-manager-lost-summary-table,\n\
       #account-manager-shelved-summary-table').DataTable( {
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api();
             // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                console.log("Before : " + i);
                var result =  typeof i === 'string' ? i.replace(/[\<b> </b>]/g, '')*1 : typeof i === 'number' ? i : 0;
                console.log("After : " + result);
                return result
            };

            // Grand Total
            var grand_count = api.column(1, { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
            var grand_value = api.column(2, { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
            $(api.column(1).footer() ).html(grand_count);
            $(api.column(2).footer() ).html(parseFloat(grand_value).toFixed(2));
        }
    });

    $("#zone-filter").change(function () {
        $.ajax({
            type: "GET",
            url: "{{ route('getusers') }}",
            data: {
                zone_id: $(this).val()
            },
            success: function (response) {
                var users = '';
                if (JSON.parse(response).length > 0) {
                    var result = JSON.parse(response);
                    var users = "";
                    $.each(result, function(k, v) {
                        users += '<div style="height:20px"><input type="checkbox" name="managers[]" value="'+ v.id +'">&nbsp;<label for="manager'+ v.id +'">'+ v.name +'</label></div>';
                    });                    
                } else {
                   users += '<div style="height:20px">No Employee Found In This Region.</div>'; 
                }
                $('#manager-wrapper').html(users);
            }
        });        
    });
} );
</script>
@endsection
