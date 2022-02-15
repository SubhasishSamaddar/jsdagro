@extends('layouts.front')
<?php //echo '<pre>'; //print_r($categorydetails);
//print_r(Cart::getContent());?> 
@section('seo-header')
		<title>Product Listing Page</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
@endsection		
@section('content')
<div class="product-listing-outer">
	<div class="product-listing-head">
		<div class="product-cat-title">Featured Products</div>
	</div>
	<div class="product-list-view">
			<div class="product-boxes"> 
			<?php
			$buttonDisable='';
			if(Cookie::has('swadesh_hut_id')):
				$swadesh_hut_id=Cookie::get('swadesh_hut_id');
			else:
				$swadesh_hut_id=0;
			endif;
			?>
			@php
				if( !$featuredroducts->isEmpty() ):
			@endphp
				 
			@php
			
					$i=1;
					foreach($featuredroducts as $data):
						if( $data->discount && $data->discount >0 ):
							$newPrice=((100-$data->discount)*$data->price/100); 
							$priceHtml='<small><del> ₹'.$data->price.'</del></small> ₹'.number_format($newPrice,2);
						else:
							$priceHtml='₹'.$data->price;
						endif;
						$optionProduct=Helper::getProductOptions($data->similar_product, $swadesh_hut_id);
			@endphp
			<?php 		//$buttonDisable=1;
						if( count(Cart::getContent())>0 ):
							foreach( Cart::getContent() as $cartData ):
								$productIdName=explode('%',$cartData->name);
								if( $productIdName[0]==$data->id ):
									//echo '===>'.$cartData->attributes['product_availability'].'===>'.$cartData->quantity;
									if( $cartData->attributes['product_availability']>$cartData->quantity):
										$buttonDisable=0; 
									else:
										$buttonDisable=1;
									endif;
								elseif( ($data->available_qty-$data->ordered_qty)>0 ):
									//echo '====';die;
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
									<div class="prodPri" id="price_container_{{ $data->id }}">{!! $priceHtml !!}</div>
									<div class="prodPri" id="unit_container_{{ $data->id }}">{{ $data->weight_per_pkt }} {{ $data->weight_unit }}</div>
									<?php 
									if(count($optionProduct)>1)
									{
									?>
									<select class="form-control" id="product_weight_price" onchange="get_product_price(this,{{ $data->id }})" style="width:100px;margin-left:2px;">
										<?php 
										if(count($optionProduct)>0)
										{
											foreach($optionProduct as $option)
											{
												$selected = ($option->id==$data->id)?" selected='selected' ":'';
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
									<input type="hidden" name="cart_product_{{ $data->id }}" id="cart_product_{{ $data->id }}" value="{{ $data->id }}"/>
									<button type="submit" id="processtoshipping" onclick="addToCart({{ $data->id }});" class="shopBtn ">Add to cart</button>
									<?php
									/*}*/
									?>
									<!--<div class="prodBtn"><span>Shop Now</span><?php echo $buttonDisable;?></div>-->
								</div>  
						</div><!-- Product Loop -->
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
					
					
								
				
	</div>
</div><!-- .product-listing-outer -->	
<!-- Hide If Empty -->	
<div class="subCategoryOuter">
	<div class="subCat-Listing">  
	<!--		@php
				if( !$categories->isEmpty() ):
			@endphp
				
			@php
					$i=1;
					foreach($categories as $data):
						
			@endphp
						<div class="subCat-box">
							<a class="subCat-Wrapper" href="{{ route('product-category', $data->id) }}">
								<div class="subCat-img">
									<img src="{{ asset('/storage/'.$data->category_image)}}" alt="">
								</div>
								<div class="subCat-title">{{ $data->name }}</div>
							</a>
						</div>
			@php
			
					$i++;
					endforeach;
				endif;
			@endphp-->
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



