@extends('layouts.front')

@section('seo-header')

		<title>JSD Agro : {{ $details->prod_name }}</title>

		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<meta name="description" content="">

		<meta name="author" content="">

@endsection	

<?php //echo '<pre>';print_r($details);

$buttonDisable=0;

if( !empty(Cart::getContent()) ):

	foreach( Cart::getContent() as $cartData ):

		$productIdName=explode('%',$cartData->name);

		if( $productIdName[0]==$details->id ):

		//echo $cartData->attributes['product_availability'];

		//echo $cartData->quantity;

			if( $details->available_qty=$cartData->quantity):

				$buttonDisable='1';

			endif;

		endif;

	endforeach;  

endif;

?>

@section('content')







		


<div class="container">
<div class="product-detail-wrap">
    
    <?php echo $bread_crumb_string; ?>

	<div class="product-detail-outer"> 

		<div class="productZoom">

		    

		    

		<!-- <img id="img_01" src="{{ asset('/storage/'.$details->product_image) }}" data-zoom-image="{{ asset('/storage/'.$details->product_image) }}"/> -->



		<?php								     							 

									if (File::exists(public_path('storage/'.$details->product_image))) { ?>

										<img id="img_01" src="{{ asset('/storage/'.$details->product_image) }}" data-zoom-image="{{ asset('/storage/'.$details->product_image) }}"/>

                                     <?php } else { ?>

										<img id="img_01" src="{{ asset('/storage/product.png') }}" data-zoom-image="{{ asset('/storage/product.png') }}"/>

                                    <?php  } ?>	

			

			<div id="gallery_01" class="gal_thumbnail">

			

				<a href="#" data-image="{{ asset('/storage/'.$details->product_image) }}" data-zoom-image="{{ asset('/storage/'.$details->product_image) }}">

					<!-- <img  src="{{ asset('/storage/'.$details->product_image) }}"  style="width:50px;"/> -->

					<?php								     							 

									if (File::exists(public_path('storage/'.$details->product_image))) { ?>

										<img src="{{ asset('/storage/'.$details->product_image)}}" style="width:50px;">

                                     <?php } else { ?>

	                                    <img src="{{ asset('/storage/product.png') }}" alt="">

                                    <?php  } ?>

				</a>

				<?php   

				if($details->other_image<>'')

				{

					$images = explode(",",$details->other_image); 

				

				foreach($images as $image){ ?>

				<a href="#" data-image="{{ asset('/storage/'.$image) }}" data-zoom-image="{{ asset('/storage/'.$image) }}">

					<!-- <img  src="{{ asset('/storage/'.$image) }}" style="width:50px;" /> -->

					<?php								     							 

									if (File::exists(public_path('storage/'.$image))) { ?>

										<img src="{{ asset('/storage/'.$image)}}" style="width:50px;">

                                     <?php } else { ?>

	                                    <img src="{{ asset('/storage/product.png') }}" alt="">

                                    <?php  } ?>



				</a>

				<?php 

				}  

				}

				?>  



			</div>

			 

		</div>

		

		<div class="productDescription">

			<!-- <p style="color:red; margin-left:10px;">* Please complete your purchase before 4pm to get same day delivery</p> -->
			

			<h3>{{ $details->prod_name }}</h3>

			@if( $details->discount && $details->discount >0 )

				@php 
					if(isset(Auth::user()->user_type) && Auth::user()->user_type=='Wholeseller')
					{
						$newPrice=((100-$details->discount)*$details->wholesale_price/100);
						$priceHtml='<small><del> ₹'.$details->wholesale_price.'</del></small> ₹'.number_format($newPrice,2);
					}
					else 
					{
						$newPrice=((100-$details->discount)*$details->price/100);
						$priceHtml='<small><del> ₹'.$details->price.'</del></small> ₹'.number_format($newPrice,2);
					}

					



				else:
					if(isset(Auth::user()->user_type) && Auth::user()->user_type=='Wholeseller')
					{
						$priceHtml='₹'.$details->wholesale_price;
					}
					else 
					{
						$priceHtml='₹'.$details->price;
					}

				@endphp

			@endif

				<!--form class="form-horizontal qtyFrm"-->

				

			 

			<div class="prodPricing">Price: <span id="price_container">@php echo $priceHtml;@endphp</span><p><span>(Inclusive of all taxes)</span></p></div>

			@if(count($link_similar_products)>0)

			

			<div class="product-box-similar">

			
			@foreach($link_similar_products as $sproducts)
			

			@if($sproducts->prod_name==$details->prod_name)

			@if(isset($sproducts->swadesh_hut_id) && $sproducts->swadesh_hut_id==Session::get('swadesh_hut_id'))

			<div class="deliveryitem <?php if($sproducts->weight_per_pkt==$details->weight_per_pkt) { echo 'active'; } ?>"><a href="{{ route('products', $sproducts->name_url) }}" class="sprodWrapper">{{ intval($sproducts->weight_per_pkt) }} {{ $sproducts->weight_unit}}</a></div>

			@endif

			@endif

			@endforeach

			</div>

			@else 
			<br clear="all">
			<div class="deliveryitem active"><a href="{{ route('products', $details->name_url) }}" class="sprodWrapper">{{ intval($details->weight_per_pkt) }} {{ $details->weight_unit}}</a></div>
			<br clear="all">
			@endif

			 

						
			

			

          <div class="deliveryfree">Delivery: Free</div>

          @php

						if( $buttonDisable=='1'):

							$disabled='disabled ';

							$addClass='asdf';

							$btnstl="style='backgroundColor: #f8940691; color: white; cursor: not-allowed;'";

						elseif( ($details->available_qty-$details->ordered_qty)>0 ):

							$disabled='';

							$btnstl='';

							$addClass='';

						else:

							$disabled='disabled';

							$addClass='asdf';

							$btnstl='style="backgroundColor: #f8940691; color: white, cursor: not-allowed"';

						endif

					@endphp
					
					
					
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
					if (date('H') < $close_time) {
						echo "<div class='deliverymsg' style='float:left; margin-left: 12px; padding-right: 13px;'><strong>Standard Delivery</strong><br/>Today</div>";
					}else {
						echo "<div class='deliverymsg' style='float:left; margin-left: 12px; padding-right: 13px;'><strong>Standard Delivery</strong><br/>Tomorrow</div>";
					}
					@endphp

          <div class="quant">
			@php
		  	if(isset(Auth::user()->user_type) && Auth::user()->user_type=='Wholeseller')
			{
				//echo '<input type="number" name="quantity" id="quantity" readonly value="'.$details->wholesale_min_qty.'">';
			}
			else 
			{
				//echo '<input type="number" name="quantity" id="quantity" min="1" max="5" value="1">';
			}
			@endphp


			<?php if($disabled=='disabled'){ ?>
			<button type="button" id="processtoshipping" style="background-color:#FF0000; border:#FF0000 1px solid!important;" class="homeshopbtn">Out Of Stock</button>
			<?php } else { ?>
			<input type="number" name="quantity" id="quantity" min="1" max="5" value="1">
			<button type="submit" id="processtoshipping" onclick="addToCart('{{ $details->id }}','{{ $details->available_qty }}');" class="shopBtn {{$addClass}}" {{ $disabled }}>Add to cart</button>
			<?php } ?>

          </div>

			<div class="shortDesc">

              <h4>Description</h4>

              {!! $details->description !!}

              </div>

					

				<!--/form-->

		</div>

</div>

		<div class="productDetails">
			
		  @php  if( !empty($details->specification) ): @endphp

			<h4>Specification</h4>

			{!! $details->specification !!}

			 @php  endif ; 

			 if( !empty($details->manufacturer_details) ): @endphp

			<h4>Manufacturer Details</h4> 

			<p>{!! $details->manufacturer_details !!}</p>

			@php  endif ;

			 if( !empty($details->marketed_by) ): @endphp

			<h4>Marketed By</h4>

			<p>{!! $details->marketed_by !!}</p>

			@php  endif ; 

			 if( !empty($details->country_of_origin) ): @endphp

			<h4>Country Of Origin</h4>

			<p>{!! $details->country_of_origin !!}</p>

			@php  endif ; 

			 if( !empty($details->customer_care_details) ): @endphp

			<h4>Customer Care Details</h4>

			<p>{!! $details->customer_care_details !!}</p>

			@php  endif ; 

			 if( !empty($details->seller) ): @endphp

			<h4>Seller</h4>

			<p>{!! $details->seller !!}</p>

				@php  endif ; 

			 if( !empty($details->barcode) ): @endphp

			<h4>Barcode</h4>

			<p>{!! $details->barcode !!}</p>

			

				@php  endif ; @endphp

		</div>

		

		

		

	

</div><!-- .product-detail-wrap -->









<!--

<div class="product-listing-outer fullwidth">

	<div class="product-listing-head">

		<div class="product-cat-title">Similar Products</div>

	</div>

	<div class="product-list-view">

			<div class="product-boxes"> 

			<?php

			$buttonDisable='';

			?>

			@php

				if( !$get_similar_products->isEmpty() ):

			@endphp

				 

			@php

			

					$i=1;

					foreach($get_similar_products as $data):

						if( $data->discount && $data->discount >0 ):

							$newPrice=((100-$data->discount)*$data->price/100); 

							$priceHtml='<small><del> ₹'.$data->price.'</del></small> ₹'.number_format($newPrice,2);

						else:

							$priceHtml='₹'.$data->price;

						endif;

			@endphp

			<?php 		

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

						

			?>

						<div class="product-box">

								<div class="prodImg">

									<a href="{{ route('products', $data->id) }}" class="prodWrapper">

										<img src="{{ asset('/storage/'.$data->product_image)}}" alt="">

									</a>

								</div>

							

							

								<div class="prodTitle"><a href="{{ route('products', $data->id) }}">{{ $data->prod_name }}</a></div>

							

								<div class="prodBtm">

									<div class="prodPri">{!! $priceHtml !!}</div>

									

									<?php

									/*if( $buttonDisable==0 ){*/

									?>

									

									<button type="submit" id="processtoshipping" onclick="addToCart({{ $data->id }});" class="shopBtn ">Add to cart</button>

									<?php

									/*}*/

									?>

								

								</div>  

						</div>

			@php

			

					$i++;

					endforeach;

				else:

			@endphp

			<?php echo '<div class="emptyProduct"><span>Sorry, Product is Not Available Yet</span></div>';?>

			@php

				endif;

			@endphp

			

			</div>

					

					

			-->					

				

	</div>

</div>

</div>















@endsection







@section('css')

<style>

	/*set a border on the images to prevent shifting*/

	#gallery_01 img{border:2px solid white;}

 

 /*Change the colour*/

 .active img{border:2px solid #333 !important;}

  

</style>

@endsection

@section('js')

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>  

<script src="https://www.elevateweb.co.uk/wp-content/themes/radial/jquery.elevatezoom.min.js" type="text/javascript"></script>

<script src="https://www.elevateweb.co.uk/wp-content/themes/radial/jquery.fancybox.pack.js" type="text/javascript"></script> 

<script>

function addToCart(ID,available_qty){

	var quantity = $("#quantity").val();

	if(quantity>available_qty)
	{
		swal("Oops!", 'Sorry! '+quantity+' item of this product is not available' , "error");
		return false;
	}

	var weight_per_packet = $("#product_weight_price").val();

	$.ajax({      

		type: "GET",

		url: "{{ url('product-add-to-cart') }}",   

		data: {product_id: ID, quantity: quantity, weight_per_packet: weight_per_packet},       

		success:   

		function(data){

			var jsonData = $.parseJSON(data);

			//alert(jsonData.msg);

			//alert(jsonData.buttonEnable);

			swal("Success!", jsonData.msg , "success");

			$("#shprocnt").html('My Cart<strong>'+jsonData.status+' items</strong>');

			
			if(jsonData.buttonEnable=='1'){

				//$("#shprocnt").html(jsonData.status+' Item(s)');

				$('#processtoshipping').prop('disabled', true);

				$("#processtoshipping").css({"backgroundColor": "#f8940691", "color": "white", "cursor": "not-allowed"});

			}

		}

	});

}





function get_product_price(product_id)

{

	var weight_per_packet = $("#product_weight_price").val();

	$.ajax({      

		type: "POST",

		url: "{{ url('get-product-price-by-weight') }}",   

		data: {

        "_token": "{{ csrf_token() }}",

        "product_id": product_id,

		"weight_per_packet": weight_per_packet

        },       

		dataType: "json",

		success:   

		function(data){

			$("#price_container").html('₹'+(data.price));

		}

	});



	 

}

 

</script> 

<script type="text/javascript">

//initiate the plugin and pass the id of the div containing gallery images

$("#img_01").elevateZoom({gallery:'gallery_01', cursor: 'pointer', galleryActiveClass: 'active', imageCrossfade: true, loadingIcon: 'http://www.elevateweb.co.uk/spinner.gif'}); 



//pass the images to Fancybox

$("#img_01").bind("click", function(e) {  

  var ez =   $('#img_01').data('elevateZoom');	

	$.fancybox(ez.getGalleryList());

  return false;

}); 

 

</script> 

@endsection

