@extends('layouts.front')

@section('seo-header')
	<title>JSD Agro | Register</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
@endsection

@section('content')

<div class="container">

@if ($message = Session::get('success'))
	<div class="alert alert-success">
		<ul class="margin-bottom-none padding-left-lg">
			<li>{{ $message }}</li>
		</ul>
	</div>
@endif

<div class="breadcrumb"><a href="{{ url('/') }}/home">Home</a>  >  <a href="{{ url('/') }}/register">Register</a></div>

<div class="formsCont" style="padding: 0px;">
	
	<div class="form-wrapper" style="margin: 0px 0px 25px;">
    	<!--<div class="form-title">{{ __('Register') }}</div>-->
      <h3>Let's Get Started</h3>
      <p><span>Create account to avail our offers.</span></p>
        <div class="form-container">
        	<form method="POST" action="{{ route('registerUser') }}">
                    @csrf
						
                        <div class="formRw">
                        	<div class="formCol">
                            	<label for="name">{{ __('Name') }}</label>
                                <input id="name" type="text" class="@error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        	</div>
                        </div>
                        
                        <div class="formRw">
                        	<div class="formCol">
                            	<label for="email">{{ __('E-Mail Address') }}</label>
                                <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="formRw">
                        	<div class="formCol">
                            	<label for="phone">Phone No</label>
                                <div class="phone-wrap">
                                	<input id="phone" type="text" class="" name="phone" value="{{ old('phone') }}" required>
                                </div>
                            </div>
                        </div>                        
                        
                        <div class="formRw">                        	
                            <div class="formCol">
                            	<label for="address_1">Address</label>
                            	<input id="address_1" type="text" class="" name="address_1" value="{{ old('address_1') }}" required>
                                @error('address_1')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
                                <!--<span class="subLabel">Address Line 1</span>-->
                            </div>
                        </div>
                        
                        <div class="formRw">
                        	<div class="formCol">
                            	<input id="address_2" type="text" class="" name="address_2" value="{{ old('address_2') }}">
                                <!--<span class="subLabel">Address Line 2</span>-->
                            </div>
                        </div>
						
                        <div class="formRw">
                        	<div class="formCol">
                                <label>State</label>
                            	<?php echo Helper::getState();?>
                                <!--<span class="subLabel">State</span>-->
                            </div>
                            <div class="formCol">
                              <label for="city">City</label>
                            	<input id="city" type="text" class="" name="city" value="{{ old('city') }}" required>
                                <!--<span class="subLabel">City</span>-->
                            </div>
                            <div class="formCol">
                              <label for="pincode">Pin Code</label>
                            	<input id="pincode" type="text" class="" name="pincode" value="{{ old('pincode') }}" required>
                                <!--<span class="subLabel">Pin Code</span>-->
                            </div>
                        </div>
						
						<div class="formRw">
                        	<div class="formCol">
                            	<label for="password">{{ __('Password') }}</label>
                                <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
								@error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="formCol">
                            	<label for="password-confirm">{{ __('Confirm Password') }}</label>
                                <input id="password-confirm" type="password" class="span3 form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
						
						<div class="formRw">
                            <div class="formCol">
                                <button type="submit" class="form-button">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
        </div>
    </div>
  
  <div class="formrightwrapper reg_box">
    <h3>Avail Best Prices and Offers</h3>
    <p>Cheaper prices than your local supermarket.</p>
    
    <p><strong>Already have an account?</strong></p>
    <a href="{{ route('login') }}" class="registerbutton">Login With Us</a>
    
  </div>
</div><!-- .formsCont -->

</div>
@endsection
