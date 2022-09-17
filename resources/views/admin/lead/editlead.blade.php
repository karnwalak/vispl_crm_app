@extends('layouts.layout')
@section('content')
<div class="container-fluid">
    <!-- Content Row -->
    <div class="row counters">
        <div class="card-body">
        <?php if ($leadeditata->status == 'Open') { ?>
            <form method="post" id="saveLeadForm" action="{{ SITEURL }}admin/updatelead" class="pb-3 form-fields">
                <input type="hidden" name="oldstatus" id="oldstatus" value="{{ $leadeditata->lead_status }}">
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
                <div class="row">
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Lead Date <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span> </label>
                            <div class="input-group">
                                <input type="text" class="form-control datetimepicker"  value="{{ $leadeditata->lead_date ? date('d/m/Y H:i a', strtotime($leadeditata->lead_date)) : "" }}" name="lead_date" placeholder="Lead Date" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Organisation <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span> </label>
                            <div class="input-group">
                                <input type="text" class="form-control " id="organisation" name="organisation" value="{{ $leadeditata->organisation }}" placeholder="Organisation" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="inputfirst_name">Contact Person  <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span> </label>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group remove-botttom-space col-6">
                        <div class="row">
                            <div class="col-4" style="padding-right: 0;">
                                <select name="salutation" id="" class="custom-select form-control" required>
                                    <option  value="">Salutation</option>
                                    <option <?php if ($leadeditata->salutation == 'Mr') { echo 'selected'; } ?> value="Mr">Mr</option>
                                    <option <?php if ($leadeditata->salutation == 'Mrs') { echo 'selected'; } ?> value="Mrs">Mrs</option>
                                    <option <?php if ($leadeditata->salutation == 'Ms') { echo 'selected'; } ?> value="Ms">Ms</option>
                                    <option <?php if ($leadeditata->salutation == 'Miss') { echo 'selected'; } ?> value="Miss">Miss</option>
                                    <option <?php if ($leadeditata->salutation == 'Dr.') { echo 'selected'; } ?> value="Dr.">Dr.</option>
                                </select>
                            </div>
                            <div class="input-group col-8">
                                <input type="text" class="form-control" id="first_name"  name="first_name" value="{{ $leadeditata->first_name }}" aria-describedby="first_nameHelp" placeholder="First Name" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $leadeditata->last_name }}" aria-describedby="LastNameHelp" placeholder="Last Name" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Mobile number/Landline Number <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span></label>
                            <div class="input-group">
                                <input type="text" class="form-control " id="mobile" maxlength="10" value="{{ $leadeditata->contact_number }}" onkeyup="if (/\D/g.test(this.value))
                                            this.value = this.value.replace(/\D/g, '')" name="mobile" placeholder="Mobile number/Landline Number" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Email Address</label>
                            <div class="input-group">
                                <input type="email" class="form-control " id="youremail" value="{{ $leadeditata->email_id }}"  name="youremail"  placeholder="Email Address">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Competitor</label>
                            <div class="input-group">
                                <input type="text" class="form-control " id="competitor" value="{{ $leadeditata->competitor_name }}" name="competitor" placeholder="Competitor">
                            </div>
                        </div>
                    </div>
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Account Type</label>
                            <div class="input-group">
                                <select id="account_type" name="account_type"class="custom-select form-control">
                                    <option value=""> Select Account Type </option>
                                    <?php foreach ($account_types as $type) { ?>
                                    <option value="{{ $type->accounttype }}" {{ $type->accounttype == $leadeditata->account_type ? "selected" : "" }}>{{ $type->accounttype }}</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Industry Type <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>  </label>
                            <div class="input-group">
                                <select name="industrytypoe" id="industrytypoe" class="custom-select form-control" required>
                                    <option value="">Select Industry Type</option>
                                    <?php foreach ($industry_types as $industry) { ?>
                                    <option value="{{ $industry->id }}" {{ $industry->id == $leadeditata->industry_type ? "selected" : "" }}>{{ $industry->industrytype }} </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Designation <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span> </label>
                            <div class="input-group">
                                <select name="designatoin" id="designatoin"   class="custom-select form-control" required>
                                    <option  value="">Select Designation</option>
                                    <?php foreach ($designations as $designation) { ?>
                                    <option value="{{ $designation->id }}" {{ $designation->id == $leadeditata->designation ? "selected" : "" }}>{{ $designation->designationname }}</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Department <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span> </label>
                            <div class="input-group">
                                <select  name="department"  id="department"  class="custom-select form-control" required>
                                    <option  value="">Select Department</option>
                                    <?php foreach ($departments as $department) { ?>
                                    <option value="{{ $department->id }}" {{ $department->id == $leadeditata->department ? "selected" : "" }}>{{ $department->name }}</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Sale Person <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span> </label>
                            <div class="input-group">
                                <select  name="saleperson"  id="saleperson" class="custom-select form-control" required>
                                    <option  value="">Select Sale Person</option>
                                    <?php foreach ($sales_person as $person) { ?>
                                    <option value="{{ $person->id }}" {{ $person->id == $leadeditata->sales_person ? "selected" : "" }}>{{ $person->name }}</option>
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
                            <textarea type="text" class="form-control " id="lead_details" name="lead_details"  value="{{ $leadeditata->lead_details }}" placeholder="Lead Details"><?php echo $leadeditata->lead_details ?></textarea>
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
                                    <option value="{{ $type->id }}" {{ $type->id == $leadeditata->source_type ? "selected" : "" }}>{{ $type->sourcetype }}</option>
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
                                    <option  value="">Select Source Value</option>
                                    <?php foreach ($source_values as $value) { ?>
                                    <option value="{{ $value->id }}" {{ $value->id == $leadeditata->source_value ? "selected" : "" }}>{{ $value->sourcevalue }}</option>
                                    <?php } ?>
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
                                <select  id="product_type"    name="product_type"   class="custom-select form-control" required>
                                    <option  value="">Select Product Type</option>
                                    <?php foreach ($product_types as $type) { ?>
                                    <option value="{{ $type->id }}" {{ $type->id == $leadeditata->product_type ? "selected" : "" }}>{{ $type->producttype }}</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Lead Status</label>
                            <div class="input-group">
                                <select id="lead_status" name="lead_status"  class="custom-select form-control">
                                    <option value="">Select Lead Status</option>
                                    <option value="1" {{ $leadeditata->lead_status == '1' ? 'selected' : '' }}>Hot</option>
                                    <option value="2" {{ $leadeditata->lead_status == '2' ? 'selected' : '' }}>Warm</option>
                                    <option value="3" {{ $leadeditata->lead_status == '3' ? 'selected' : '' }}>Cold</option>
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
                                <select id="sub_product"   name="sub_product"  class="custom-select form-control">
                                    <option  value="">Select Sub Product</option>
                                    <?php foreach ($sub_products as $product) { ?>
                                    <option value="{{ $product->id }}" {{ $product->id == $leadeditata->sub_product ? "selected" : "" }}>{{ $product->producttype }}</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Payment Type</label>
                            <div class="input-group">
                                <select  id="payment_type"  name="payment_type"  class="custom-select form-control">
                                    <option value="">Select Payment Type</option>
                                    <option value="Prepaid"  {{ $leadeditata->payment_type == 'Prepaid' ? 'selected' : '' }}>Prepaid</option>
                                    <option value="Postpaid" {{ $leadeditata->payment_type == 'Postpaid' ? 'selected' : '' }}>Postpaid</option>
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
                                <select  name="channel_partner" id="channel_partner" class="custom-select form-control">
                                    <option value="">Select Channel Partner Involved </option>
                                    <option value="Yes" {{ $leadeditata->channel_partner == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No"  {{ $leadeditata->channel_partner == 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">Channel Partner Name</label>
                            <div class="input-group">
                                <input type="text" class="form-control " name="channel_competitor" id="channel_competitor" value="{{ $leadeditata->channel_competitor }}" placeholder="Channel Partner Name">
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
                                    <option  value="">Select Lead Type</option>
                                    <option value="1" {{ $leadeditata->lead_type == '1' ? 'selected' : '' }}>New</option>
                                    <option value="2" {{ $leadeditata->lead_type == '2' ? 'selected' : '' }}>Existing</option>
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
                                <input type="text" class="form-control " id="address" value="{{ $leadeditata->address }}" name="address" placeholder="Address" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group remove-botttom-space col-6">
                        <div class="input-group">
                            <label for="inputfirst_name">State <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span> </label>
                            <div class="input-group">
                                <select name="state_id" id="state_id" class="custom-select form-control" required >
                                    <option  value="">Select State </option>
                                    <?php foreach ($states as $state) {?>
                                    <option value="{{ $state->id }}" {{ $state->id == $leadeditata->state_id ? 'selected' : '' }}>{{ $state->name }} </option>
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
                                    <option  value="">Select City</option>
                                    <?php foreach ($cities as $city) {?>
                                    <option value="{{ $city->id }}" {{ $city->id == $leadeditata->city_id ? 'selected' : '' }}>{{ $city->name }} </option>
                                    <?php } ?>
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
                                <input type="text" class="form-control " value="{{ $leadeditata->pincode }}" onkeyup="if (/\D/g.test(this.value))
                                            this.value = this.value.replace(/\D/g, '')" name="pincode" id="pincode" maxlength="6" placeholder="Pin Code">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <a href="{{ route('leadmanagement')}}">
                        <button type="button" class="btn btn-secondary mr-3 btn-patient-box closeMySidenav">Cancel</button>
                    </a>
                    <button type="submit" class="btn btn-primary btn-patient-box">Submit</button>
                    <input type="hidden" name="userid" id="userid" value="{{ auth()->user()->id  }}">
                    <input type="hidden" name="editid" id="editid" value="{{ $leadeditata->id }}">
                </div>
                {!! csrf_field() !!}
            </form>
        <?php } else {?>
            <div style="text-align: center;color: red">Lead is Closed</div>
        <?php } ?>
        </div>
    </div>
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
        //alert(urldata);
        $("#city_id").load(urldata);
    });

    $("#product_type").change(function () {
        var kdf = $(this).val();
        var urldata = '{{ SITEURL }}ajaxfiles/getdata.php?action=loadsubprodct&id=' + kdf;
        $("#sub_product").load(urldata);
    });
});
$(function () {
    var dtToday = new Date();

    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();

    if (month < 10)
        month = '0' + month.toString();
    if (day < 10)
        day = '0' + day.toString();

    var maxDate = year + '-' + month + '-' + day;
    $('#lead_date').attr('max', maxDate);
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
</script>
@endsection

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
