@extends('layouts.front')

@section('seo-header')
	<title>JSD Agro | Order Confirm</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
@endsection

@section('content')
<div class="container">
<div class="page_success">
  <img src="{{ asset('front-assets/images/success.png') }}" alt="">
  <h3>Your Order has been successful.</h3>
	<?php //print_r($get_order_details);exit;  ?>
  <h4>Your order number #<a href="{{ url('order-details/'.$data['order_id']) }}" target="_blank"><ins>{{ $data['order_number'] }}</ins></a> will get delivered within {{ date('jS F, Y',strtotime($shipping_date_time)) }} </h4>



  <p>Check your Order status in <a href="{{ url('my-order') }}" target="_blank"><ins>My Orders</ins></a> section.</p>


		
		</div>
	</div>
</div>
@endsection


<script>
function addToCart(ID){
	$.ajax({      
		type: "GET",
		url: "{{ url('product-add-to-cart') }}",   
		data: {product_id : ID},       
		success:   
		function(data){
			var jsonData = $.parseJSON(data);
			alert(jsonData.msg);
			$("#shprocnt").html(jsonData.status+' Item(s)');
			/*
			var jsonData = $.parseJSON(data);
			if(jsonData.count>'0'){
				$("#cart_list").html(jsonData.data);
				$("#product_cart").html(jsonData.count);
			}else{

			}
			*/
		}
	});
}
</script>
@section('css')
@endsection
@section('js')
@endsection
