@extends('layouts.front')
<?php //echo '<pre>'; print_r($siteBarProducts);?>
@section('seo-header')
		<title>Product Listing Page</title>
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
						<img src="{{ asset('/storage/'.$siteBarProduct->product_image) }}" alt="bootstrap ecommerce templates">
						<div class="caption">
							<h4><a class="defaultBtn" href="{{ route('products',$siteBarProduct->id) }}">VIEW</a> <span class="pull-right">${{ $siteBarProduct->price }}</span></h4>
						</div>
					</div>
				</li>
				<li style="border:0"> &nbsp;</li>	
			@endforeach
		@endif;
		</ul>
	</div>
	
	<div class="span9">
		<div class="well well-small">
			<h3>Our Products </h3>
				<div class="row-fluid">
					<ul class="thumbnails">
					@if( !$products->isEmpty() )
						@php $i=1 @endphp
						@foreach($products as $data)
							@if( $i%3==0 )
								@php '</ul></div><div class="row-fluid"><ul class="thumbnails">' @endphp
							@endif
							<li class="span4">
								<div class="thumbnail">
								<a href="{{ route('products',$data->id) }}" class="overlay"></a>
								<a class="zoomTool" href="{{ route('products',$data->id) }}" title="add to cart"><span class="icon-search"></span> QUICK VIEW</a>
								<a href="{{ route('products',$data->id) }}"><img src="{{ asset('/storage/'.$data->product_image)}}" alt=""></a>
									<div class="caption cntr">
									<p>{{ $data->prod_name}}</p>
									<p><strong> ${{ $data->price }}</strong></p>
									<h4><a class="shopBtn" href="{{ route('products',$data->id) }}" title="add to cart"> Product Details </a></h4>
										<!--div class="actionList">
											<a class="pull-left" href="#">Add to Wish List </a> 
											<a class="pull-left" href="#"> Add to Compare </a>
										</div--> 
									<br class="clr">
									</div>
								</div>
							</li>
							@php $i++ @endphp
						@endforeach
					@else
						{{ 'No Product Found!' }}
					@endif
				</ul>
				@php /* More */ @endphp
				<span id="">Load Here </span>
			</div>
		</div>
	</div>
	
</div>
@endsection
@section('css')
@endsection
@section('js')
@endsection



