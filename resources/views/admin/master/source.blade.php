@extends('layouts.layout')

@section('content')



<div class="container-fluid">
                        <!-- Content Row -->
                        <div class="row counters">
                            <!-- Content Column -->
                            <div class="col-6 mb-4">
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
                                        <h1 class="m-0 font-weight-bold text-primary head-font-size">Add Source Type </h1>
                                        <a data-toggle="modal" href="#Designation" class="btn btn-primary btn-patient-box" role="button">
                                        <i class="fas fa-plus fa-sm font-icn-color"></i>
                                            Add Source Type
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
                                                        <th>Source Type</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Source Type</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                    <?php foreach($sourcetype as $sourcet) { ?>
                                                        <tr>
                                                            <td>{{  date("D, d M Y", strtotime($sourcet->createdtime))}}  </td>
                                                            <td><span style="white-space: nowrap;">{{ $sourcet->sourcetype }}</span></td>
                                                            <td>

                                                                <a href="{{ SITEURL }}admin/editsourcetype/{{ $sourcet->id }}" class="btn btn-success icon-btn openMySidenav"><i class="fa fa-edit"></i></a>

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
                                @if (Session::has('sucmessage2'))
                                <div class="alert alert-success" role="alert">
                                        {{ Session::get('sucmessage2') }}
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
                                        <h1 class="m-0 font-weight-bold text-primary head-font-size">Add Source Value</h1>
                                        <a data-toggle="modal" href="#Department"  class="btn btn-primary btn-patient-box" role="button">
                                        <i class="fas fa-plus fa-sm font-icn-color"></i>
                                            Add Source Value
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
                                                        <th>Source Type</th>
                                                        <th>Source Value</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Source Type</th>
                                                        <th>Source Value</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                    <?php foreach($sourcevalue as $sourcev) { ?>
                                                        <tr>
                                                            <td>{{  date("D, d M Y", strtotime($sourcev->createdtime))}}  </td>
                                                            <td><span style="white-space: nowrap;">{{ $sourcev->sourcevalue }}</span></td>
                                                            <td>{{$sourcev->sourcetype}}</td>
                                                            <td>

                                                                <a href="{{ SITEURL }}admin/editsourcevalue/{{ $sourcev->id }}" class="btn btn-success icon-btn openMySidenav"><i class="fa fa-edit"></i></a>

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
                                    <h6 class="m-0 font-weight-bold text-primary">Add Source Type</h6>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                        <i class="fas fa-times fa-sm"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form class="pb-3 form-fields" action="{{ SITEURL }}admin/savesourcetype" method="post">
                                        <div class="row">
                                            <div class="form-group remove-botttom-space col-12">
                                                <div class="input-group">
                                                    <label for="inputFirstName">Source Type</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control " id="sourcetype" name="sourcetype"  placeholder="Source Name" required >
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

                    <!--------------Department----------------->
                    <div id="Department" class="modal fade " role="dialog">
                        <div class="modal-dialog">
                        <!-- Modal content-->
                            <div class="modal-content">
                                <div class="card-header d-flex flex-row align-items-center justify-content-between card-header-bckcolor">
                                    <h6 class="m-0 font-weight-bold text-primary">Add Source Value</h6>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                        <i class="fas fa-times fa-sm"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form class="pb-3 form-fields" action="{{ SITEURL }}admin/savesourcevalue" method="post">
                                        <div class="row">
                                            <div class="form-group remove-botttom-space col-12">
                                                <div class="input-group">
                                                    <label for="inputFirstName">Source Type</label>
                                                    <div class="input-group">
                                                        <select class="form-control" name="source_type_id" required>
                                                            <option value="">Select Source Type</option>
                                                            <?php foreach($sourcetype as $sourcet) { ?>
                                                                <option value="{{$sourcet->id}}">{{$sourcet->sourcetype}}</option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group remove-botttom-space col-12">
                                                <div class="input-group">
                                                    <label for="inputFirstName">Source Value</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control " id="sourcevalue" name="sourcevalue"  placeholder="Source Value Name" required>
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


