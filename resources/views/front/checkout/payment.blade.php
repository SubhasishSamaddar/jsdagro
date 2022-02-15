@extends('layouts.front')
<?php //echo Cart::getContent()->count();
//echo '<pre>'; print_r($data); echo '</pre>';?>
@section('seo-header')
		<title>Payment Page</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
@endsection	
@section('content')
<div class="row">
		<div id="sidebar" class="span3">
			<div class="well well-small">
				<?php echo Helper::printMenu('0');?>
			</div>

			<div class="well well-small alert alert-warning cntr">
				<h2>50% Discount</h2>
				<p> 
					only valid for online order. <br><br><a class="defaultBtn" href="#">Click here </a>
				</p>
			</div>
			<div class="well well-small" ><a href="#"><img src="{{ asset('front-assets/img/paypal.jpg') }}" alt="payment method paypal"></a></div>
				
			<a class="shopBtn btn-block" href="#">Upcoming products <br><small>Click to view</small></a>
			<br>
			<br>
			<ul class="nav nav-list promowrapper">
				@if( !$siteBarProducts->isEmpty() )
					@foreach( $siteBarProducts as $siteBarProduct )
						<li>
							<div class="thumbnail">
							<a class="zoomTool" href="{{ route('products',$siteBarProduct->id) }}" title="add to cart"><span class="icon-search"></span> QUICK VIEW</a>
								<img src="{{ asset('/storage/'.$siteBarProduct->product_image) }}" alt="bootstrap ecommerce templates" height="200">
								<div class="caption">
									<h4><a class="defaultBtn" href="{{ route('products',$siteBarProduct->id) }}">VIEW</a> <span class="pull-right">र {{ $siteBarProduct->price }}</span></h4>
								</div>
							</div>
						</li>
						<li style="border:0"> &nbsp;</li>	
					@endforeach
				@endif
			</ul>
		</div>
	

		<div class="span9">
			<ul class="breadcrumb">
				<li><a href="{{ route('sitelink') }}">Home</a> <span class="divider">/</span></li>
				<li class="active">Payment Details</li>
			</ul>
			<!--h3> Shipping Details</h3-->	
			<hr class="soft"/>
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
			<div class="well">
				<form method="post" action="{{ route('place-order') }}" id="checkoutform">
				 @csrf 
					<input type="hidden" name="total_amount" value="{{ $data['total_amount'] }}">
					<input type="hidden" name="shiping_street" value="{{ $data['shiping_street'] }}">		
					<input type="hidden" name="shiping_street_1" value="{{ $data['shiping_street_1'] }}">		
					<input type="hidden" name="location" value="{{ $data['location'] }}">		
					<input type="hidden" name="city" value="{{ $data['city'] }}">		
					<input type="hidden" name="shiping_pincode" value="{{ $data['shiping_pincode'] }}">		
					<input type="hidden" name="state" value="{{ $data['state'] }}">		
					<div class="control-group">
						<label class="control-label">Payment Mode <sup>*</sup></label>
						<div class="controls">
							<select class="span1" name="payment_mode" id="payment_mode" >
								<option value="COD">COD</option>
								<option value="Online" disabled>Online Payment</option>
							</select>
						</div>
					</div>
			
					<input type="hidden" name="new_token" value="{{ time() }}">
					<div class="control-group">
						<div class="controls">
							<input type="submit" name="submitAccount" value="Place Order" class="exclusive shopBtn">
						</div>
					</div>		
				</form>
			</div>
		</div>
	</div>
@endsection

@section('css')
@endsection
@section('js')

@endsection



