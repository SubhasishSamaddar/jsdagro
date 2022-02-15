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
	    <p><span>Enter your email address to get a password reset link.</span></p>
	    <div class="form-container">

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="formRw">
                            	<div class="formCol">
                                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="formRw">
								<div class="formCol cusFlex">
                                <button type="submit"  class="form-button">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
             </div>
            <div class="formrightwrapper">
    <h3>Avail Best Prices and Offers</h3>
    <p>Cheaper prices than your local supermarket.</p>
    
    <p><strong>Don't have any account?</strong></p>
    <a href="{{ route('register') }}" class="registerbutton">Register With Us</a>
    
  </div>
            
       
</div></div>
@endsection
