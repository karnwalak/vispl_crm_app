@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
		
		<img src="{{ SITEURL }}public/images/logo.png" style="max-width:300px; height:auto; margin:0 auto 20px; display:block;" alt="Mera App: Online Personal Document Management">
		
            <div class="card">
               

                <div class="card-body">
					
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                             <label for="password" class="col-md-4 col-form-label text-md-right"> Username / Mobile Number:</label>
                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"   required placeholder="Username / Mobile Number" autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Password : </label>

                            <div class="col-md-6">
                                <input id="password" type="password"  placeholder="Password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                      

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
							 
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                 
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
 <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
 

<script type="text/javascript">
$(document).ready(function (e) {
	$( ".tabinfclickdata" ).click(function() { 
		 var neswsarat = $(this).attr('href');
		 $(neswsarat).trigger('click');
		  return false;
	}); 
	 

		$( "#email_data" ).change(function() {
		   var str = $(this).val();
		  $('#errorhow').fadeOut(); 
			var intRegex = /^\d+$/;
			var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/; 
			
			if(intRegex.test(str) || floatRegex.test(str)) { 
			 if(!str.match('[0-9]{10}'))  {
					$('#errorhow').fadeIn(); 
					return;
				}  else { 
				
				var data = 'action=emailadd&mobile=' + str;
  
				 //alert(data);
				  //start the ajax
				  $.ajax({
				   //this is the php file that processes the data and send mail
				   url: "{{ SITEURL.'ajaxfiles/cat_load.php' }}",

				   //GET method is used
				   type: "GET",

				   //pass the data   
				   data: data ,  
				   
				   //Do not cache the page
				   cache: false,
				   
				   //success
				   success: function (html) {  
					//alert(html);  
					 $( "#email" ).val(html);
					//if process.php returned 1/true (send mail success)
				   }
					   
				  }); 
				}
			} else {
			$("#email").val(str);	
			}

		  
		});
		
		});
	</script>
@endsection
