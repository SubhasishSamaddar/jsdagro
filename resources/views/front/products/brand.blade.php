@extends('layouts.front')

@section('seo-header')
		<title>Brand Page - {{ $branddetails->brand_name }}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
@endsection		
@section('content') 
<div class="product-listing-outer">
	<div class="product-listing-head">
		<div class="product-cat-title">{{ $branddetails->brand_name }}</div>
        <div  >{!! $branddetails->brand_content!!}</div>
	</div>
	<div class="product-list-view">
    <div class="featured_products"> 
		<div class="productsrow">			
				<ul class="prod">
                  @if( !$products->isEmpty() )
					@php $i=1; @endphp
					@foreach( $products as $newData )
						@php
							if( $newData->discount && $newData->discount >0 ):
								$newPrice=((100-$newData->discount)*$newData->price/100);
								$priceHtml='<small><del> र'.$newData->price.'</del></small> र'.number_format($newPrice,2);
							else:
								$priceHtml='र'.$newData->price;
							endif;
						@endphp
						

						<!--- ADD TO CART FUNCTIONALITY-->
						@php
						$buttonDisable=0;
						if(Cookie::has('swadesh_hut_id')):
						$swadesh_hut_id=Cookie::get('swadesh_hut_id');
						else:
							$swadesh_hut_id=0;
						endif;
						$catProduct=Helper::get_product_by_categorys($newData->id, $swadesh_hut_id);
						$optionProduct=Helper::getProductOptions($newData->similar_product, $swadesh_hut_id);	
						if( !$catProduct->isEmpty() ):
							foreach( $catProduct as $cpdata ):
								if( $cpdata->discount && $cpdata->discount >0 ):
									$newPrice=((100-$cpdata->discount)*$cpdata->price/100);
									$priceHtml='<small><del> र'.$cpdata->price.'</del></small> र'.number_format($newPrice,2);
								else:
									$priceHtml='र'.$cpdata->price;
								endif;
						
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
								
								<figure>
									<img src="{{ asset('/storage/'.$newData->product_image) }}" alt=""></figure>
								<div class="caption">
                                  <h5><a  href="{{ route('products',$newData->id) }}">{{ $newData->prod_name }}</a></h5><h3><center id="price_container_{{ $newData->id }}">{!! $priceHtml !!}</center></h3>
								  <h3 id="unit_container_{{ $newData->id }}">{{ $newData->weight_per_pkt }} {{ $newData->weight_unit }}</h3>
								   
									  <?php  foreach($optionProduct as $option){
										  //echo $option->price." ".$option->weight_per_pkt." ".$option->weight_unit;
									  } ?>
								   <?php 
			if(count($optionProduct)>1)
			{
			?>
			<select class="form-control" id="product_weight_price" onchange="get_product_price(this,{{ $newData->id }})" style="width:100px;margin-left:2px;">
				<?php 
				if(count($optionProduct)>0)
				{
					foreach($optionProduct as $option)
					{
						$selected = ($option->id==$newData->id)?" selected='selected' ":'';
						echo '<option value="'.$option->id.'" '.$selected.'><strong>'.intval($option->weight_per_pkt).'&nbsp;'.$option->weight_unit.'</strong></option>';
					}
				}
				?>	
			</select>
			<?php 
			} 
			?>
								   
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
			 
					
					
								
				
	</div>
</div> 
<!-- Hide If Empty -->		


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
			$("#price_container_"+pid).html('₹'+(data.price));
			$("#unit_container_"+pid).html( data.weight_per_pkt + ' ' + data.weight_unit ); 
			$("#cart_product_"+pid).val(productId);
			
		}
	}); 
}
</script>
@endsection 



