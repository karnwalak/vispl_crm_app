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
                                        <h1 class="m-0 font-weight-bold text-primary head-font-size">Edit Circle</h1>
                                        
										<!-- <a href="javascript:void(0);" id="openMySidenav1" class="btn btn-primary btn-patient-box openMySidenav1" role="button"> -->
                                        <!-- <i class="fas fa-plus fa-sm font-icn-color"></i> -->
                                        <!-- Add Patient1 -->
                                        <!-- </a> -->
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive data-list">
                                            <form action="{{ SITEURL }}admin/updatecircle" method="post" class="pb-3 form-fields">                                
                                        <div class="row">
                                            <div class="form-group remove-botttom-space col-12">
                                                <div class="input-group">
                                                    <label for="inputFirstName">Region</label>
                                                    <div class="input-group">
                                                       <select name="parentregion" id="parentregion"  class="custom-select form-control">
														<option selected disabled>Select Region</option>
														<?php 	$regioata = DB::table('regiondata')->where('regioblavel',1)->orderBy('id', 'DESC')->get(); 
														foreach($regioata  as $regionda) { ?>
                                                            <option <?php if($regiondata->regionparent ==$regionda->id  ) { echo 'selected'; } ?> value="{{ $regionda->id }}">{{ $regionda->regionname }}</option>
															<?php } ?>
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
											  <div class="form-group remove-botttom-space col-12">
                                             <div class="input-group">
                                                    <label for="inputFirstName">Select Circle</label>
                                                    <div class="input-group" style="max-height:250px; overflow-y:auto;"> 
														 
														<?php 	
														$regionnamearray = explode(',',$regiondata->regionname);
														
														$staetdtaa = DB::table('states')->where('country_id',101)->orderBy('name', 'ASC')->get(); 
														foreach($staetdtaa  as $regionda) { ?>
                                                             
															<label style="display:block; padding-bottom:10px;     width: 100%;"  >
																<input type="checkbox" <?php if(in_array($regionda->name,$regionnamearray)) { echo 'checked';} ?> name="circlename[]" id="circlename{{ $regionda->id }}" value="{{ $regionda->name }}">{{ $regionda->name }}
															</label>
															<?php }  ?> 
                                                    </div>
                                                </div>
                                            </div> 
											
											
                                        </div>
                                        <div class="text-right">
											<input type="hidden" name="editid" value="{{ $regiondata->id  }}">
									 
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
  

  <script src="https://code.jquery.com/jquery-3.5.0.js"></script>  
	<script> 
		// A $( document ).ready() block.
		$( document ).ready(function() {
		  $( "#channelparner" ).change(function() {
			  
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