@extends('layouts.layout')

@section('content')



<div class="container-fluid">
                        <!-- Content Row -->
                        <div class="row counters">
                            <!-- Content Column -->
                            <div class="col-6 mb-4">
                               
                                <!-- Project Card Example -->
                                <div class="card shadow  mb-4">
                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h1 class="m-0 font-weight-bold text-primary head-font-size">Add Department </h1>
                                        <a data-toggle="modal" href="#Designation" class="btn btn-primary btn-patient-box" role="button">
                                        <i class="fas fa-plus fa-sm font-icn-color"></i>
                                            Add Department  
                                        </a>
										<!-- <a href="javascript:void(0);" id="openMySidenav1" class="btn btn-primary btn-patient-box openMySidenav1" role="button"> -->
                                        <!-- <i class="fas fa-plus fa-sm font-icn-color"></i> -->
                                        <!-- Add Patient1 -->
                                        <!-- </a> -->
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive data-list">
                                            <table class="table" id="" width="100%" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Department</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Department</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
												<?php foreach($departmentdata as $departmentda) { ?>
                                                    <tr>
                                                        <td>{{  date("D, d M Y", strtotime($departmentda->createdtme))}}  </td>
                                                        <td><span style="white-space: nowrap;">{{ $departmentda->name }}</span></td>
                                                        <td>
                                                         
                                                            <a href="{{ SITEURL }}admin/editdepartment/{{ $departmentda->id }}" class="btn btn-success icon-btn openMySidenav"><i class="fa fa-edit"></i></a>
                                                           
                                                        </td>
                                                    </tr>
												<?php } ?>
													
                                                    
                                                   
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mb-4">
                               
                                <!-- Project Card Example -->
                                <div class="card shadow  mb-4">
                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h1 class="m-0 font-weight-bold text-primary head-font-size">Add Designation</h1>
                                        <a data-toggle="modal" href="#Department"  class="btn btn-primary btn-patient-box" role="button">
                                        <i class="fas fa-plus fa-sm font-icn-color"></i>
                                            Add Designation
                                        </a>
										<!-- <a href="javascript:void(0);" id="openMySidenav1" class="btn btn-primary btn-patient-box openMySidenav1" role="button"> -->
                                        <!-- <i class="fas fa-plus fa-sm font-icn-color"></i> -->
                                        <!-- Add Patient1 -->
                                        <!-- </a> -->
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive data-list">
                                            <table class="table" id="" width="100%" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Designation</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Designation</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                    <?php foreach($designation as $departmentda) { ?>
                                                    <tr>
                                                        <td>{{  date("D, d M Y", strtotime($departmentda->createdtime))}}  </td>
                                                        <td><span style="white-space: nowrap;">{{ $departmentda->designationname }}</span></td>
                                                        <td>
                                                         
                                                            <a href="{{ SITEURL }}admin/editdesignation/{{ $departmentda->id }}" class="btn btn-success icon-btn openMySidenav"><i class="fa fa-edit"></i></a>
                                                           
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

                   
                    
                     <!--------------Designation----------------->
                     <div id="Designation" class="modal fade " role="dialog">
                        <div class="modal-dialog">
                        <!-- Modal content-->
                            <div class="modal-content">
                                <div class="card-header d-flex flex-row align-items-center justify-content-between card-header-bckcolor">
                                    <h6 class="m-0 font-weight-bold text-primary">Add Department</h6>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                        <i class="fas fa-times fa-sm"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ SITEURL }}admin/savedepartment" method="post" class="pb-3 form-fields">                                
                                        <div class="row">
                                            <div class="form-group remove-botttom-space col-12">
                                                <div class="input-group">
                                                    <label for="inputFirstName">Department</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control " id="designation" name="designation"  placeholder="Designation Name">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
										<input type="hidden" name="userid" value="{{ Auth::user()->id }}">
										{!! csrf_field() !!}
                                            <button type="button" class="btn btn-secondary mr-3 btn-patient-box "  data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary btn-patient-box">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--------------Department----------------->
                    <div id="Department" class="modal fade " role="dialog">
                        <div class="modal-dialog">
                        <!-- Modal content-->
                            <div class="modal-content">
                                <div class="card-header d-flex flex-row align-items-center justify-content-between card-header-bckcolor">
                                    <h6 class="m-0 font-weight-bold text-primary">Add Designation</h6>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                        <i class="fas fa-times fa-sm"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form class="pb-3 form-fields" action="{{ SITEURL }}admin/savedesignation" method="post" >                                
                                        <div class="row">
                                            <div class="form-group remove-botttom-space col-12">
                                                <div class="input-group">
                                                    <label for="inputFirstName">Designation</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control " id="designation" name="designation"  placeholder="Designation Name">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
										{!! csrf_field() !!}
										<input type="hidden" name="userid" value="{{ Auth::user()->id }}">
                                            <button type="button" class="btn btn-secondary mr-3 btn-patient-box "  data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary btn-patient-box">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>




@endsection	