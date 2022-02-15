@extends('layouts.front')

<?php //echo '<pre>'; //print_r($categorydetails);

//print_r(Cart::getContent());?> 

@section('seo-header')

		<title>{{ $categorydetails }} | JSDAgro</title>

		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<meta name="description" content="">

		<meta name="author" content="">

@endsection		

@section('content')



<div class="container">

<!-- Hide If Empty 	-->


<?php echo $bread_crumb_string; ?>
<!-- New Design -->
	<?php 
	if(isset($all_banners) && count($all_banners)>0)
	{
	?>
	<div class="well np" style="clear:both;">
		<div id="myCarousel" class="carousel slide homCar">
			<div class="carousel-inner">
				<?php 
				$count=0;
				foreach($all_banners as $banner)
				{
				?>
				<div class="item <?php if($count==1) {?>active<?php } ?>">
					<img style="width:100%" src="{{ asset('/storage/category/'.$banner)}}" alt="bootstrap ecommerce templates">
				</div>
				<?php 
				$count++;
				}
				?>
			</div>
			<a class="left carousel-control" href="#myCarousel" data-slide="prev">‹</a>
			<a class="right carousel-control" href="#myCarousel" data-slide="next">›</a>
		</div>
	</div>
	<?php 
	}
	else 
	{
	?>
    <div class="well np" style="clear:both;">
		<div id="myCarousel" class="carousel slide homCar">
			<div class="carousel-inner">
				<div class="item active">
					<img style="width:100%" src="/public/images/listing-banner.jpg" alt="bootstrap ecommerce templates">
				</div>
				<div class="item">
					<img style="width:100%" src="/public/images/listing-banner.jpg" alt="bootstrap ecommerce templates">
				</div>
				<div class="item">
					<img style="width:100%" src="/public/images/listing-banner.jpg" alt="bootstrap ecommerce templates">
				</div>
			</div>
			<a class="left carousel-control" href="#myCarousel" data-slide="prev">‹</a>
			<a class="right carousel-control" href="#myCarousel" data-slide="next">›</a>
		</div>
	</div>
	<?php 
	}
	?>
	
	<div class="subCat">
		@if(count($categories)>0)
		@foreach($categories as $category_details)
	    <div class="subCatInr">
	        <div class="catImg">
				<?php
				if (File::exists(public_path('storage/'.$category_details->category_image))) { ?>
					<img src="{{ asset('/storage/'.$category_details->category_image)}}" alt="">
				<?php } else { ?>
					<img src="{{ asset('/storage/product.png') }}" alt="">
				<?php  } ?>
	        </div>
	        <div class="catName">
	            <h3><a href="{{ route('category', $category_details->name_url) }}">{{ $category_details->name }}</a></h3>
	        </div>
	    </div>
		@endforeach
		@endif
	</div>

	
	@if(count($categories)>0)
	@foreach($categories as $category_details)
	
	<div class="pdtlist product-listing-outer" style="width:100%;float:none;">
	    <div class="product-listing-head">
		    <div class="product-cat-title">{{ $category_details->name }}</div>
		    <a href="{{ route('category', $category_details->name_url) }}">View All</a>
	    </div>
	    <div class="product-list-view">
			<div class="product-boxes"> 
				@php 
				$get_category_ids = Helper::getCategoryIdArray($category_details->id);
				$id_array = [];
				$count=0;
				if(count($get_category_ids)>0){
					foreach($get_category_ids as $key=>$val){
						$id_array[$count] = $key;
						$count++;
						if(count($val)>0){
							foreach($val as $key=>$ids){
								$id_array[$count] = $key;
								$count++;
							}
						}
					}
				}
				if(count($id_array)>0)
				{
					$get_category_products = Helper::get_products_by_category_id_array($id_array,Session::get('swadesh_hut_id'));
				}
				else 
				{
					$get_category_products = Helper::get_products_by_category_id($category_details->id,Session::get('swadesh_hut_id'));
				}
				
				if(count($get_category_products)>0)
				{
				$loop_count = 0;
				foreach($get_category_products as $data)
				{
					$newPrice=$data->price; 
					if(($data->max_price-$data->price)>0)
					{
						$priceHtml='<small id="prdmaxid'.$data->id.'"><del> ₹'.$data->max_price.'</del></small> ₹'.number_format($newPrice,2);
					}
					else 
					{
						$priceHtml='₹'.number_format($newPrice,2);
					}
					$optionProduct=Helper::getProductOptions($data->similar_product, Session::get('swadesh_hut_id'));


					if( count(Cart::getContent())>0 ):
					foreach( Cart::getContent() as $cartData ):
						$productIdName=explode('%',$cartData->name);
						if( $productIdName[0]==$data->id ):
							if( $cartData->attributes['product_availability']>$cartData->quantity):
								$buttonDisable=0; 
							else:
								$buttonDisable=1;
							endif;
						elseif( ($data->available_qty-$data->ordered_qty)>0 ):
							$buttonDisable=0;
						else:
							$buttonDisable=1;
						endif;
					endforeach; 
					elseif( ($data->available_qty-$data->ordered_qty)>0 ):
					$buttonDisable=0;
					else:
					$buttonDisable=1;
					endif;


					$discount_percentage = ($data->max_price-$data->price)*100/$data->max_price;					
					$discount_percentage = number_format($discount_percentage,2);
					$no_dtls = explode('.',$discount_percentage);
					$after_decimal_number = $no_dtls[1];
					if($after_decimal_number=='00')
					{
						$show_percentage = substr($discount_percentage,0,strpos($discount_percentage,'.'));
					}
					else 
					{
						if($after_decimal_number>50){
							$show_percentage = ceil($discount_percentage);
						}else if($after_decimal_number<=50){
							$show_percentage = floor($discount_percentage);
						}
						
					}

					if($buttonDisable==0 && $loop_count<=5)
					{
						$loop_count++;
				@endphp
			    <div class="product-box">
					
					<br>
					<?php if(($data->max_price-$data->price)>0) { ?><div class="discounts" id="percent_container{{$data->id}}">{{$show_percentage}}% off</div><?php } ?>
					
					<div class="prodImg">
						<a href="{{ route('products', $data->name_url) }}" class="prodWrapper">
						<?php
						if (File::exists(public_path('storage/'.$data->product_image))) { ?>
						<img src="{{ asset('/storage/'.$data->product_image)}}" alt="">
						<?php } else { ?>
						<img src="{{ asset('/storage/product.png') }}" alt="">
						<?php  } ?>
						</a>
					</div>

					
                    <div class="prodTitle"><a href="{{ route('products', $data->name_url) }}">{{ $data->prod_name }}</a></div>
					
					
					<div class="prodBtm">
						<div class="prodPri" id="price_container_{{ $data->id }}">{!! $priceHtml !!}</div>
						<?php 
						if(count($optionProduct)>1)
						{
						?>
						<select class="form-control" id="product_weight_price" onchange="get_product_price(this,{{ $data->id }})" style="margin-left: 2px; font-size: 12px;">
							<?php 
							if(count($optionProduct)>0)
							{
								foreach($optionProduct as $option)
								{
									if($option->prod_name==$data->prod_name)
									{
										if($option->swadesh_hut_id == Session::get('swadesh_hut_id'))
										{
											$final_price = $option->price;

											$selected = ($option->id==$data->id)?" selected='selected' ":'';
	
											echo '<option value="'.$option->id.'" '.$selected.'><strong>'.intval($option->weight_per_pkt).'&nbsp;'.$option->weight_unit.' -- ₹ '. number_format($final_price,2) .'</strong></option>';
										}
									}
								}
							}
							?>	
						</select>
						<?php 
						} 
						else 
						{
						?>
						<select class="form-control"style="margin-left: 2px; font-size: 12px;">
							<option value="{{ intval($data->weight_per_pkt).'&nbsp;'.$data->weight_unit.' -- ₹ '. number_format($data->price,2) }}">{{ intval($data->weight_per_pkt).' '.$data->weight_unit.' -- ₹ '. number_format($data->price,2) }}</option>
						</select>
						<?php 
						}
						?>
						
						@php date_default_timezone_set('Asia/Kolkata'); //echo date("H:i:s");  @endphp
						@php
						$get_swadesh_hut_details = Helper::get_swadesh_hut_details(Session::get('swadesh_hut_id'));

						if(isset($get_swadesh_hut_details->close_time) && $get_swadesh_hut_details->close_time!='')
						{
							$close_time = $get_swadesh_hut_details->close_time;
						}
						else 
						{
							$close_time = '17:00:00';
						}


						if (date('H:i:s') < $close_time) {
							echo "<div class='deliverymsg'><Strong>Standard Delivery</Strong><br/>Today</div>";
						}else {
							echo "<div class='deliverymsg'><Strong>Standard Delivery</Strong><br/>Tomorrow</div>";
						}
						@endphp
						
						<?php
						if( $buttonDisable==0 ){
						?>
						<input type="hidden" name="cart_product_{{ $data->id }}" id="cart_product_{{ $data->id }}" value="{{ $data->id }}"/>
						<button type="submit" id="processtoshipping" onclick="addToCart({{ $data->id }});" class="homeshopbtn">Add to cart</button>
						<?php
						}
						else 
						{
						?>
						<button type="button" id="processtoshipping" style="background-color:#FF0000; border: #FF0000 1px solid!important;" class="homeshopbtn">Out Of Stock</button>
						<?php 
						}
						?>
					</div>  
				</div>
				@php 
					}
				}
				}
				@endphp
	       </div>
        </div>
    </div>
    @endforeach
	@else
	<div class="pdtlist product-listing-outer" style="width:100%;float:none;">
	    <div class="product-listing-head">
		    <div class="product-cat-title">{{ $categorydetails }}</div>
	    </div>
		
	    <div class="product-list-view">
			<div class="product-boxes"> 
				@php 
				if(count($products)>0)
				{
				foreach($products as $data)
				{
					$newPrice=$data->price; 
					if(($data->max_price-$data->price)>0)
					{
						$priceHtml='<small id="prdmaxid'.$data->id.'"><del> ₹'.$data->max_price.'</del></small> ₹'.number_format($newPrice,2);
					}
					else 
					{
						$priceHtml='₹'.number_format($newPrice,2);
					}
					$optionProduct=Helper::getProductOptions($data->similar_product, 3);
					

					if( count(Cart::getContent())>0 ):
					foreach( Cart::getContent() as $cartData ):
						$productIdName=explode('%',$cartData->name);
						if( $productIdName[0]==$data->id ):
							if( $cartData->attributes['product_availability']>$cartData->quantity):
								$buttonDisable=0; 
							else:
								$buttonDisable=1;
							endif;
						elseif( ($data->available_qty-$data->ordered_qty)>0 ):
							$buttonDisable=0;
						else:
							$buttonDisable=1;
						endif;
					endforeach; 
					elseif( ($data->available_qty-$data->ordered_qty)>0 ):
					$buttonDisable=0;
					else:
					$buttonDisable=1;
					endif;


					$discount_percentage = ($data->max_price-$data->price)*100/$data->max_price;					$discount_percentage = number_format($discount_percentage,2);
					$no_dtls = explode('.',$discount_percentage);
					$after_decimal_number = $no_dtls[1];
					if($after_decimal_number=='00')
					{
						$show_percentage = substr($discount_percentage,0,strpos($discount_percentage,'.'));
					}
					else 
					{
						if($after_decimal_number>50){
							$show_percentage = ceil($discount_percentage);
						}else if($after_decimal_number<=50){
							$show_percentage = floor($discount_percentage);
						}
					}

					
				@endphp
			    <div class="product-box">
					<?php if(($data->max_price-$data->price)>0) { ?><div class="discounts" id="percent_container{{$data->id}}">{{$show_percentage}}% off</div><?php } ?>
					<div class="prodImg">
						<a href="{{ route('products', $data->name_url) }}" class="prodWrapper">
						<?php
						if (File::exists(public_path('storage/'.$data->product_image))) { ?>
						<img src="{{ asset('/storage/'.$data->product_image)}}" alt="">
						<?php } else { ?>
						<img src="{{ asset('/storage/product.png') }}" alt="">
						<?php  } ?>
						</a>
					</div>
                    <div class="prodTitle"><a href="{{ route('products', $data->name_url) }}">{{ $data->prod_name }}</a></div>
					<div class="prodBtm">
						<div class="prodPri" id="price_container_{{ $data->id }}">{!! $priceHtml !!}</div>
						<?php 
						if(count($optionProduct)>1)
						{
						?>
						<select class="form-control" id="product_weight_price" onchange="get_product_price(this,{{ $data->id }})" style="width: 160px; margin-left: 2px; font-size: 12px;">
							<?php 
							if(count($optionProduct)>0)
							{
								foreach($optionProduct as $option)
								{
									if($option->prod_name==$data->prod_name)
									{
										if($option->swadesh_hut_id == Session::get('swadesh_hut_id'))
										{
											$final_price = $option->price;

											$selected = ($option->id==$data->id)?" selected='selected' ":'';
	
											echo '<option value="'.$option->id.'" '.$selected.'><strong>'.intval($option->weight_per_pkt).'&nbsp;'.$option->weight_unit.' -- ₹ '. number_format($final_price,2) .'</strong></option>';
										}
										
									}
								}
							}
							?>	
						</select>
						<?php 
						} 
						else 
						{
						?>
						<select class="form-control"style="width: 160px; margin-left: 2px; font-size: 12px;">
							<option value="{{ intval($data->weight_per_pkt).'&nbsp;'.$data->weight_unit.' -- ₹ '. number_format($data->price,2) }}">{{ intval($data->weight_per_pkt).' '.$data->weight_unit.' -- ₹ '. number_format($data->price,2) }}</option>
						</select>
						<?php 
						}
						?>
						<?php
						if( $buttonDisable==0 ){
						?>
						<input type="hidden" name="cart_product_{{ $data->id }}" id="cart_product_{{ $data->id }}" value="{{ $data->id }}"/>
						<button type="submit" id="processtoshipping" onclick="addToCart({{ $data->id }});" class="homeshopbtn">Add to cart</button>
						<?php
						}
						else 
						{
						?>
						<button type="button" id="processtoshipping" style="background-color:#FF0000; border: #FF0000 1px solid!important;" class="homeshopbtn">Out Of Stock</button>
						<?php 
						}
						?>
					</div>  
				</div>
				@php 
					
				}
				}
				@endphp
	       </div>
        </div>
		
    </div>
    </div>
	@endif

<!-- New Design -->



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

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

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

<script>

function addToCart(ID){

	var pid = $("#cart_product_"+ID).val();

	$.ajax({      

		type: "GET",

		url: "{{ url('product-add-to-cart') }}",   

		data: {product_id : pid, quantity: 1},       

		success:     

		function(data){

			var jsonData = $.parseJSON(data);

			//alert(jsonData.msg);

			swal("Success!", jsonData.msg , "success");

			//alert(jsonData.buttonEnable);

			$("#shprocnt").html('<strong>'+jsonData.status+'</strong>');

			if(jsonData.buttonEnable=='1'){

				location.reload();  

			}

		}

	});

}

function get_product_price(option,pid)

{

	var productId = option.value;

	 

	$.ajax({      

		type: "POST",

		url: "{{ url('get-product-price-by-id') }}",   

		data: {

        "_token": "{{ csrf_token() }}",

        "product_id": productId

        },       

		dataType: "json",

		success:   

		function(data){  

		    

		   // if( data.discount >0 ) {

				//$newPri=((100-(data.discount))*(data.price)/100); 

			//	$priceHtml='<small><del> ₹'.$data->price.'</del></small> ₹'.number_format($newPrice,2);

					//	else:

					//		$priceHtml='₹'.$data->price;

							

			let x = data.price;

			let y =data.max_price;


			let discount_percentage = ((Number(y)-Number(x))*100/Number(y)).toFixed(2);
			let no_dtls = discount_percentage.split(".");
			let after_decimal_number = no_dtls[1];
			if(after_decimal_number=='00')
			{
				var show_percentage = discount_percentage.substr(0,discount_percentage.indexOf('.'));
			}
			else 
			{
				if(after_decimal_number>50){
					var show_percentage = Math.ceil(discount_percentage);
				}else if(after_decimal_number<=50){
					var show_percentage = Math.ceil(discount_percentage);
				}
			}



			$("#price_container_"+pid).html('<small id="prdmaxid'+pid+'"><del> ₹'+y.toLocaleString('us', {minimumFractionDigits: 2, maximumFractionDigits: 2})+ '</del></small> ₹'+(x.toLocaleString('us', {minimumFractionDigits: 2, maximumFractionDigits: 2})));	


			if(show_percentage>0) 
			{ 
				$("#percent_container"+pid).show(); 
				$("#prdmaxid"+pid).show(); 
				$("#percent_container"+pid).html(show_percentage+'% off'); 
			}
			else 
			{
				$("#percent_container"+pid).hide(); 
				$("#prdmaxid"+pid).hide(); 
			}

			

		    //} else {

		       //$("#price_container_"+pid).html('₹'+(x.toLocaleString('us', {minimumFractionDigits: 2, maximumFractionDigits: 2})));

		  // }

		    

		//	$("#price_container_"+pid).html('₹'+(data.price));

			$("#unit_container_"+pid).html( data.weight_per_pkt + ' ' + data.weight_unit ); 

			$("#cart_product_"+pid).val(productId);

			

		}

	}); 

}

</script>

@endsection 







