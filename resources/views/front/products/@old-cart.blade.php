@extends('layouts.front')
<?php //echo Cart::getContent()->count();
//echo '<pre>'; print_r($productDetails); echo '</pre>';?>
@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 my-3">
            <div class="pull-right">
                <div class="btn-group">
                   Product Listing
                </div>
            </div>
        </div>
    </div>


	<table class="table table-bordered">
		<thead>
			<tr>
				<th>Product Name</th>
				<th>Quantity</th>
				<th>Item Price</th>
				<th>Total Price</th>
				<th></th>
			</tr>
		</thead>
		<tbody id="carttable">
			@if( !$productDetails->isEmpty() )
				<?php $totalPrice=0;?>
				@foreach( $productDetails as $pdKey=>$data )
					<?php $productIdName=explode('%',$data->name);?>
					<tr id="{{ $pdKey }}">
						<td>{{ $productIdName['1'] }}</td>
						<td><input type="number"  name="quantity" id="qun{{ $pdKey }}" value="{{ $data->quantity }}" onchange="updateCart('{{ $pdKey }}')" minvalue="1"></td>
						<td>{{ $data->price }}</td>
						<td>{{ $data->price*$data->quantity }}</td>
						<td>
							<a onclick="removeFromCart('{{ $pdKey }}');" class="btn btn-lg btn-outline-primary text-uppercase"> <i class="fas fa-shopping-cart"></i> Remove </a>
						</td>
					</tr>
					<?php $totalPrice=$totalPrice+($data->price*$data->quantity);?>
				@endforeach
					<tr id="totalprice">
						<td colspan="4"> Total Price : </td>
						<td>{{ $totalPrice }}</td>
					</tr>
			@else
				<tr>
					<td colspan="5">Cart Is Empty!</td>
				</tr>
			@endif
		</tbody>
	</table>
			<?php /* if( !$productDetails->isEmpty() && $totalPrice>0 && isset(Auth::user()->id) )*/?>
			@if( !$productDetails->isEmpty() && $totalPrice>0 )
				<form method="post" action="{{ route('shipping') }}" id="checkoutform">
					@csrf  
						<input type="hidden" name="total_amount" value="{{ $totalPrice }}">

					<div class="row">
						<!--div class="col-md-6">
							<label for="description">Order Date</label>
							<input type="date" name="order_date" class="form-control" required>
						</div-->
						<!--div class="col-md-6">
							<label for="description">Swadesh Hut
								<a data-toggle="modal" href="#myModal"><span id="cartstoreid">

									@if(Session::has('swadesh_hut_name'))
										Store Name : {{ Session::get('swadesh_hut_name') }}
									@else
										{{ "Select Store" }}
									@endif
									</span>
								</a>
							</label>

						</div-->	
							<!--select name="swadesh_hut_id" id="swadesh_hut_id" class="form-control" required>
								<option value="">-- Select Swadesh Hut --</option>
								@if( !$swadeshHutDetails->isEmpty() )
									@foreach( $swadeshHutDetails as $sdata )
										<option value="{{ $sdata->id }}">{{ $sdata->location_name }}</option>
									@endforeach
								@endif
							</select-->
						
					</div>
					<div class="row">

						@if(Session::has('swadesh_pin_code'))
							<?php $disabled='';?>
						@else
							<?php $disabled='disabled';?>
						@endif
		<div class="col-md-6"><br>
							<button id="processtocheckout" class="btn btn-success" {{ $disabled }}>Process to Checkout</a>
						</div>
					</div>
				</form>
			@endif

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
@section('css')
@endsection
@section('js')

@endsection



