@extends('layouts.front')
<?php //echo Cart::getContent()->count();
//echo '<pre>'; print_r($productDetails); echo '</pre>';
//echo '<pre>';print_r(Auth::user());//die;	
?>
@section('seo-header')
		<title>Cart Page</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
@endsection
@section('content')
<div class="container">
<div class="row">
<div class="breadcrumb"><a href="{{ url('/') }}/home">Home</a>  >  <a href="{{ url('/') }}/cart">Cart</a></div>
	<div class="cartbox">
			
			<h3>Cart <!--small class="pull-right"> 2 Items are in the cart </small--></h3>
			@if( !$productDetails->isEmpty() )
            <div class="tablecartdata">
			<table class="table table-bordered table-condensed">
				<thead>
					<tr>
						<th style="width:10%;">Image</th>
						<th style="width:58%;">Product</th>
						<!--th>Ref.</th>
						<th>Avail.</th-->
						<th>Unit price</th>
						<th style="width: 12%;">Qty </th>
						<th>Total</th>
					</tr>
				</thead>
				<tbody>
					@php $totalPrice=0; @endphp
					@foreach( $productDetails as $pdKey=>$data )
					@php $productIdName=explode('%',$data->name);@endphp
					<?php 
					$get_product_details = DB::table('products')->where('id',$productIdName['0'])->first();
				//	echo "<pre>";
				//	print_r($data);
					?>
					<tr id="{{ $productIdName['0'] }}">
						<td><img width="100" src="{{ asset('/storage/'.$data->attributes['product_image']) }}" alt=""></td>
						<td>{{ $productIdName['1'] }} <br> {{ $data->attributes['weight_per_pkt'] }} {{ $data->attributes['weight_unit'] }}</td>
						<!--td> - </td>
						<td><span class="shopBtn"><span class="icon-ok"></span></span> </td-->
						<td>र {{ number_format($data->price, 2) }}</td>
						<td>
						@php
							if( $data->attributes['product_availability']>$data->quantity ):
								$disabled="";
							else:
								$disabled="disabled";
							endif;
						@endphp

							
						
							<input type="number" class="span1" name="quantity" id="qun{{ $pdKey }}" value="{{ $data->quantity }}" onchange="updateCart('{{ $pdKey }}')" min="1" {{$disabled}} style="width: 58px; padding: 6px 8px!important;">

							
							<!--input class="span1" style="max-width:34px" placeholder="1" id="appendedInputButtons" size="16" type="text" value="2"-->
							<div class="input-append">
								<!--button class="btn btn-mini" type="button">-</button><button class="btn btn-mini" type="button"> + </button>
								<button class="btn btn-mini btn-danger" type="button"><span class="icon-remove"></span></button-->
								<a onclick="removeFromCart('{{ $pdKey }}');" class="btn btn-lg text-uppercase"><button class="btn btn-mini btn-danger" type="button"><span class="icon-remove"></span></button></a>
							</div>
							
						</td>
						<td>र {{ number_format( ($data->price*$data->quantity), 2) }}
						</td>
					</tr>
					
				@php $totalPrice=$totalPrice+($data->price*$data->quantity); @endphp
					@endforeach
					@if(Session::has('total_price'))
						@php Session::forget('total_price'); @endphp
					@endif
					@php Session::put('total_price', $totalPrice); @endphp
					<tr>
                      <td colspan="4" class="alignR" style="text-align: right;"><strong>Total Price: &nbsp;</strong></td>
					  <td class="label-primary">र {{ number_format($totalPrice, 2) }}</td>
					</tr>
				</tbody>
            </table>
			
      </div>
			@endif
			<div class="cartbuttons">
				<a href="{{ route('sitelink') }}" class="shopBtn btn-large" style="width: auto;"><span class="icon-arrow-left"></span> Continue Shopping </a>
				@if( !$productDetails->isEmpty() && $totalPrice>0 )
				<form method="post" action="{{ route('shipping') }}" id="checkoutform">
					@csrf
					<input type="hidden" name="total_amount" value="{{ $totalPrice }}">
					<!--a href="login.html" class="shopBtn btn-large pull-right">Next <span class="icon-arrow-right"></span></a-->
					@if(Cookie::has('swadesh_pin_code'))
						<?php $disabled='';?>
					@else
						<?php $disabled='disabled';?>
					@endif
					<button id="processtocheckout" class="shopBtn btn-large pull-right">Process to Checkout</a>
				</form>
				@endif
      		</div>
	  <p style="color:red; margin-left:10px;">* Please complete your purchase before <?php echo date("h:i a", strtotime($swadeshHutDetails[0]->close_time)); ?> to get same day delivery</p>
	</div>
</div>
</div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
function removeFromCart(ID){
	$.ajax({
		type: "GET",
		url: "{{ url('product-remove-from-cart') }}",
		data: {item_session : ID},
		success:
		function(data){
			var jsonData = $.parseJSON(data);
			//alert(jsonData.msg);
			swal("Success!", jsonData.msg , "success");
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
				//alert(jsonData.msg);
				swal("Success!", jsonData.msg , "success");
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
@endsection

@section('css')
@endsection
@section('js')

@endsection



