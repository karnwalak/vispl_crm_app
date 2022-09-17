<div id="mySidenav" class="sidenav shadow width-560 mbr-sidenav">
    <div class="card-header d-flex flex-row align-items-center justify-content-between card-header-bckcolor">
        <h6 class="m-0 font-weight-bold text-primary">Add Lead</h6>
        <a href="javascript:void(0);" class="closeMySidenav" id="closeMySidenav">
            <i class="fas fa-times fa-sm"></i>
        </a>
    </div>
    <div class="card-body">
        <form method="post" id="saveLeadForm" action="{{ SITEURL }}admin/savelead" class="pb-3 form-fields">
            <div class="row">
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Lead Date <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span> </label>
                        <div class="input-group">
                            <input type="text" class="form-control datetimepicker" id="lead_date" name="lead_date" placeholder="Lead Date" value="{{ date('Y-m-d') }}" required>
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
                                <option value="">Salutation</option>
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
                        <input type="text" class="form-control" id="last_name" name="last_name" aria-describedby="LastNameHelp" placeholder="Last Name">
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
                        <label for="inputfirst_name">Email Address</label>
                        <div class="input-group">
                            <input type="email" class="form-control " id="youremail"  name="youremail"  placeholder="Email Address">
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
                        <label for="inputfirst_name">Pin Code</label>
                        <div class="input-group">
                            <input type="text" class="form-control " onkeyup="if (/\D/g.test(this.value))
                                        this.value = this.value.replace(/\D/g, '')" name="pincode" id="pincode" maxlength="6" placeholder="Pin Code">
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <button type="reset" class="btn btn-secondary mr-3 btn-patient-box closeMySidenav">Cancel</button>
                <button type="submit" id="submit-btn" class="btn btn-primary btn-patient-box">Submit</button>
                <input type="hidden" name="userid" id="userid" value="{{ $userid }}">
            </div>
            {!! csrf_field() !!}
        </form>
    </div>
</div>
@push('scripts')
<script>
$("#saveLeadForm").submit(function (e) {
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
})
</script>
@endpush
