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
                                        <h1 class="m-0 font-weight-bold text-primary head-font-size">Edit Source Value</h1>

										<!-- <a href="javascript:void(0);" id="openMySidenav1" class="btn btn-primary btn-patient-box openMySidenav1" role="button"> -->
                                        <!-- <i class="fas fa-plus fa-sm font-icn-color"></i> -->
                                        <!-- Add Patient1 -->
                                        <!-- </a> -->
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive data-list">
                                            <form action="{{ SITEURL }}admin/updatesourcevalue" method="post" class="pb-3 form-fields">
                                        <div class="row">
                                            <div class="form-group remove-botttom-space col-12">
                                                <div class="input-group">
                                                    <select class="form-control" name="source_type_id" required>
                                                        <option value="">Select Source Type</option>
                                                        <?php foreach($sourcetype as $sourcet) { ?>
                                                            <option value="{{$sourcet->id}}" @if($sourcet->id == $sourcevalue->source_type_id) selected @endif>{{$sourcet->sourcetype}}</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group remove-botttom-space col-12">
                                                <div class="input-group">
                                                    <label for="inputFirstName">Source Value</label>
                                                    <div class="input-group">
                                                       <input type="text" class="form-control " id="sourcevalue" name="sourcevalue" value="{{ $sourcevalue->sourcevalue }}" placeholder="Source Value">
                                                    </div>
                                                </div>
                                            </div>




                                        </div>
                                        <div class="text-right">
											<input type="hidden" name="editid" value="{{ $sourcevalue->id  }}">

                                            <button type="reset" class="btn btn-secondary mr-3 btn-patient-box "  data-dismiss="modal">Cancel</button>
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
