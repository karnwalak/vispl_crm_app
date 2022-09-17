<div id="mySidenav" class="sidenav shadow width-560 mbr-sidenav">
    <div class="card-header d-flex flex-row align-items-center justify-content-between card-header-bckcolor">
        <h6 class="m-0 font-weight-bold text-primary">Add Activity</h6>
        <a href="javascript:void(0);" class="closeMySidenav" id="closeMySidenav">
            <i class="fas fa-times fa-sm"></i>
        </a>
    </div>
    <div class="card-body">
        <form method="post" id="saveLeadForm" action="{{ route('saveactivity') }}" class="pb-3 form-fields">
            <div class="top_part">
                <div class="row">
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Activity Type
                                <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                            </label>
                            <div class="input-group">
                                <select id="activitytype" class="custom-select form-control" name="activity_type_id" required>
                                    <option value="">Select Activity Type</option>
                                    <?php foreach ($activity_types as $type) { ?>
                                        <option value="{{ $type->id }}" >{{ $type->activity_name }} </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Sale Person
                                <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                            </label>
                            <div class="input-group">
                                <select id="saleperson" name="sale_person_id" class="custom-select form-control" required>
                                @if(auth()->user()->role != "Member")<option value="">Select Sales Person</option>@endif
                                <?php foreach ($sales_person as $person) { ?>
                                    <option value="{{ $person->id }}" <?php if ($person->id == $user_id) { echo "selected"; } ?>>{{ $person->name }}</option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Activity Date / Time <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span></label>
                            <div class="input-group">
                                <input type="text" class="form-control datetimepicker" id="formdate" name="from_date" placeholder="Form Date" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">To Date / Time</label>
                            <div class="input-group">
                                <input type="text" class="form-control datetimepicker" id="todate" name="to_date" placeholder="To Date" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Accompanied By Regional Head</label>
                            <div class="input-group">
                                <select name="accompanied_by_rh" id="accompanied_by_rh" class="custom-select form-control">
                                    <option value="">Select Accompanied By Regional Head</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Accompanied By Sales Head</label>
                            <div class="input-group">
                                <select name="accompanied_by_sh" id="accompanied_by_sh" class="custom-select form-control">
                                    <option value="">Select Accompanied By Sales Head</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Accompanied By Others</label>
                            <div class="input-group">
                                <select id="accompanied_by_others" name="accompanied_by_others" class="custom-select form-control">
                                    <option value="">Select Accompanied By Others</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Accompanied By Product Head</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="accompanied_by_ph" name="accompanied_by_ph">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Next Follow-up Date
                                <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                            </label>
                            <div class="input-group">
                                <input type="date" class="form-control " id="followup_date" name="followup_date" placeholder="Follow-up Date" required>                                
                            </div>
                        </div>                     
                    </div>
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Follow-up Time</label>
                            <div class="input-group">
                                <input type="time" class="form-control " id="followup_time" name="followup_time" placeholder="Follow-up Time" value="">                           
                            </div>
                        </div>                     
                    </div>
                </div>
                <div class="row">
                    <div class="form-group remove-botttom-space col-12">
                        <label for="inputfirst_name">Activity Description</label>
                        <div class="input-group">
                            <textarea type="text" class="form-control " id="activity_details" name="activity_details"  placeholder="Activity Description"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group frm-margin-btm col-md-12 label-lght">
                        <p style="margin-bottom: 5px;">Link To</p>
                        <input type="radio" checked  name="radion" value="Nolink" required>
                        <label for="nolink">Pre-Sales</label>
                        <input type="radio"  name="radion" value="New" required>
                        <label for="new">New</label>
                        <input type="radio" name="radion" value="Existing" required>
                        <label for="existing">Existing</label><br>
                    </div>
                </div>
            </div>
            <!-- Pre Sales -->
            <div class="Nolink selectt" style="">
                <div class="row">
                    <div class="col-md-12">
                        <label for="inputfirst_name">Contact Person <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span></label>
                    </div>
                </div>
                <div class="row">
                    <div class="remove-botttom-space col-6">
                        <div class="row">
                            <div class="col-4" style="padding-right: 0;">
                                <select name="salutation_" id="" class="custom-select form-control Pre-selectt" required="">
                                    <option value="">Salutation</option>
                                    <option value="Mr">Mr</option>
                                    <option value="Mrs">Mrs</option>
                                    <option value="Ms">Ms</option>
                                    <option value="Miss">Miss</option>
                                    <option value="Dr.">Dr.</option>
                                </select>
                            </div>
                            <div class="input-group col-8">
                                <input type="text" class="form-control Pre-selectt" id="first_name_" name="first_name_" aria-describedby="first_nameHelp" placeholder="First Name" required="">
                            </div>
                        </div>
                    </div>
                    <div class="remove-botttom-space col-6">
                        <div class="input-group">
                            <input type="text" class="form-control Pre-selectt" id="last_name_" name="last_name_" aria-describedby="LastNameHelp" placeholder="Last Name">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="">
                            <label for="nameOfClient">
                                Organization Name
                                <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                            </label>
                            <input type="text" required name="organisation_" id="" class="form-control Pre-selectt">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="">
                            <label for="nameOfClient">
                                Mobile Number/Landline Number
                                <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                            </label>
                            <input type="text" required name="mobile_" id="" class="form-control Pre-selectt" minlength="10" maxlength="11" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g, '')" placeholder="Mobile Number/Landline Number">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="">
                            <label for="nameOfClient">Email Address</label>
                            <input type="email" name="youremail_" id="" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <!-- New -->
            <div class="New selectt" style="display: none;" id="new-select">
                <div class="row">
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Lead Date
                                <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control New-selectt datetimepicker" id="lead_date" name="lead_date" placeholder="Lead Date">
                            </div>
                        </div>
                    </div>
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Organisation
                                <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control New-selectt" id="organisation" name="organisation"  placeholder="Organisation">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="inputfirst_name">Contact Person</label>
                        <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group remove-botttom-space col-6">
                        <div class="row">
                            <div class="col-4" style="padding-right: 0;">
                                <select name="salutation" id="salutation" class="custom-select form-control New-selectt" >
                                    <option selected value="">Salutation</option>
                                    <option value="Mr">Mr</option>
                                    <option value="Mrs">Mrs</option>
                                    <option value="Ms">Ms</option>
                                    <option value="Miss">Miss</option>
                                    <option value="Dr.">Dr.</option>
                                </select>
                            </div>
                            <div class="input-group col-8">
                                <input type="text" class="form-control New-selectt" id="first_name"  name="first_name" aria-describedby="first_nameHelp" placeholder="First Name">
                            </div>
                        </div>
                    </div>
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <input type="text" class="form-control " id="last_name" name="last_name" aria-describedby="LastNameHelp" placeholder="Last Name">
                        </div>
                    </div>
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Mobile Number/Landline Number
                                <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control New-selectt" id="mobile" minlength="10" maxlength="11" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g, '')" name="mobile" placeholder="Mobile Number/Landline Number">
                            </div>
                        </div>
                    </div>
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Email Address</label>
                            <div class="input-group">
                                <input type="email" class="form-control" id="youremail"  name="youremail"  placeholder="Email Address">
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
                                    <option selected disabled>Select Account Type</option>
                                    <option value="Govt">Govt</option>
                                    <option value="Enterprise">Enterprise</option>
                                    <option value="Reseller">Reseller</option>
                                    <option value="Super-Aggregator">Super-Aggregator</option>
                                    <option value="PSU ">PSU</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Industry Type
                                <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                            </label>
                            <div class="input-group">
                                <select name="industrytypoe" id="industrytypoe" class="custom-select form-control New-selectt">
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
                            <label for="inputfirst_name">Designation
                                <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                            </label>
                            <div class="input-group">
                                <select name="designatoin" id="designatoin"  class="custom-select form-control New-selectt">
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
                            <label for="inputfirst_name">Department
                                <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                            </label>
                            <div class="input-group">
                                <select  name="department"  id="department"  class="custom-select form-control New-selectt">
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
                            <label for="inputfirst_name">Sale Person
                                <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                            </label>
                            <div class="input-group">
                                <select  name="saleperson"  id="saleperson" name="sale_person_id" class="custom-select New-selectt form-control">
                                @if(auth()->user()->role != "Member")<option value="">Select Sales Person</option>@endif
                                <?php foreach ($sales_person as $person) { ?>
                                <option value="{{ $person->id }}" <?php if ($person->id == $user_id) { echo "selected"; } ?>>{{ $person->name }}</option>
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
                                <select  name="source_type" id="source_type" class="custom-select form-control">
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
                                <select  id="source_value"   name="source_value"  class="custom-select form-control">
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
                            <select id="product_type" name="product_type" class="New-selectt custom-select form-control">
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
                            <label for="inputfirst_name">Lead Status
                                <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                            </label>
                            <div class="input-group">
                                <select id="lead_status" name="lead_status"  class="New-selectt custom-select form-control">
                                    <option selected value="">Select Lead Status</option>
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
                                <select  id="payment_type"  name="payment_type"  class="custom-select form-control">
                                    <option selected disabled>Select Payment Type</option>
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
                                <select  name="channel_partner"  id="channel_partner" class="custom-select form-control">
                                    <option selected disabled>Select Channel Partner Involved </option>
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
                                <input type="text" class="form-control " name="channel_competitor" id="channel_competitor" placeholder="Channel Partner Name">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Lead Type
                                <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                            </label>
                            <div class="input-group">
                                <select  name="lead_type"   id="lead_type"  class="New-selectt custom-select form-control">
                                    <option selected value="">Select Lead Type</option>
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
                            <label for="inputfirst_name">Address
                                <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control New-selectt" id="address" name="address" placeholder="Address">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">State
                                <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                            </label>
                            <div class="input-group">
                                <select name="state_id" id="state_id" class="custom-select form-control New-selectt">
                                    <option selected disabled>Select State </option>
                                    <?php foreach ($states as $state) {?>
                                    <option value="{{ $state->id }}" >{{ $state->name }} </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">City
                                <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                            </label>
                            <div class="input-group">
                                <select id="city_id" name="city_id" class="New-selectt custom-select form-control">
                                    <option selected disabled>Select City</option>

                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group remove-botttom-space col-12">
                        <div class="input-group">
                            <label for="inputfirst_name">Pin Code</label>
                            <div class="input-group">
                                <input type="text" class="form-control " onkeyup="if (/\D/g.test(this.value))
                                                this.value = this.value.replace(/\D/g, '')" name="pincode" id="pincode" maxlength="6" placeholder="Pin Code">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Existing -->
            <div class="Existing selectt" style="display: none;margin: 0 0 30px;">
                <div class="row">
                </div>
                <table class="table display" id="dataTable1" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th >Organisation Name</th>
                            <th style="white-space: nowrap;">Contact Person</th>
                            <th>Product</th>
                            <th>Sub Product</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($existing_leads as $lead)
                        {
                            $laeye = 'Cold';
                            if ($lead->lead_type == 1) {
                                $laeye = 'Hot';
                            }
                            if ($lead->lead_type == 1) {
                                $laeye = 'Warm';
                            }
                            if ($lead->lead_type == 1) {
                                $laeye = 'Cold';
                            }

                            if ($lead->status == 'Open') {
                            ?>
                                <tr>
                                    <td>
                                        <input value="{{ $lead->id }}" type="radio" name="existing-lead"/>
                                    </td>
                                    <td>
                                        <span class="first-bold" style="white-space: nowrap;">{{ $lead->organisation }}</span>
                                    </td>
                                    <td>
                                        <span class="first-bold">{{ $lead->first_name }}</span>
                                    </td>
                                    <td>
                                        <span class="first-bold">{{ $lead->producttype }}</span>
                                    </td>
                                    <td>
                                        <span class="first-bold">{{ $lead->subproduct }}</span>
                                    </td>
                                </tr>
                            <?php
                            }
                        }?>
                    </tbody>
                </table>
            </div>
            <div class="text-right">
                <button type="reset" class="btn btn-secondary btn-patient-box mt-3 closeMySidenav">Cancel</button>
                <button type="submit" class="btn btn-primary btn-patient-box mt-3">Submit</button>
                <input type="hidden" name="userid" id="userid" value="{{ $user_id  }}">
            </div>
            {!! csrf_field() !!}
        </form>
    </div>
</div>

@push('scripts')
<script>
$("#saveLeadForm").submit(function (e) {
    if($('input[name="radion"').val() == "New") {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "{{route('checkAddLeadData')}}",
            data: $(this).closest('form').first().serialize(),
            success: function (response) {
                if(response.status) {
                    e.target.submit();
                } else {
                    Swal.fire(
                        'Not Valid Data!',
                        'Lead of this organization with this product is already exist.',
                        'warning'
                    )
                }
            }
        });
    }
})
</script>
@endpush
