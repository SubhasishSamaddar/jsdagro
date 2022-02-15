@extends('layouts.front')
<?php //echo '<pre>'; print_r($newProducts); echo '</pre>';?>
@section('seo-header')
		<title>Home Page</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
@endsection	
@section('content')

<div class="row">


    <!--ul class="nav navbar-nav">
        <li class="active"><a href="#">Home</a></li>
        <li><a href="#about">About</a></li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"> <span class="nav-label">Services</span> <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="#">Service A</a></li>
                <li><a href="#">Service B</a></li>
                <li class="dropdown-submenu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <span class="nav-label">Service C</span><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Service C1</a></li>
                        <li><a href="#">Service C2</a></li>
                        <li><a href="#">Service C3</a></li>
                        <li><a href="#">Service C4</a></li>
                        <li><a href="#">Service C5</a></li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul-->
	
	<div id="sidebar" class="span3">
		<div class="well well-small">
		<?php echo Helper::printMenu('0');?>
		
		
			
		</div>

		<!--div class="well well-small alert alert-warning cntr">
			<h2>50% Discount</h2>
			<p> 
				only valid for online order. <br><br><a class="defaultBtn" href="#">Click here </a>
			</p>
		</div>
		<div class="well well-small" ><a href="#"><img src="{{ asset('front-assets/img/paypal.jpg') }}" alt="payment method paypal"></a></div>
			
		<a class="shopBtn btn-block" href="#">Upcoming products <br><small>Click to view</small></a-->
		<br>
		<br>
		<ul class="nav nav-list promowrapper">
			@if( !$siteBarProducts->isEmpty() )
				@foreach( $siteBarProducts as $siteBarProduct )
					@if( $siteBarProduct->discount && $siteBarProduct->discount >0 )
						@php 
							$newPrice=((100-$siteBarProduct->discount)*$siteBarProduct->price/100); 
							$priceHtml='<small><del> र'.$siteBarProduct->price.'</del></small> र'.number_format($newPrice,2);

						else:
							$priceHtml='र'.$siteBarProduct->price;
						@endphp
					@endif
					<li>
						<div class="thumbnail">
						<a class="zoomTool" href="{{ route('products',$siteBarProduct->id) }}" title="add to cart"><span class="icon-search"></span> QUICK VIEW</a>
							<img src="{{ asset('/storage/'.$siteBarProduct->product_image) }}" alt="bootstrap ecommerce templates" height="200" width="200">
							<div class="caption">
								<h4><a class="defaultBtn" href="{{ route('products',$siteBarProduct->id) }}">VIEW</a> <span class="pull-right">@php echo $priceHtml;@endphp</span></h4>
							</div>
						</div>
					</li>
					<li style="border:0"> &nbsp;</li>	
				@endforeach
			@endif
		</ul>
	</div>
	<div class="span9">
		<div class="well np">
			<div id="myCarousel" class="carousel slide homCar">
				<div class="carousel-inner">
					<div class="item">
						<img style="width:100%" src="{{ asset('front-assets/img/bootstrap_free-ecommerce.png') }}" alt="bootstrap ecommerce templates">
						<div class="carousel-caption">
							<h4>Bootstrap shopping cart</h4>
							<p><span>Very clean simple to use</span></p>
						</div>
					</div>
					<div class="item">
						<img style="width:100%" src="{{ asset('front-assets/img/carousel1.png') }}" alt="bootstrap ecommerce templates">
						<div class="carousel-caption">
							<h4>Bootstrap Ecommerce template</h4>
							<p><span>Highly Google seo friendly</span></p>
						</div>
					</div>
					<div class="item active">
						<img style="width:100%" src="{{ asset('front-assets/img/carousel3.png') }}" alt="bootstrap ecommerce templates">
						<div class="carousel-caption">
							<h4>Twitter Bootstrap cart</h4>
							<p><span>Very easy to integrate and expand.</span></p>
						</div>
					</div>
					<div class="item">
						<img style="width:100%" src="{{ asset('front-assets/img/bootstrap-templates.png') }}" alt="bootstrap templates">
						<div class="carousel-caption">
							<h4>Bootstrap templates integration</h4>
							<p><span>Compitable to many more opensource cart</span></p>
						</div>
					</div>
				</div>
				<a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>
				<a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>
			</div>
		</div>
<!--	New Products	-->
	<div class="well well-small">
		<h3>New Products {{ Cookie::get('gg') }}</h3>
		<hr class="soften"/>
		<div class="row-fluid">
			<div id="newProductCar" class="carousel slide">
				<div class="carousel-inner">
				@if( !$newProducts->isEmpty() )
				@php $i=1; @endphp
					<div class="item active">
						<ul class="thumbnails">
							@foreach( $newProducts as $newData )
								
								<li class="span3">
									<div class="thumbnail">
										<a class="zoomTool" href="{{ route('products',$newData->id) }}" title="add to cart"><span class="icon-search"></span> QUICK VIEW</a>
										<a href="#" class="tag"></a>
										<a href="{{ route('products',$newData->id) }}">
											<img src="{{ asset('/storage/'.$newData->product_image) }}" alt="bootstrap-ring" width="200" height="200">
										</a>
									</div>
								</li>
								@if( $i%4==0 )
									@php echo '</ul></div><div class="item"><ul class="thumbnails">' @endphp
								@endif
								@php $i++ @endphp
							@endforeach
						</ul>
					</div>
				@endif
				</div>
				<a class="left carousel-control" href="#newProductCar" data-slide="prev">&lsaquo;</a>
				<a class="right carousel-control" href="#newProductCar" data-slide="next">&rsaquo;</a>
			</div>
		</div>
	</div>
	<!-- Featured Products	-->
	<div class="well well-small">
	<!--h3><a class="btn btn-mini pull-right" href="products.html" title="View more">VIew More<span class="icon-plus"></span></a> Featured Products  </h3-->
	<hr class="soften"/>
		
		@if( !$newProducts->isEmpty() )
			@php $i=1; @endphp
			<div class="row-fluid">
				<ul class="thumbnails">
					@foreach( $newProducts as $newData )
						@php 
							if( $newData->discount && $newData->discount >0 ):
								$newPrice=((100-$newData->discount)*$newData->price/100); 
								$priceHtml='<small><del> र'.$newData->price.'</del></small> र'.number_format($newPrice,2);
							else:
								$priceHtml='र'.$newData->price;
							endif;
						@endphp
						@if( $i%4==0 )
							@php echo '</ul></div><div class="row-fluid"><ul class="thumbnails">' @endphp
						@endif
						
						<li class="span4">
							<div class="thumbnail">
								<a class="zoomTool" href="{{ route('products',$newData->id) }}" title="add to cart"><span class="icon-search"></span> QUICK VIEW</a>
								<a  href="{{ route('products',$newData->id) }}">
									<img src="{{ asset('/storage/'.$newData->product_image) }}" alt=""  width="200" height="200">
								</a>
								<div class="caption">
									<h5>{{ $newData->prod_name }}</h5>
									<h3><center>{!! $priceHtml !!}</center></h3>
									<!--h4>
										<a class="defaultBtn" href="{{ route('products',$newData->id) }}" title="Click to view"><span class="icon-zoom-in"></span></a>
										<a class="shopBtn" href="#" title="add to cart"><span class="icon-plus"></span></a>
										<span class="pull-right">${{ $newData->price }}</span>
									</h4-->
								</div>
							</div>
						</li>
			
						@php $i++ @endphp
					@endforeach
				</ul>
			</div>
		@endif
	</div>
	
	
	<hr>
	</div>
</div>

@endsection
<script>
function removeFromCart(ID){
	$.ajax({
		type: "GET",
		url: "{{ url('product-remove-from-cart') }}",
		data: {item_session : ID},
		success:
		function(data){
			var jsonData = $.parseJSON(data);
			alert(jsonData.msg);
			$("#shprocnt").html(jsonData.status+' Item(s)');
			location.reload();
			/*
			$("#"+ID).hide();
			if( jsonData.status=='0' ){
				$("#totalprice").html('<td colspan="5">Cart Is Empty!</td>');
				$("#checkoutform").html('');
			}
			*/
		}
	});

}

function updateCart(ID){
	var Quantity = $("#qun"+ID).val();

	var Price = $("#price"+ID).val();
	if(Quantity>0){
		$.ajax({
			type: "GET",
			url: "{{ url('product-update-cart') }}",
			data: {item_session : ID, product_quantity : Quantity, product_per_price : Price},
			success:
			function(data){
				var jsonData = $.parseJSON(data);
				alert(jsonData.msg);
				location.reload();
			}
		});
	}else{
		$("#qun"+ID).val($("#old"+ID).val());
		alert('Product Quantity Must Be Greater Than 0');
		location.reload();
	}
}
</script>
<style>

</style>
@section('css')
@endsection
@section('js')

@endsection



