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
                    <h1 class="m-0 font-weight-bold text-primary head-font-size">Add Employee</h1>
                    <a href="javascript:void(0);" id="openMySidenav" class="btn btn-primary btn-patient-box openMySidenav" role="button">
                        <i class="fas fa-plus fa-sm font-icn-color"></i>
                        Add Employee
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <table class="table" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee name </th>
                                    <th>Mobile Number</th>
                                    <th>Designation</th>
                                    <th>Zone</th>
                                    <th>State</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $coutnu = 1;
                                foreach ($usersdaasetting as $usersdaasett) {
                                    $designationname = 'N/A';
                                    $stateame = '';
                                    $designation = DB::table('designation')->where('id', $usersdaasett->desgnationid)->first();
                                    if ($designation) {
                                        $designationname = $designation->designationname;
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo $coutnu ?></td>
                                        <td>
                                            {{ $usersdaasett->name }}
                                        </td>
                                        <td>
                                            {{ $usersdaasett->mobileno }}
                                        </td>
                                        <td>
                                            {{ $designationname }}
                                        </td>
                                        <td>
                                            {{ $usersdaasett->regionname }}
                                        </td>
                                        <td>
                                            {{ $usersdaasett->stateid }}
                                        </td>
                                        <td>
                                            <a data-toggle="modal" href="#myModal{{ $usersdaasett->id }}" class="btn btn-primary icon-btn"><i class="fa fa-eye"></i></a>
                                            <a href="{{ SITEURL }}admin/edituser/{{$usersdaasett->id  }}" class="btn btn-success icon-btn"><i class="fa fa-edit"></i></a>
                                            <a href="{{ SITEURL }}admin/changepassword?userid={{$usersdaasett->id  }}" class="btn btn-success icon-btn"><i class="fa fa-key"></i></a>
                                            <!-- Modal -->
                                            <div id="myModal{{ $usersdaasett->id }}" class="modal fade" role="dialog">
                                                <div class="modal-dialog">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">User {{ $usersdaasett->name }} Details: </h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table table-hover">
                                                                <tbody>
                                                                    <tr><th>Employee Name </th><td>{{ $usersdaasett->name }}</td>  </tr>
                                                                    <tr><th>Employee Email</th><td>{{ $usersdaasett->email }}</td>  </tr>
                                                                    <tr><th>Employee Mobile</th><td>{{ $usersdaasett->mobileno }}</td>  </tr>
                                                                    <tr><th>Employee Address</th><td>{{ $usersdaasett->address }}</td>  </tr>
                                                                    <tr><th>Employee Zone</th><td>{{ $usersdaasett->regionname }}</td>  </tr>
                                                                    <tr><th>Employee State</th><td>{{ $usersdaasett->stateid }}</td>  </tr>
                                                                    <tr><th>Employee Desigation</th><td>{{ $designationname }}</td>  </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php $coutnu = $coutnu + 1;
                                } ?>
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
        <h6 class="m-0 font-weight-bold text-primary">Add Employee</h6>
        <a href="javascript:void(0);" class="closeMySidenav" id="closeMySidenav">
            <i class="fas fa-times fa-sm"></i>
        </a>
    </div>
    <div class="card-body">
        <form method="post" onsubmit="return onFormSubmitHandler();" action="{{ SITEURL }}admin/userregistration" class="pb-3 form-fields">
            <div class="row">
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputFirstName">Employee Name<span style="display:inline-block; font-size:16px; color:#f12605 "> * </span></label>
                        <div class="input-group">
                            <input type="text" class="form-control " id="employee" maxlength="200" name="employee" placeholder="Employee Name" required>
                        </div>
                    </div>
                </div>
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputFirstName">Employee Email<span style="display:inline-block; font-size:16px; color:#f12605 "> * </span></label>
                        <div class="input-group">
                            <input type="email" class="form-control " id="employeeemail" maxlength="150" name="employeeemail" placeholder="Employee Email" required>
                        </div>
                    </div>
                </div>
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputFirstName">Employee Password<span style="display:inline-block; font-size:16px; color:#f12605 "> * </span></label>
                        <div class="input-group">
                            <input type="password" class="form-control " maxlength="20" id="password" name="password" placeholder="Employee Password" required>
                        </div>
                    </div>
                </div>
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputFirstName">Employee Mobile</label>
                        <div class="input-group">
                            <input type="text" onkeyup="if (/\D/g.test(this.value))
                                        this.value = this.value.replace(/\D/g, '')" maxlength="10" minlength="10" class="form-control " id="employeemobile" name="employeemobile" required placeholder="Employee Mobile">
                        </div>
                    </div>
                </div>
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputFirstName">Designation </label>
                        <div class="input-group">

                            <select name="designation" id="designation"  class="custom-select form-control">
                                <option value="">Select Designation</option>
                                <?php $regioata = DB::table('designation')->orderBy('id', 'DESC')->get();
                                foreach ($regioata as $regionda) {
                                ?>
                                <option value="{{ $regionda->id }}">{{ $regionda->designationname }}</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputFirstName">Role<span style="display:inline-block; font-size:16px; color:#f12605 "> * </span></label>
                        <div class="input-group">
                            <select name="role" id="role"  class="custom-select form-control" required>
                                <option value="">Select Role</option>
                                <option value="Member">Member</option>
                                <option value="Zonal Level Admin">Zonal Level Admin</option>
                                <option value="Country Head">Country Head</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="zonerow">
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputFirstName">Zone </label>
                        <div class="input-group">
                            <select name="zone" id="zone"  class="custom-select form-control">
                                <option value="">Select Zone</option>
                                <?php $regiondata = DB::table('regiondata')->where('regioblavel', 1)->orderBy('id', 'DESC')->get();
                                foreach ($regiondata as $regionda) {
                                ?>
                                    <option value="{{ $regionda->id }}">{{ $regionda->regionname }}</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputFirstName">State </label>
                        <div class="input-group">
                            <div id="statedata"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputFirstName">Pin Code</label>
                        <div class="input-group">
                            <input type="text" class="form-control" onkeyup="if (/\D/g.test(this.value))
                                        this.value = this.value.replace(/\D/g, '')" id="pincode" maxlength="6" name="pincode" placeholder="Pin Code">
                        </div>
                    </div>
                </div>
                <div class="form-group remove-botttom-space col-6">
                    <div class="input-group">
                        <label for="inputFirstName">Address</label>
                        <div class="input-group">
                            <input type="text" class="form-control " id="address" maxlength="200" name="address" placeholder="Address">
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right">
                {!! csrf_field() !!}
                <button type="reset" class="btn btn-secondary mr-3 btn-patient-box closeMySidenav">Cancel</button>
                <button type="submit" class="btn btn-primary btn-patient-box">Submit</button>
            </div>
        </form>
    </div>
</div>
<!-- Sidenav-->

<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
<script>
// A $( document ).ready() block.
$(document).ready(function () {
    $("#zone").change(function () {
        if ($(this).val() != '') {
            var kdf = $(this).val();
            var urldata = '{{ SITEURL }}ajaxfiles/getdata.php?action=loadzones&zoned=' + kdf;
            //alert(urldata);
            $('#statedata').load(urldata);
        } else {
            $('#statedata').empty();
        }
    });

    $("#role").change(function () {
        if ($(this).val() == 'Country Head') {
            $('#zone').val('');
            $('#statedata').empty();
            $('#zonerow').hide();
        } else {
            $('#zonerow').show();
        }
    });
});
var mobileNumberExist = false;
$('#employeemobile').keyup(function () {
    if($(this).val().length == 10){
        $.ajax({
            type: "GET",
            url: "{{ route('checkMobileNumber' )}}",
            data: {
                id: null,
                mobilenumber: $(this).val()
            },
            success: function (response) {
                if(!response.status) {
                    mobileNumberExist = true;
                    alert("This mobile number is already exist");
                } else {
                    mobileNumberExist = false;
                }
            }
        });
    }
});

function onFormSubmitHandler() {
    if(mobileNumberExist) {
        alert("This mobile number is already exist");
        return false;
    }
    return true;
}
</script>
@endsection
