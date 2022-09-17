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
                    <h1 class="m-0 font-weight-bold text-primary head-font-size">Change Password</h1>
                    <?php
                    $userid = Auth::user()->id;
                    if (isset($_REQUEST['userid'])) {
                        $userid = $_REQUEST['userid'];
                    }
                    ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive data-list">
                        <form action="{{ SITEURL }}admin/updatepassword" method="post" class="pb-3 form-fields">
                            <div class="row">
                                <div class="form-group remove-botttom-space col-12">
                                    <div class="input-group">
                                        <label for="inputFirstName">New Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control " id="password" name="password"   placeholder="Enter New Password">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group remove-botttom-space col-12">
                                    <div class="input-group">
                                        <label for="inputFirstName">Confirm Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control " id="confirmpassword" name="confirmpassword" placeholder="Enter New Password">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <input type="hidden" name="userid" value="{{ $userid  }}">
                                <a href="{{ url('/admin') }}">
                                    <button type="button" class="btn btn-secondary mr-3 btn-patient-box closeMySidenav">Cancel</button>
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
@endsection
