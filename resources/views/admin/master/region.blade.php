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
                                        <h1 class="m-0 font-weight-bold text-primary head-font-size">Add Region</h1>
                                        <a data-toggle="modal" href="#Region" class="btn btn-primary btn-patient-box" role="button">
                                        <i class="fas fa-plus fa-sm font-icn-color"></i>
                                            Add Region
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
														<th>Action</th>
                                                        <th>Region Name</th>
                                                     
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th>Date</th>
														<th>Action</th>
                                                        <th>Region Name</th>
                                                        
                                                    </tr>
                                                </tfoot>
                                                <tbody>
												<?php foreach($regiondata as $regiond) { ?>
                                                    <tr>
                                                        <td> {{  date("D, d M Y", strtotime($regiond->createdtime))}} </td>
														 <td>
                                                            <a href="javascript:void(0)" class="btn btn-primary icon-btn openMySidenav"><i class="fa fa-eye"></i></a>
                                                            <a href="{{ SITEURL }}admin/editregion/{{ $regiond->id  }}" class="btn btn-success icon-btn openMySidenav"><i class="fa fa-edit"></i></a>
                                                           
                                                        </td>
                                                        <td><span style="white-space: nowrap;">{{ $regiond->regionname }} </span></td>
                                                       
                                                    </tr>
												<?php } ?>
                                                   
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mb-4">
							
							@if (Session::has('sucmessage'))
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
                                        <h1 class="m-0 font-weight-bold text-primary head-font-size">Add Circle</h1>
                                        <a data-toggle="modal" href="#Circle"  class="btn btn-primary btn-patient-box" role="button">
                                        <i class="fas fa-plus fa-sm font-icn-color"></i>
                                            Add Circle
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
														<th>Action</th>
                                                        <th>Region</th>
                                                        <th>Circle</th>
                                                        
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th>#</th>
														<th>Action</th>
                                                        <th>Region</th>
                                                        <th>Circle</th>
                                                        
                                                    </tr>
                                                </tfoot>
                                                <tbody>
												<?php foreach($crciledata as $regiond) { 
												$regiondatasecton = DB::table('regiondata')->where('id', $regiond->regionparent)->first();
												$regionname = 'N/A';
												if($regiondatasecton) {
													$regionname = $regiondatasecton->regionname;
												}
												?>
                                                    <tr>
                                                        <td style="white-space: nowrap;"> {{  date("D, d M Y", strtotime($regiond->createdtime))}} </td>
														 <td>
                                                            <a href="javascript:void(0)" class="btn btn-primary icon-btn openMySidenav"><i class="fa fa-eye"></i></a>
                                                            <a href="{{ SITEURL }}admin/editcircle/{{ $regiond->id  }}" class="btn btn-success icon-btn openMySidenav"><i class="fa fa-edit"></i></a>
                                                           
                                                        </td>
                                                        <td><span style="white-space: nowrap;">{{ $regionname }} </span></td>
														<td><span style="white-space: nowrap;">{{ $regiond->regionname }} </span></td>
                                                       
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

                   
                    
                     <!--------------Region----------------->
                     <div id="Region" class="modal fade " role="dialog">
                        <div class="modal-dialog">
                        <!-- Modal content-->
                            <div class="modal-content">
                                <div class="card-header d-flex flex-row align-items-center justify-content-between card-header-bckcolor">
                                    <h6 class="m-0 font-weight-bold text-primary">Add Region</h6>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                        <i class="fas fa-times fa-sm"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ SITEURL }}admin/saveregion" method="post"  class="pb-3 form-fields">                                
                                        <div class="row">
                                            <div class="form-group remove-botttom-space col-12">
                                                <div class="input-group">
                                                    <label for="inputFirstName">Region</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control " id="regionname" name="regionname"  placeholder="Region Name" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
											<input type="hidden" name="userid" value="{{ Auth::user()->id }}">
                                            <button type="reset" class="btn btn-secondary mr-3 btn-patient-box "  data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary btn-patient-box">Submit</button>
											 {!! csrf_field() !!}
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--------------Circle----------------->
                    <div id="Circle" class="modal fade " role="dialog">
                        <div class="modal-dialog">
                        <!-- Modal content-->
                            <div class="modal-content">
                                <div class="card-header d-flex flex-row align-items-center justify-content-between card-header-bckcolor">
                                    <h6 class="m-0 font-weight-bold text-primary">Add Circle</h6>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                        <i class="fas fa-times fa-sm"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form class="pb-3 form-fields" action="{{ SITEURL }}admin/savecircle" method="post">                                
                                        <div class="row">
                                            <div class="form-group remove-botttom-space col-12">
                                                <div class="input-group">
                                                    <label for="inputFirstName">Select Region</label>
                                                    <div class="input-group">
                                                        <select name="parentregion" id="parentregion"  class="custom-select form-control" required>
														<option selected disabled>Select Region</option>
														<?php 	
														
														$arraystate = array();
														$regiondatacirused = DB::table('regiondata')->where('regioblavel',2)->orderBy('id', 'DESC')->get();
														$regionnamearray = array(); $regionparentarray = array();
														foreach($regiondatacirused as $regiondatacir) {
															$regionnamearray[] = $regiondatacir->regionname;
															$regionparentarray[] = $regiondatacir->regionparent;
														}
														if($regionnamearray){ 
															$regionnameaata = implode(',',$regionnamearray);
															$arraystate =  explode(',',$regionnameaata);
														}
														$regionparentar = '';
														if($regionnamearray){ 
															$regionparentar = implode(',',$regionparentarray); 
														}
														
														
														$regiondata = DB::table('regiondata')->where('regioblavel',1)->orderBy('id', 'DESC')->get(); 
														foreach($regiondata  as $regionda) { ?>
                                                            <option value="{{ $regionda->id }}">{{ $regionda->regionname }}</option>
															<?php } ?>
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
										 <div class="row">
                                            <div class="form-group remove-botttom-space col-12">
                                                <div class="input-group">
                                                    <label for="inputFirstName">Select Circle</label>
                                                    <div class="input-group" style="max-height:250px; overflow-y:auto;"> 
														 
														<?php 	
														  
														$regiondata = DB::table('states')->where('country_id',101)->orderBy('name', 'ASC')->get(); 
														foreach($regiondata  as $regionda) { ?>
                                                             
															<label style="display:block; padding-bottom:10px;     width: 100%;"  >
																<input type="checkbox" <?php if(in_array($regionda->name,$arraystate)) { echo 'checked disabled';} ?> name="circlename[]" id="circlename{{ $regionda->id }}" value="{{ $regionda->name }}">{{ $regionda->name }}
															</label>
															<?php } ?> 
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


  <script src="https://code.jquery.com/jquery-3.5.0.js"></script>  
	<script> 
		// A $( document ).ready() block.
		$( document ).ready(function() {
		  $( "#parentregion" ).change(function() {
			  var hd = $(this).val();
			  var sting = '{{ $regionparentar }}';
			  if (sting.indexOf(hd) > -1) {
				   if (!confirm("Zone has been already used. Do you want to replace this?")){
					  return false;
					}
				} 
			});
			
			  $( "#stateelgion" ).change(function() {
			  var kdf = $( this ).val();
			 var statne =  $(this).find(':selected').attr('dataid');
			 
			  var urldata =  '{{ SITEURL }}ajaxfiles/getdata.php?action=loadcity&stateid='+kdf;
			 // alert(urldata);
			  $( "#cityrelgion" ).load( urldata );
			  $('#statename').val(statne);
			});
			
			$( "#cityrelgion" ).change(function() { 
			 var statne =  $(this).find(':selected').attr('dataid');
			 
			  $('#cityname').val(statne);
			});
			
			
			
			
		});
	</script>  

@endsection	