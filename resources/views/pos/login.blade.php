@extends('layouts.pos')

@section('seo-header')
	<title>JSD Agro | Pos-Login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
@endsection	

@section('content')
<style>
.rounded-button {border-radius: 50%;border: none;color: white;background-color: #2fad49;margin-bottom:10px;margin-top:10px;padding: 20px;text-align: center;font-size: 16px;}
</style>

<div class="formsCont">
	<div class="form-wrapper">
    	<!--<div class="form-title">{{ __('Login') }}</div>-->
      <h3>Welcome </h3>
      <p><span>Sign in to continue</span></p>

	  @if (Session::has('message'))
   <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif

        <div class="form-container">
        	<form method="POST" action="{{ route('posloginsubmit') }}">
                        @csrf
						
                        
                        	<div class="formRw">
                            	<div class="formCol">
                                	<label for="email">{{ __('E-Mail Address') }}</label>
                                    <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
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
                            
                            <!-- <div class="formRw">
                            	<div class="formCol">
                                	<label class="checkLbl">
                                    	<input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <span class="checkSqr"></span>
                                        <span class="lblText">{{ __('Remember Me') }}</span>
                                    </label>
                                </div>
                            </div> -->
                        
							
							<div class="formRw">
								<div class="formCol cusFlex">
									<button type="submit" class="form-button">
										{{ __('Login') }}
									</button>

									
								</div>
							</div>

						
					</form>

					
        </div>
    </div>
  
  
</div>    <!-- .formsCont -->


@endsection
@section('js')

@endsection