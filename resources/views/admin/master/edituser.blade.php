@extends('layouts.layout')
@section('content')
<div class="container-fluid">
    <div class="row counters">
        <div class="col-9 mb-4">
            @if (Session::has('sucmessage'))
            <div class="alert alert-success" role="alert">
                {{ Session::get('sucmessage') }}
            </div>
            @endif
            @if (Session::has('message'))
            <div class="alert alert-danger" role="alert">
                {{ Session::get('message') }}
            </div>
            @endif
            <!-- Project Card Example -->
            <div class="card shadow  mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h1 class="m-0 font-weight-bold text-primary head-font-size">Edit User </h1>
                    <!-- <a href="javascript:void(0);" id="openMySidenav1" class="btn btn-primary btn-patient-box openMySidenav1" role="button"> -->
<!-- <i class="fas fa-plus fa-sm font-icn-color"></i> -->
                    <!-- Add Patient1 -->
                    <!-- </a> -->
                </div>
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <form action="{{ SITEURL }}admin/updateprofile" method="post" class="pb-3 form-fields">
                            <div class="row">
                                <div class="form-group remove-botttom-space col-12">
                                    <div class="input-group">
                                        <label for="inputFirstName">Employee Name</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control "  maxlength="200"  id="username" name="username" value="{{ $usersdata->name }}" placeholder="Employee Name">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group remove-botttom-space col-12">
                                    <div class="input-group">
                                        <label for="inputFirstName">Employee Email</label>
                                        <div class="input-group">
                                            <input type="email" class="form-control "  maxlength="150"  id="useremail" name="useremail" value="{{ $usersdata->email }}" placeholder="Employee Email">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group remove-botttom-space col-12">
                                    <div class="input-group">
                                        <label for="inputFirstName">Employee Mobile number</label>
                                        <div class="input-group">
                                            <input type="text" onkeyup="if (/\D/g.test(this.value))
                                                        this.value = this.value.replace(/\D/g, '')" maxlength="10" class="form-control " id="usermobileno" name="usermobileno" value="{{ $usersdata->mobileno }}" placeholder="Employee Mobile">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group remove-botttom-space col-12">
                                    <div class="input-group">
                                        <label for="inputFirstName">Employee Desigation</label>
                                        <div class="input-group">
                                            <select name="designation" id="designation"  class="custom-select form-control">
                                                <option selected disabled>Select Designation</option>
                                                <?php $regioata = DB::table('designation')->orderBy('id', 'DESC')->get();
                                                foreach ($regioata as $regionda) {
                                                    ?>
                                                    <option <?php if ($regionda->id == $usersdata->desgnationid) {
                                                        echo 'selected';
                                                    } ?> value="{{ $regionda->id }}">{{ $regionda->designationname }}</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group remove-botttom-space col-12">
                                    <div class="input-group">
                                        <label for="inputFirstName">Role</label>
                                        <div class="input-group">
                                            <select name="role" id="role" class="custom-select form-control" required>
                                                <option value="">Select Role</option>
                                                <option value="Member" <?php if ($usersdata->role == 'Member') { echo 'selected';} ?>>Member</option>
                                                <option value="State Level Admin" <?php if ($usersdata->role == 'State Level Admin') { echo 'selected';} ?>>State Level Admin</option>
                                                <option value="Zonal Level Admin" <?php if ($usersdata->role == 'Zonal Level Admin') { echo 'selected';} ?>>Zonal Level Admin</option>
                                                <option value="Country Head" <?php if ($usersdata->role == 'Country Head') { echo 'selected';} ?>>Country Head</option>
                                                <option value="Admin" <?php if ($usersdata->role == 'Admin') { echo 'selected';} ?>>Admin</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="zonerow">
                                <div class="form-group remove-botttom-space col-12">
                                    <div class="input-group">
                                        <label for="inputFirstName">Employee Zone</label>
                                        <div class="input-group">
                                            <select name="zone" id="zone" class="custom-select form-control">
                                                <option selected disabled>Select Zone</option>
                                                <?php $regiondata = DB::table('regiondata')->where('regioblavel', 1)->orderBy('id', 'DESC')->get();
                                                foreach ($regiondata as $regionda) {
                                                    ?>
                                                        <option <?php if ($regionda->id == $usersdata->zone) {
                                                        echo 'selected';
                                                    } ?> value="{{ $regionda->id }}">{{ $regionda->regionname }}</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="staterow">
                                <div class="form-group remove-botttom-space col-12">
                                    <div class="input-group">
                                        <label for="inputFirstName">Employee State</label>
                                        <div class="input-group">
                                            <div id="statedata">
                                            <?php
                                            if ($usersdata->zone) {
                                                $regiondata = DB::table('regiondata')->where('regionparent', $usersdata->zone)->orderBy('id', 'DESC')->first();
                                                $stateid = explode(',', $regiondata->regionname);
                                                $stateiddata = explode(',', $usersdata->stateid);
                                                foreach ($stateid as $statei) {
                                                    ?>
                                                    <label  style="display:block; width:100%; padding-bottom:5px;">
                                                        <input type="checkbox" <?php if (in_array($statei, $stateiddata)) { echo 'checked'; }?> name="statename[]" id="statename" value="<?php echo $statei; ?>"><?php echo $statei; ?>
                                                    </label>
                                                <?php } ?>
                                            <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group remove-botttom-space col-12">
                                    <div class="input-group">
                                        <label for="inputFirstName">Employee Address</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control "  maxlength="200"  id="useraddress" name="useraddress" value="{{ $usersdata->address }}" placeholder="Employee Name">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group remove-botttom-space col-12">
                                    <div class="input-group">
                                        <label for="inputFirstName">Employee Pincode</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control " onkeyup="if (/\D/g.test(this.value))
                                                        this.value = this.value.replace(/\D/g, '')" maxlength="6" id="userpincode" name="userpincode" value="{{ $usersdata->pincode }}" placeholder="Employee Name">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <input type="hidden" name="editid" value="{{ $usersdata->id  }}">
                                <a href="{{ route('empolyees')}}">
                                    <button type="button" class="btn btn-secondary mr-3 btn-patient-box "  data-dismiss="modal">Cancel</button>
                                </a>
                                <button type="submit" class="btn btn-primary btn-patient-box">Update</button>
                            </div>
                            {!! csrf_field() !!}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
<script>
// A $( document ).ready() block.
$(document).ready(function () {
    $("#zone").change(function () {
        var kdf = $(this).val();
        var urldata = '{{ SITEURL }}ajaxfiles/getdata.php?action=loadzones&zoned=' + kdf;
        //  alert(urldata);
        $('#statedata').load(urldata);
    });
    var user_role = '<?php echo $usersdata->role;?>';
    if (user_role == 'Country Head') {
        $('#zone').val('');
        $('#statedata').empty();
        $('#zonerow').hide();
        $('#staterow').hide();
    }
    $("#role").change(function () {
        if ($(this).val() == 'Country Head') {
            $('#zone').val('');
            $('#statedata').empty();
            $('#zonerow').hide();
            $('#staterow').hide();
        } else {
            $('#zonerow').show();
            $('#staterow').show();
        }
    });
});
</script>
@endsection
