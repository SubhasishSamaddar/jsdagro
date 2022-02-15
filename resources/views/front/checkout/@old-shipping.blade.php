@extends('layouts.front')
<?php //echo Cart::getContent()->count();
//echo '<pre>'; print_r($data); echo '</pre>';?>
@section('seo-header')
		<title>Shipping Page</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
@endsection	

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 my-3">
            <div class="pull-right">
                <div class="btn-group">
                   Shiping
                </div>
            </div>
        </div>
    </div> 
    @if ($errors->any())
	<div class="alert alert-danger">
		<strong>Whoops!</strong> There were some problems with your input.<br>
		<ul>
		@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
		</ul>
	</div>
	@endif

	
			<?php /* if( !$productDetails->isEmpty() && $totalPrice>0 && isset(Auth::user()->id) )*/?>
				<form method="post" action="{{ route('payment') }}" id="checkoutform">
				 @csrf  
					<input type="hidden" name="total_amount" value="{{ $data['total_amount'] }}">
					<!--input type="hidden" name="order_date" value="{{ $data['total_amount'] }}"-->
					<div class="row">
						<div class="col-md-8">
							<label for="description">Address</label>
							<input type="text" name="shiping_street" class="form-control" >
						</div>
						
						<!--div class="col-md-4">
							<label for="description">Country</label>
							<select name="shiping_country" id="swadesh_hut_id" class="form-control" required>
								<option value="">-- Select Country --</option>
								@if( !$countryDetails->isEmpty() )
									@foreach( $countryDetails as $sdata )
										<option value="{{ $sdata->id }}">{{ $sdata->country_name }}</option>
									@endforeach
								@endif
							</select>
						</div>
						<div class="col-md-4">
							<label for="description">State</label>
							<span id="shipping_state"></span>
						</div-->
						<div class="col-md-4">
							<label for="description">Pin Code</label>
							
							<input type="text" name="shiping_pincode" class="form-control" value="{{ Session::get('swadesh_pin_code') }}" readonly>
						</div>
					</div>
					 <input type="hidden" name="new_token" value="{{ time() }}">
					<div class="row">
					
						<div class="col-md-6"><br>
							<button id="processtocheckout" class="btn btn-success">Process to Payment</a>
						</div>
					</div>
				</form>
			
							
</div>
@endsection

@section('css')
@endsection
@section('js')

@endsection



