@extends('layouts.front')
@section('seo-header')
		<title>{{ $pageTitle }}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
@endsection
@section('content')

	<div class="formsCont">
		<div class="form-wrapper" style="margin:0px 0px;">
			<h3>Contact us</h3>
				<div class="form-container" style="margin-top:20px;">  
					<form method="POST" id="user_contact_form">
						<input type="hidden" name="_token" value="{{ csrf_token()}}">
						<div class="formRw">
							<div class="formCol">
								<label for="name">Name</label>
								<input id="name" type="text" class="" name="user_name" value="" required autocomplete="name" autofocus="">
							</div>
						</div>

						<div class="formRw">
							<div class="formCol">
								<label for="email">E-Mail Address</label>
								<input id="email" type="email" class="" name="user_email" value="" required autocomplete="email">
							</div>
                        	<div class="formCol">
                            	<label for="phone">Phone No</label>
                                <div class="phone-wrap">
                                	<input id="phone" type="text" class="" name="user_phone_no" required>
                                </div>
                            </div>
                        </div>                        
                        
						<div class="formRw">
                        	<div class="formCol">
                            	<label for="phone">Subject</label>
                                <div class="">
                                	<input type="text" name="user_subject" id="user_subject" required>
                                </div>
                            </div>
                        </div>

                        <div class="formRw">
                        	<div class="formCol">
                            	<label for="phone">Comment</label>
                                <div class="">
                                	<textarea id="message" type="text" class="" name="user_comment" required></textarea>
                                </div>
                            </div>
                        </div>
						
						
						
						<div class="formRw">
                            <div class="formCol">
                                <button type="button" class="form-button" onclick="save_contact_us_form()">
                                    Submit
                                </button>
                            </div>
                        </div>


						<div class="formRw">
                            <div class="formCol" id="user_contact_msg">
                                <img id="load_img" src="<?php echo url('/'); ?>/public/img/loading.gif" height="30px" width="30px;" style="display:none;">
                            </div>
                        </div>



                    </form>
				</div>
      </div>
      
      <div class="formrightwrapper" style="min-height:598px;">
        <h3>Any Problems?</h3>
        <p><strong>Call us at +919874313174</strong></p>
    <h3>Avail Best Prices and Offers</h3>
    <p>Cheaper prices than your local supermarket.</p>
    
    <p><strong>Don't have any account?</strong></p>
    <a href="{{ route('register') }}" class="registerbutton">Register With Us</a>
    
  </div>
      
      
		</div>
	


		
<br>	
<br>	
@endsection
@section('css')

@endsection
@section('js')
<script>
function save_contact_us_form(){
	$("#load_img").show();
	$.ajax({      
		type: "POST",
		url: "{{ url('save-contactus-data') }}",   
		data: $("#user_contact_form").serialize(),       
		success:     
		function(data){
			$("#load_img").hide();
			$("#user_contact_form")[0].reset();
			$("#user_contact_msg").html(data.msg);
		}
	});
}
</script>
@endsection



