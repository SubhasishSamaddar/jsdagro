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
							<h4><a class="defaultBtn" href="{{ route('products',$siteBarProduct->id) }}">VIEW</a> <span class="pull-right">@php echo $priceHtml; @endphp</span></h4>
						</div>
					</div>
				</li>
				<li style="border:0"> &nbsp;</li>	
			@endforeach
		@endif
		</ul>
	</div>
	
	<div class="span9">
		<div class="well well-small">
			<h3>Our Products </h3>
				<div class="row-fluid">
					<span id="">
					<?php //echo url()->current();?>
					

						@if( !$products->isEmpty() )
							<ul class="thumbnails">
								@php $i=1 @endphp
								<?php //echo '<pre>'; print_r($products);?>
								@foreach($products as $data)
									@if( $data->discount && $data->discount >0 )
										@php 
											$newPrice=((100-$data->discount)*$data->price/100); 
											$priceHtml='<small><del> र'.$data->price.'</del></small> र'.number_format($newPrice,2);

										else:
											$priceHtml='र'.$data->price;
										@endphp
									@endif
									@if( $i%3==0 )
										@php '</ul></div><div class="row-fluid"><ul class="thumbnails">' @endphp
									@endif
									<li class="span4">
										<div class="thumbnail">
										<a href="{{ route('products',$data->id) }}" class="overlay"></a>
										<a class="zoomTool" href="{{ route('products',$data->id) }}" title="add to cart"><span class="icon-search"></span> QUICK VIEW</a>
										<a href="{{ route('products',$data->id) }}"><img src="{{ asset('/storage/'.$data->product_image)}}" alt="" height="200" width="200"></a>
											<div class="caption cntr">
											<p>{{ $data->prod_name}}</p>
											<p><strong>@php echo $priceHtml;@endphp</strong></p>
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
							</ul>
							<span id="spanscrollcount1"><button id="scrollcount" onclick="productpagination('1')">Load More</button></span>
<!--ul class="pagination-custom text-center">
<li class="pag-link" ><a href="#"><</a></li>
<li class="pag-link"><a href="#">1</a></li>
<li class="pag-link"><a href="#">2</a></li>
<li class="pag-link current"><span>3</span></li>
<li class="pag-link"><a href="#">4</a></li>
<li class="pag-link"><a href="#">5</a></li>
<li class="pag-link"><a href="#">></a></li>
</ul-->
					
						
						@else
							{{ 'No Product Found!' }}
						@endif
					
					@php /* More */ @endphp
					
					
				</span>	
</div>
							
				
			</div>
		</div>
	</div>
	
</div>

<div class="container">
	<div class="row">
		
	</div>
</div>

<style>
.pag-link {
    display: inline-block;
    vertical-align: middle;
    padding: 5px;
}
.pag-link.disabled > span,
.pag-link.current > span,
.pag-link > a{
    display: block;
    border-radius: 5%;  
    font-size: 10px;
    line-height: 1.42857;
    margin-right: 5px;
    padding: 5px 10px;
    position: relative;
    text-decoration: none;
    border: none;
    -webkit-transition: all 0.3s ease-in-out;
    -moz-transition: all 0.3s ease-in-out;
    -o-transition: all 0.3s ease-in-out;
    -ms-transition: all 0.3s ease-in-out;
    transition: all 0.3s ease-in-out;
}
.pag-link:active > a,
.pag-link:hover > a,
.pag-link:focus > a,
.pag-link.current > span{
    font-size: 10px;
    font-weight: bold;
    padding: 5px 10px;
}
.pag-link > a{
    background-color: #e6e6e4;
    color: #fff;
    cursor: pointer;
}
.pag-link.disabled > span,
.pag-link.current > span{
    background-color: #f89406;
    color: #fff;
    cursor: inherit;
}
.pag-link:active > a,
.pag-link:hover > a,
.pag-link:focus > a {
    background-color: #ec217c !important;
    border-color: #ec217c;
    color: #fff;
}</style>
<script>
function productpagination(ID){
	var CategoryId=1;
	$.ajax({      
		type: "GET",
		url: "{{ url('category-wise-product-listing-pagination') }}",   
		data: {page_no : ID, category_id : CategoryId},       
		success:   
		function(data){
			var jsonData = $.parseJSON(data);
			//alert(jsonData.msg);
			//if(jsonData.status)
			$("#spanscrollcount"+ID).html(jsonData.msg);
			
		}
	});
	
}
</script>
@endsection
@section('css')
@endsection
@section('js')
@endsection



