@extends('layouts.front')

@section('seo-header')
	<title>JSD Agro | Reset Password</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
@endsection	

@section('content')

<style>
.rounded-button {border-radius: 50%;border: none;color: white;background-color: #2fad49;margin-bottom:10px;margin-top:10px;padding: 20px;text-align: center;font-size: 16px;}
</style>

<div class="container">
<div class="formsCont">
	<div class="form-wrapper">
	    <h3>{{ __('Reset Password') }}</h3>
	    <p><span>Enter your new password to reset your password.</span></p>
	    <div class="form-container">

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="formRw">
                            	<div class="formCol">
                                <label class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                    </div>
            <div class="formRw">
                            	<div class="formCol">
                                <label class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div></div>
            <div class="formRw">
                            	<div class="formCol">
                                <label class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">


            </div>
        </div>
    
   <div class="formRw">
								<div class="formCol cusFlex">
    <button type="submit"  class="form-button">{{ __('Reset Password') }}</button>
    </div> </div>
    <!-- /.col -->

</form>
</div>
        </div>
        </div>
        <div class="formrightwrapper" style="min-height: 410px;">
    <h3>Avail Best Prices and Offers</h3>
    <p>Cheaper prices than your local supermarket.</p>
    
    <p><strong>Don't have any account?</strong></p>
    <a href="{{ route('register') }}" class="registerbutton">Register With Us</a>
    
  </div>
        </div></div>
@endsection
