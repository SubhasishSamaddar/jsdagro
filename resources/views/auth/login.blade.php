@extends('layouts.front')

@section('seo-header')
	<title>JSD Agro | Login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
@endsection	

@section('content')
<style>
.rounded-button {border-radius: 50%;border: none;color: white;background-color: #2fad49;margin-bottom:10px;margin-top:10px;padding: 20px;text-align: center;font-size: 16px;}
</style>
<div class="container">
<div class="breadcrumb"><a href="{{ url('/') }}/home">Home</a>  >  <a href="{{ url('/') }}/login">Login</a></div>
<div class="formsCont" style="padding: 0px;">
	
	<div class="form-wrapper" style="margin: 0px 0px 25px;" id="login_form_container">
    	<!--<div class="form-title">{{ __('Login') }}</div>-->
      <h3>Welcome back</h3>
      <p><span>Sign in to continue</span></p>
        <div class="form-container">
        	<form method="POST" action="{{ route('login') }}">
                        @csrf                        
                        	<div class="formRw">
                            	<div class="formCol">
                                	<label for="email">{{ __('E-Mail Address or Phone Number') }}</label>
                                    <input id="email" type="text" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
									@error('email')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
                                </div>                            
                            </div>
                            
                            <div class="formRw">
                            	<div class="formCol">
                                	<label for="inputPassword">{{ __('Password') }}</label>
                                    <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
									@error('password')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
                                </div>
                            </div>
                            
                            <div class="formRw">
                            	<div class="formCol">
                                	<label class="checkLbl">
                                    	<input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <span class="checkSqr"></span>
                                        <span class="lblText">{{ __('Remember Me') }}</span>
                                    </label>
                                </div>
                            </div>
                        
							
							<div class="formRw">
								<div class="formCol cusFlex">
									<button type="submit" class="form-button">
										{{ __('Login') }}
									</button>
									<p>OR</p>
									<button type="button" class="otp-button" onclick="show_otp_container()">
										{{ __('Request OTP') }}
									</button>

									@if (Route::has('password.request'))
										<a class="formLink" href="{{ route('password.request') }}">
											{{ __('Forgot Your Password?') }}
										</a>
									@endif
								</div>
							</div>

						
					</form>

					<!---- LOGIN WITH OTP 
					<button class="rounded-button"><strong>OR</strong></button>
					<div class="formCol">
					<h5>Login with OTP</h5>
						<div class="phone-wrap">
							<input type="text" class="" id="mobile_no" name="mobile_no" required="" placeholder="Enter Mobile No.">
						</div>
					</div>
					<br clear="all">
					<div class="formRw">
						<div class="formCol cusFlex">
							<button type="button" class="form-button" onclick="send_otp()">
								Send OTP
							</button>
						</div>
					</div> ---->
        </div>
    </div>


	<div class="form-wrapper" style="margin: 0px 0px 25px; display:none;" id="otp_form_container">
		<h3>Login with OTP</h3>
		<p><span>Login using your phone number with OTP</span></p>
        <div class="form-container"> 
        <div class="formRw">
			<div class="formCol">
			    <label for="mobile_no">{{ __('Phone Number') }}</label>
				<input class="input100" type="text" id="mobile_no" name="mobile_no" placeholder="Enter Mobile Number" maxlength="10">
				<span class="focus-input100"></span>
				<span class="symbol-input100">
					<i class="fa fa-lock" aria-hidden="true"></i>
				</span>
			</div>
			</div>
			<div class="formRw">
			<div class="formCol">
				<input class="input100" type="text" id="otp" name="otp" placeholder="OTP" maxlength="6" style="display:none;">
			</div>
			</div>
			<div class="formRw" id="otpsendingbutton">
			 	<div class="formCol cusFlex">
				<button class="form-button"  style="margin-right: 10px;" id="otp_submit" type="button" onclick="send_otp()">
					Submit
				</button>
				<button class="otp-button" type="button" onclick="cancel_otp()">
					Cancel
				</button>
			</div>
			</div>
			<div class="formRw" id="otploginbutton" style="display:none;">
			    <div class="formCol cusFlex">
				<button class="form-button" id="otp_submit" type="button" onclick="login_with_otp()">
					Login
				</button>
			</div>
			</div>
			<br clear="all">
			<img id="loading-image" style="display:none;" src="<?php echo url('/'); ?>/public/img/loading@2x.gif" alt="Loading..." />
			<div id="otp_msg"></div>
        </div>
    </div>
  
   <div class="formrightwrapper">
    <h3>Avail Best Prices and Offers</h3>
    <p>Cheaper prices than your local supermarket.</p>
    
    <p><strong>Don't have any account?</strong></p>
    <a href="{{ route('register') }}" class="registerbutton">Register With Us</a> 
  </div>
</div>    <!-- .formsCont -->

</div>


<!-- The Modal -->
<div class="modal fade" id="verifyModal" data-keyboard="false" data-backdrop="static" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
		
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Enter OTP
		  <button type="button" class="close" data-dismiss="modal">×</button>
		  </h4>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
			<p id="resend_msg" style="text-align:center;color:green;"></p>
			<p id="error_msg" style="text-align:center;color:green;"></p>
			
			<div class="wrap-input100 validate-input" data-validate = "Password is required">
				<input class="input100" type="text" id="mobile_no" name="mobile_no" placeholder="Enter Mobile Number" maxlength="10">
				<span class="focus-input100"></span>
				<span class="symbol-input100">
					<i class="fa fa-lock" aria-hidden="true"></i>
				</span>
			</div>
			
			<div class="wrap-input100 validate-input">
				<input class="input100" type="text" id="otp" name="otp" placeholder="OTP" maxlength="6" style="display:none;">
			</div>
			<br clear="all">
			<div class="formRw" id="otpsendingbutton">
				<button class="btn btn-warning btn-sm" id="otp_submit" type="button" onclick="send_otp()">
					Submit
				</button>
			</div>
			
			<div class="formRw" id="otploginbutton" style="display:none;">
				<button class="btn btn-info btn-sm" id="otp_submit" type="button" onclick="login_with_otp()">
					Login
				</button>
			</div>
			<br clear="all">
			<img id="loading-image" src="<?php echo url('/'); ?>/public/img/loading@2x.gif" alt="Loading..." />
			<div id="otp_msg"></div>
        </div>
        <!-- Modal footer -->
      </div>
    </div>
</div>




@endsection
@section('js')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script>
function send_otp(){
	var mobile_no = $("#mobile_no").val();
	$("#loading-image").show();
	$.ajax({      
		type: "POST",
		url: "{{ url('send-otp') }}",   
		data: {'mobile_no':mobile_no,'_token':'<?php echo csrf_token(); ?>'},       
		success:     
		function(data){
			if(data.success==0)
			{
				swal("Oops", data.msg , "error");
			}
			else 
			{
				$("#loading-image").hide();
				$("#otp_msg").html('<span style="color:green">'+data.msg+'</span>');
				$("#mobile_no").hide();
				$("#otp").show();
				$("#otploginbutton").show();
				$("#otpsendingbutton").hide();
			}
		}
	});
}


function login_with_otp()
{
	var mobile_no = $("#mobile_no").val();
	var otp = $("#otp").val();
	$.ajax({      
		type: "POST",
		url: "{{ url('send-otp-by-login') }}",   
		data: {'otp': otp,'mobile_no':mobile_no,'_token':'<?php echo csrf_token(); ?>'},       
		success:     
		function(data){
			if(data.success==1)
			{
				//swal("Success", "Login Successfull" , "success");
				if(data.user_type!='Customer'){
					window.location.href = "{{ route('home')}}";
				}
				else{
					window.location.href = "https://jsdagro.com/home";
				}
			}
		}
	});
}


function show_otp_container(){
	$("#login_form_container").hide();
	$("#otp_form_container").show();
}

function cancel_otp(){
	$("#login_form_container").show();
	$("#otp_form_container").hide();
}
</script>
@endsection