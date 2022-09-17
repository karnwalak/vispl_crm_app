
@extends('layouts.layout')

@section('content')



<div class="container-fluid">
    <!-- Content Row -->
    <div class="row counters">
   

    <div class="card-body">
                
        <form method="post" action="{{ SITEURL }}admin/updatelead" class="pb-3 form-fields">
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

            <div class="row">
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Lead Date</label>
                        <div class="input-group">
                            <input type="date" class="form-control " id="lead_date" value="{{ $leadeditata->lead_date }}" name="lead_date" placeholder="Lead Date">
                        </div>
                    </div>
                </div>
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Organisation</label>
                        <div class="input-group">
                            <input type="text" class="form-control " id="organisation" name="organisation" value="{{ $leadeditata->organisation }}" placeholder="Organisation">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <label for="inputfirst_name">Contact Person</label>
                </div>
            </div>
            <div class="row">
                <div class="form-group remove-botttom-space col-6">
                    <div class="row">
                        <div class="col-4" style="padding-right: 0;">
                            <select name="salutation" id="" class="custom-select form-control">
                                <option selected disabled>Salutation</option>
                                <option <?php if($leadeditata->salutation  == 'Mr') { echo 'selected';} ?> value="Mr">Mr</option>
                                <option <?php if($leadeditata->salutation  == 'Mrs') { echo 'selected';} ?> value="Mrs">Mrs</option>
                                <option <?php if($leadeditata->salutation  == 'Ms') { echo 'selected';} ?> value="Ms">Ms</option>
                                <option <?php if($leadeditata->salutation  == 'Miss') { echo 'selected';} ?> value="Miss">Miss</option>
                                <option <?php if($leadeditata->salutation  == 'Dr.') { echo 'selected';} ?> value="Dr.">Dr.</option>
                            </select>
                        </div>
                        <div class="input-group col-8">
                            <input type="text" class="form-control" id="first_name"  name="first_name" value="{{ $leadeditata->first_name }}" aria-describedby="first_nameHelp" placeholder="First Name" required>
                        </div>
                    </div>
                    
                </div>
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <input type="text" class="form-control" id="lastname" name="lastname" value="{{ $leadeditata->last_name }}" aria-describedby="LastNameHelp" placeholder="Last Name" required>
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Mobile number/Landline Number</label>
                        <div class="input-group">
                            <input type="text" class="form-control " id="mobile" maxlength="10" value="{{ $leadeditata->contact_number }}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" name="mobile" placeholder="Mobile number/Landline Number">
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
                                <option <?php if($leadeditata->account_type  == 'Govt') { echo 'selected';} ?> value="Govt">Govt</option>
                                <option <?php if($leadeditata->account_type  == 'Enterprise') { echo 'selected';} ?> value="Enterprise">Enterprise</option>
                                <option <?php if($leadeditata->account_type  == 'Reseller') { echo 'selected';} ?> value="Reseller">Reseller</option>
                                <option <?php if($leadeditata->account_type  == 'Super-Aggregator') { echo 'selected';} ?> value="Super-Aggregator">Super-Aggregator</option>
                                <option <?php if($leadeditata->account_type  == 'PSU') { echo 'selected';} ?> value="PSU">PSU</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Industry Type</label>
                        <div class="input-group">
                            <select name="industrytypoe" id="industrytypoe" class="custom-select form-control">
                                <option selected disabled>Select Industry Type</option>
                                <option <?php if($leadeditata->industry_type  == '1') { echo 'selected';} ?> value="1">One</option>
                                <option <?php if($leadeditata->industry_type  == '2') { echo 'selected';} ?> value="2">Two</option>
                                <option <?php if($leadeditata->industry_type  == '3') { echo 'selected';} ?> value="3">Three</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Designation</label>
                        <div class="input-group">
                            <select name="designatoin" id="designatoin"   class="custom-select form-control">
                                <option selected disabled>Select Designation</option>
                                <option <?php if($leadeditata->designation  == '1') { echo 'selected';} ?> value="1">One</option>
                                <option <?php if($leadeditata->designation  == '2') { echo 'selected';} ?> value="2">Two</option>
                                <option <?php if($leadeditata->designation  == '3') { echo 'selected';} ?> value="3">Three</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Department</label>
                        <div class="input-group">
                            <select  name="department"  id="department"  class="custom-select form-control">
                                <option selected disabled>Select Department</option>
                                <option <?php if($leadeditata->department  == '1') { echo 'selected';} ?> value="1">One</option>
                                <option <?php if($leadeditata->department  == '2') { echo 'selected';} ?> value="2">Two</option>
                                <option <?php if($leadeditata->department  == '3') { echo 'selected';} ?> value="3">Three</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Sale Person</label>
                        <div class="input-group">
                            <select  name="saleperson"  id="saleperson" class="custom-select form-control">
                                <option selected disabled>Select Sale Person</option>
                                <option <?php if($leadeditata->sales_person  == '1') { echo 'selected';} ?> value="1">One</option>
                                <option <?php if($leadeditata->sales_person  == '2') { echo 'selected';} ?> value="2">Two</option>
                                <option <?php if($leadeditata->sales_person  == '3') { echo 'selected';} ?> value="3">Three</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group remove-botttom-space col-12">
                    <label for="inputfirst_name">Lead Details</label>
                    <div class="input-group">
                        <textarea type="text" class="form-control " id="lead_details" name="lead_details"  value="{{ $leadeditata->lead_details }}" placeholder="Lead Details"></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Source Type</label>
                        <div class="input-group">
                            <select  name="source_type" id="source_type" class="custom-select form-control">
                                <option selected disabled>Select Source Type</option>
                                <option <?php if($leadeditata->source_type  == '1') { echo 'selected';} ?> value="1">One</option>
                                <option <?php if($leadeditata->source_type  == '2') { echo 'selected';} ?> value="2">Two</option>
                                <option <?php if($leadeditata->source_type  == '3') { echo 'selected';} ?> value="3">Three</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Source Value</label>
                        <div class="input-group">
                            <select  id="source_value"   name="source_value"  class="custom-select form-control">
                                <option selected disabled>Select Source Value</option>
                                <option <?php if($leadeditata->source_value  == '1') { echo 'selected';} ?> value="1">One</option>
                                <option <?php if($leadeditata->source_value  == '2') { echo 'selected';} ?> value="2">Two</option>
                                <option <?php if($leadeditata->source_value  == '3') { echo 'selected';} ?> value="3">Three</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Product Type</label>
                        <div class="input-group">
                            <select  id="product_type"    name="product_type"   class="custom-select form-control">
                                <option selected disabled>Select Product Type</option>
                                <option <?php if($leadeditata->payment_type  == 'SMS') { echo 'selected';} ?> value="SMS">SMS</option>
                                <option <?php if($leadeditata->payment_type  == 'EMAIL') { echo 'selected';} ?> value="EMAIL">EMAIL</option>
                                <option <?php if($leadeditata->payment_type  == 'VOICE') { echo 'selected';} ?> value="VOICE">VOICE</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">Lead Status</label>
                        <div class="input-group">
                            <select id="lead_status" name="lead_status"  class="custom-select form-control">
                                <option selected disabled>Select Lead Status</option>
                                <option <?php if($leadeditata->lead_status  == '1') { echo 'selected';} ?> value="1">Hot</option>
                                <option <?php if($leadeditata->lead_status  == '2') { echo 'selected';} ?> value="2">Warm</option>
                                <option <?php if($leadeditata->lead_status  == '3') { echo 'selected';} ?> value="3">Cold</option>
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
                                <option selected disabled>Select Sub Product</option>
                                <option <?php if($leadeditata->sub_product  == 'OBD') { echo 'selected';} ?> value="OBD">OBD</option>
                                <option <?php if($leadeditata->sub_product  == 'IBD') { echo 'selected';} ?> value="IBD">EMAIL</option>
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
                                <option <?php if($leadeditata->product_type  == 'Prepaid') { echo 'selected';} ?> value="Prepaid">Prepaid</option>
                                <option <?php if($leadeditata->product_type  == 'Postpaid') { echo 'selected';} ?> value="Postpaid">Postpaid</option>
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
                                <option <?php if($leadeditata->channel_partner  == 'Yes') { echo 'selected';} ?> value="Yes">Yes</option>
                                <option <?php if($leadeditata->channel_partner  == 'No') { echo 'selected';} ?> value="No">No</option>
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
                        <label for="inputfirst_name">Lead Type</label>
                        <div class="input-group">
                            <select  name="lead_type"   id="lead_type"  class="custom-select form-control">
                                <option selected disabled>Select Lead Type</option>
                                <option <?php if($leadeditata->lead_type  == '1') { echo 'selected';} ?> value="1">New</option>
                                <option <?php if($leadeditata->lead_type  == '2') { echo 'selected';} ?> value="2">Existing</option>
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
                        <label for="inputfirst_name">Address</label>
                        <div class="input-group">
                            <input type="text" class="form-control " id="address" value="{{ $leadeditata->address }}" name="address" placeholder="Address">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">State</label>
                        <div class="input-group">
                            <select name="state_id" id="state_id" class="custom-select form-control">
                                <option selected disabled>Select State </option>
                                <option <?php if($leadeditata->state_id  == '1') { echo 'selected';} ?> value="1">One</option>
                                <option <?php if($leadeditata->state_id  == '2') { echo 'selected';} ?> value="2">Two</option>
                                <option <?php if($leadeditata->state_id  == '3') { echo 'selected';} ?> value="3">Three</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputfirst_name">City</label>
                        <div class="input-group">
                            <select name="city_id" name="city_id" class="custom-select form-control">
                                <option selected disabled>Select City</option>
                                <option <?php if($leadeditata->city_id  == '1') { echo 'selected';} ?> value="1">One</option>
                                <option <?php if($leadeditata->city_id  == '2') { echo 'selected';} ?> value="2">Two</option>
                                <option <?php if($leadeditata->city_id  == '3') { echo 'selected';} ?> value="3">Three</option>
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
                            <input type="text" class="form-control " value="{{ $leadeditata->pincode }}" onkeyup="if (/\D/g.test(this.value))  this.value = this.value.replace(/\D/g,'')" name="pincode" id="pincode" maxlength="6" placeholder="Pin Code">
                        </div>
                    </div>
                </div>
            </div>


            <div class="text-right">
                <button type="reset" class="btn btn-secondary mr-3 btn-patient-box closeMySidenav">Cancel</button>
                <button type="submit" class="btn btn-primary btn-patient-box">Submit</button>
                <input type="hidden" name="userid" id="userid" value="{{ auth()->user()->id  }}">
                <input type="hidden" name="editid" id="editid" value="{{ $leadeditata->id }}"">
            </div>
            {!! csrf_field() !!}
        </form>
    </div>

</div>











@endsection		