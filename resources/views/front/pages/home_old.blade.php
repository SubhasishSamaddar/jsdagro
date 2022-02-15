@extends('layouts.front')

<?php //echo '<pre>'; print_r($newProducts); echo '</pre>';?>

@section('seo-header')

		<title>Home Page</title>

		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<meta name="description" content="">

		<meta name="author" content="">

@endsection




@section('content')

	<!-- 	BANNER SECTION -->

	<div class="well np">

		<div id="myCarousel" class="carousel slide homCar">

			<div class="carousel-inner">

			<?php //print_r($banners);die;?>

				@if( !empty($banners) )

					@php $i=1; @endphp

					@foreach( $banners as $bdata )

					@php

						if($i==1):

							$active='active';

						else:

							$active='';

						endif;

					@endphp

					<?php //echo $bdata->banner_image;die;?>

					<div class="item {{ $active }}">

						<img style="width:100%" src="{{ asset('/storage/banner/'.$bdata->banner_image )}}" alt="bootstrap ecommerce templates">

						

					</div>

					@php $i++;@endphp

					@endforeach

				@endif

				

				</div>

			<a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>

			<a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>

		</div>

	</div>
	</div>

	<!-- 	END BANNER SECTION -->

<div class="container">

<div class="featured_products">

	<!--<h2>Featured Products</h2>-->

		<div class="productsrow">			

				<ul class="prod">

                  @if( !$featuredroducts->isEmpty() )

					@php $i=1; @endphp

					@foreach( $featuredroducts as $newData )

						@php
							
							

								if(isset(Auth::user()->user_type) && Auth::user()->user_type=='Wholeseller')
								{
									$newPrice=((100-$newData->discount)*$newData->wholesale_price/100);
								}
								else 
								{
									$newPrice=$newData->price;
								}
								
								if(isset(Auth::user()->user_type) && Auth::user()->user_type=='Wholeseller')
								{
									$priceHtml='<small><del> र'.$newData->wholesale_price.'</del></small> र'.number_format($newPrice,2);
								}
								else 
								{
									if(($newData->max_price-$newData->price)>0)
									{
										$priceHtml='<small id="prdmaxid'.$newData->id.'"><del> ₹'.$newData->max_price.'</del></small> ₹'.number_format($newPrice,2);
									}
									else 
									{
										$priceHtml='₹'.number_format($newPrice,2);
									}
								}
								//echo $priceHtml;
								$dropdown_price = number_format($newPrice,2);

							

						@endphp

						



						<!--- ADD TO CART FUNCTIONALITY-->

						@php

						$buttonDisable=0;

						

						

						$catProduct=Helper::get_product_by_categorys($newData->id, $swadesh_hut_id);

						$optionProduct=Helper::getProductOptions($newData->similar_product, $swadesh_hut_id);	

						if($catProduct):

							foreach( $catProduct as $cpdata ):

								/*if( $cpdata->discount && $cpdata->discount >0 ):

									if(Auth::user()->user_type=='Wholeseller')
									{
										$newPrice=((100-$cpdata->discount)*$cpdata->wholesale_price/100);

										$priceHtml='<small><del> र'.$cpdata->wholesale_price.'</del></small> र'.number_format($newPrice,2);

									}
									else 
									{
										$newPrice=$cpdata->price;

										$priceHtml='<small><del> र'.$cpdata->price.'</del></small> र'.number_format($newPrice,2);

									}

									
									

								else:
									if(Auth::user()->user_type=='Wholeseller')
									{
										$priceHtml='6र'.$cpdata->wholesale_price;
									}
									else 
									{
										$priceHtml='6र'.$cpdata->price;
									}
									

								endif;*/

						

								if( count(Cart::getContent())>0 ):

									foreach( Cart::getContent() as $cartData ):

										$productIdName=explode('%',$cartData->name);

										if( $productIdName[0]==$cpdata->id ):

											if( $cartData->attributes['product_availability']>$cartData->quantity):

												$buttonDisable=0; 

											else:

												$buttonDisable=1;

											endif;

										elseif( ($cpdata->available_qty-$cpdata->ordered_qty)>0 ):

											$buttonDisable=0;

										else:

											$buttonDisable=1;

										endif;

									endforeach; 

								elseif( ($cpdata->available_qty-$cpdata->ordered_qty)>0 ):

									$buttonDisable=0;

								else:

									$buttonDisable=1;

								endif;

							endforeach;

						endif;

						@endphp

						<!----END ADD CART FUNCTIONALITY-->







						<li>

							<div class="prodthumbnail">

								<?php 

									$discount_percentage = ($newData->max_price-$newData->price)*100/$newData->max_price;					
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




								?>

								<?php if(($newData->max_price-$newData->price)>0) { ?><div class="discounts" id="percent_container{{$newData->id}}">{{$show_percentage}}% off</div><?php } ?>

								<figure>

									<!--<img src="{{ asset('/storage/'.$newData->product_image) }}" alt="">-->

									<?php

									if (File::exists(public_path('storage/'.$newData->product_image))) { ?>

										<img src="{{ asset('/storage/'.$newData->product_image)}}" alt="">

                                     <?php } else { ?>

	                                    <img src="{{ asset('/storage/product.png') }}" alt="">

                                    <?php  } ?>

									</figure>

								<div class="caption">
								

                                  <h5><a  href="{{ route('products',$newData->id) }}">{{ $newData->prod_name }}</a></h5><h3><center id="price_container_{{ $newData->id }}">{!! $priceHtml !!}</center></h3>

								  <h3 id="unit_container_{{ $newData->id }}">{{ $newData->weight_per_pkt }} {{ $newData->weight_unit }}</h3>

								   

									   

								   <?php 

			if(count($optionProduct)>1)

			{
				
			?>

			<select class="form-control" id="product_weight_price" onchange="get_product_price(this,{{ $newData->id }})" style="width:180px;margin-left:2px;">

				<?php 

				if(count($optionProduct)>0)

				{

					foreach($optionProduct as $option)

					{
						if(isset(Auth::user()->user_type) && Auth::user()->user_type=='Wholeseller')
						{
							$final_price = ((100-$option->discount)*$option->wholesale_price/100);
						}
						else
						{
							$final_price = $option->price;
						}
						

						$selected = ($option->id==$newData->id)?" selected='selected' ":'';

						echo '<option value="'.$option->id.'" '.$selected.'><strong>'.intval($option->weight_per_pkt).'&nbsp;'.$option->weight_unit.' -- ₹ '. number_format($final_price,2) .'</strong></option>';

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
				<option value="{{ intval($newData->weight_per_pkt).'&nbsp;'.$newData->weight_unit.' -- ₹ '. number_format($newData->price,2) }}">{{ intval($newData->weight_per_pkt).' '.$newData->weight_unit.' -- ₹ '. number_format($newData->price,2) }}</option>
			<select>
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



									/*if( $buttonDisable==0 ){*/

									?>

										<input type="hidden" name="cart_product_{{ $newData->id }}" id="cart_product_{{ $newData->id }}" value="{{ $newData->id }}"/> 

										<button type="submit" onclick="addToCart({{ $newData->id }});" class="homeshopbtn">Add to cart</button><br clear="all">

									<?php

									/*}*/

									?>

									

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

				

			

		@endif

                  </ul>

  </div>

	</div>















	<!-- 	SMALL BANNER SECTION -->

	

      <div class="smallbanners">

		@php

			if( !empty($smallBanners) ):

				$i=1;

				foreach( $smallBanners as $sbdata ):

					if( $i<4 ):

		@endphp

					<div class="smbanner">

						

							<a href="{{ $sbdata->banner_url }}">

								<img src="{{ asset('/storage/banner/small/'.$sbdata->banner_image)}}" alt="">

							</a>	

					</div>

		@php

					$i++;

					endif;  

				endforeach;

			endif;

		@endphp

     </div>



	<!-- 	END SMALL BANNER SECTION -->

	<!-- 	SHOW CATEGORY PRODUCT SECTION -->

	 

	@if( !$showinHomePageCategory->isEmpty() )

			@foreach( $showinHomePageCategory as $shData )

				@php 

				if( $shData->id ):

				$category_product_array = Helper::getCategoryIdArray($shData->id);  

				$sub_category_id_array = array();
				$count=0;
				foreach($category_product_array as $key=>$val){
					$sub_category_id_array[$count] = $key;
					$count++;
					if(count($val)>0){
						foreach($val as $key=>$ids){
							$sub_category_id_array[$count] = $key;
							$count++;
						}
					}
				}
				
				if(!empty($sub_category_id_array)){
					$catProduct=Helper::get_products_by_category_id_array($sub_category_id_array, $swadesh_hut_id);
				}
				else{
					$catProduct=Helper::get_products_by_category_id($shData->id, $swadesh_hut_id);
				}
				

				//echo '<pre>'; print_r($catProduct);echo '</pre>';

				//echo $shData->id.'####'.$swadesh_hut_id;

				@endphp    

				<div class="row margbot20">

                  <div class="categorylisting">

                    <h4><?php echo $shData->name;?></h4>

					<div class="listingrow">

						<ul class="thumbnails">

				@php

				if($catProduct):
					$loop_count = 0;
					foreach( $catProduct as $cpdata ):

						

							if(isset(Auth::user()->user_type) && Auth::user()->user_type=='Wholeseller')
							{
								$newPrice=((100-$cpdata->discount)*$cpdata->wholesale_price/100);
								$priceHtml='<small><del> र'.$cpdata->max_price.'</del></small> र'.number_format($newPrice,2);
							}
							else 
							{
								$newPrice=$cpdata->price;
								if(($cpdata->max_price-$cpdata->price)>0)
								{
									$priceHtml='<small id="prdmaxid2'.$cpdata->id.'"><del> ₹'.$cpdata->max_price.'</del></small> ₹'.number_format($newPrice,2);
								}
								else 
								{
									$priceHtml='₹'.number_format($newPrice,2);
								}
							}

							

						

						$optionProduct=Helper::getProductOptions($cpdata->similar_product, $swadesh_hut_id);	

						if( count(Cart::getContent())>0 ):

							foreach( Cart::getContent() as $cartData ):

								$productIdName=explode('%',$cartData->name);

								if( $productIdName[0]==$cpdata->id ):

									if( $cartData->attributes['product_availability']>$cartData->quantity):

										$buttonDisable=0; 

									else:

										$buttonDisable=1;

									endif;

								elseif( ($cpdata->available_qty-$cpdata->ordered_qty)>0 ):

									$buttonDisable=0;

								else:

									$buttonDisable=1;

								endif;

							endforeach; 

						elseif( ($cpdata->available_qty-$cpdata->ordered_qty)>0 ):

							$buttonDisable=0;

						else:

							$buttonDisable=1;

						endif;

						if($buttonDisable==0 && $loop_count<5)
						{
							$loop_count++;

				@endphp		

					<li>

						<div class="prodWrapper">

						<?php 
									
							$discount_percentage = ($cpdata->max_price-$cpdata->price)*100/$cpdata->max_price;
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
						?>

							<?php if(($cpdata->max_price-$cpdata->price)>0) { ?><div class="discounts" id="percent_container2{{$cpdata->id}}">{{$show_percentage}}% off</div><?php } ?>

							<div class="thumbnail">

								<a  href="{{ route('products',$cpdata->name_url) }}">

									<!--<img src="{{ asset('/storage/'.$cpdata->product_image) }}" alt=""  width="200" height="160">-->

									<?php

									if (File::exists(public_path('storage/'.$cpdata->product_image))) { ?>

										<img src="{{ asset('/storage/'.$cpdata->product_image)}}" alt="" width="200" height="160">

                                     <?php } else { ?>

	                                    <img src="{{ asset('/storage/product.png') }}" alt="" width="200" height="160">

                                    <?php  } ?>

								</a>

							</div>

							<div class="caption">

								

								<h5><a href="{{ route('products',$cpdata->name_url) }}">{{ $cpdata->prod_name }}</a></h5>

								<?php 
								if(isset(Auth::user()->user_type) && Auth::user()->user_type=='Wholeseller' && $cpdata->wholesale_price!=0)
								{
								?>
								<h3><center id="cprice_container_{{ $cpdata->id }}">{!! $priceHtml !!}</center></h3>
								<?php 
								}
								else 
								{
								?>
								<h3><center id="cprice_container_{{ $cpdata->id }}">{!! $priceHtml !!}</center></h3>
								<?php 
								}
								?>

								<h3 style="display: none;" id="cunit_container_{{ $cpdata->id }}">{{ $cpdata->weight_per_pkt }} {{ $cpdata->weight_unit }}</h3>


								


							</div>



							<?php 

								if(count($optionProduct)>1)

								{

								?>

								<select class="form-control" id="product_weight_price" onchange="get_cproduct_price(this,{{ $cpdata->id }})" style="width:180px;margin-left:2px;">

									<?php 

									if(count($optionProduct)>0)

									{
										foreach($optionProduct as $option)
										{
											if($option->prod_name==$cpdata->prod_name)
											{
												if($option->swadesh_hut_id == Session::get('swadesh_hut_id'))
												{
													$final_price = $option->price;

													$selected = ($option->id==$cpdata->id)?" selected='selected' ":'';
			
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
									<option value="{{ intval($cpdata->weight_per_pkt).'&nbsp;'.$cpdata->weight_unit.' -- ₹ '. number_format($cpdata->price,2) }}">{{ intval($cpdata->weight_per_pkt).' '.$cpdata->weight_unit.' -- ₹ '. number_format($cpdata->price,2) }}</option>
								<select>
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

							//echo 'Available : '.$cpdata->available_qty.' Order : '.$cpdata->ordered_qty;

							
							
							
							
							
							if( $buttonDisable==0 ){

							?>

								<input type="hidden" name="ccart_product_{{ $cpdata->id }}" id="ccart_product_{{ $cpdata->id }}" value="{{ $cpdata->id }}"/> 

								<?php 
								if(isset(Auth::user()->user_type) && Auth::user()->user_type=='Wholeseller')
								{
									if($cpdata->wholesale_price!=0)
									{
									?>
									<button type="submit" onclick="addToCartC({{ $cpdata->id }});" class="homeshopbtn">Add to cart</button>
									<?php 
									}
									else 
									{
									?>
									
									<?php
									}
								}
								else 
								{
								?>
								<button type="submit" onclick="addToCartC({{ $cpdata->id }});" class="homeshopbtn">Add to cart</button>
								<?php
								}
								?>

							<?php

							}

							else 
							{

							?>
							<button type="button" id="processtoshipping" style="background-color:#ddd" class="homeshopbtn">Out Of Stock</button>

							<?php 
							}
							?>

						</div>

					</li>

				@php
						}

					endforeach;

				else:

					echo 'No Product Found!';

				endif;

				endif;

				@endphp

						</ul>

					</div>

				</div>

                

				</div>

			@endforeach

		@endif

		<!-- 	SHOW CATEGORY PRODUCT SECTION -->

	

<?php /* ?>	
</div>
<div class="span12">



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

	





	<hr>

	</div>

</div>

<?php */ ?>

@endsection

@section('css')



@endsection

@section('js')

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>

function addToCart(ID){

	var pid = $("#cart_product_"+ID).val();

	$.ajax({      

		type: "GET",

		url: "{{ url('product-add-to-cart') }}",   

		data: {product_id : pid},       

		success:     

		function(data){

			var jsonData = $.parseJSON(data);

			//alert(jsonData.msg);

			swal("Success!", jsonData.msg , "success");

			//alert(jsonData.buttonEnable);

			$("#shprocnt").html('My Cart<strong>'+jsonData.status+' items</strong>');

			if(jsonData.buttonEnable=='1'){

				location.reload();  

			}

		}

	});

}

function addToCartC(ID){

	var pid = $("#ccart_product_"+ID).val();

	$.ajax({      

		type: "GET",

		url: "{{ url('product-add-to-cart') }}",   

		data: {product_id : pid},       

		success:     

		function(data){

			var jsonData = $.parseJSON(data);

			//alert(jsonData.msg);

			swal("Success!", jsonData.msg , "success");

			//alert(jsonData.buttonEnable);

			$("#shprocnt").html('My Cart<strong>'+jsonData.status+' items</strong>');

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



			//if( data.discount >0 ) {					

			let x = data.price;

			let y = data.max_price;

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
					var show_percentage = Math.floor(discount_percentage);
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

			

		   // } else {

		     //  $("#price_container_"+pid).html('₹'+(x.toLocaleString('us', {minimumFractionDigits: 2, maximumFractionDigits: 2})));

		  // }

			

			///////////////

			//$("#price_container_"+pid).html('₹'+(data.price));

			$("#unit_container_"+pid).html( data.weight_per_pkt + ' ' + data.weight_unit ); 

			$("#cart_product_"+pid).val(productId);

			////////////////////////

			

		}

	}); 

}

function get_cproduct_price(option,pid)

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

			//if( data.discount >0 ) {

				//$newPri=((100-(data.discount))*(data.price)/100); 

			//	$priceHtml='<small><del> ₹'.$data->price.'</del></small> ₹'.number_format($newPrice,2);

					//	else:

					//		$priceHtml='₹'.$data->price;

							

			let x = data.price;

			let y = data.max_price;

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
					var show_percentage = Math.floor(discount_percentage);
				}
			}


			$("#cprice_container_"+pid).html('<small id="prdmaxid2'+pid+'"><del> ₹'+y.toLocaleString('us', {minimumFractionDigits: 2, maximumFractionDigits: 2})+ '</del></small> ₹'+(x.toLocaleString('us', {minimumFractionDigits: 2, maximumFractionDigits: 2})));	


			if(show_percentage>0) 
			{ 
				$("#percent_container2"+pid).show(); 
				$("#prdmaxid2"+pid).show(); 
				$("#percent_container2"+pid).html(show_percentage+'% off'); 
			}
			else 
			{
				$("#percent_container2"+pid).hide(); 
				$("#prdmaxid2"+pid).hide(); 
			}

			

		    //} else {

		       //$("#cprice_container_"+pid).html('₹'+(x.toLocaleString('us', {minimumFractionDigits: 2, maximumFractionDigits: 2})));

		  // }

			//$("#cprice_container_"+pid).html('₹'+(data.price));

			$("#cunit_container_"+pid).html( data.weight_per_pkt + ' ' + data.weight_unit ); 

			$("#ccart_product_"+pid).val(productId);

			

		}

	}); 

}

</script>

@endsection







