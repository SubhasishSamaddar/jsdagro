@extends('layouts.front')
@section('seo-header')
		<title>My Order Details</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
@endsection
<?php //echo '<pre>'; print_r($orderDetails); echo '</pre>';?>
@section('content')

<div class="container">

<div class="accountarea">
	<div id="sidebar">
			<ul class="sidenav nav-list">
				<li>
					<a href="{{ route('my-profile') }}">My Profile</a>
				</li>
				<li>
					<a href="{{ route('my-order') }}">My Order</a>
				</li>
				<li>
					<a href="{{ route('logout') }}">Logout</a>
				</li>
			</ul>
	</div>
	<div class="profilearea">
<div class="profileform">
		<!--<ul class="breadcrumb">
			<li><a href="{{ route('sitelink') }}">Home</a> <span class="divider">/</span></li>
			<li><a href="{{ route('my-order') }}">Order(s)</a> <span class="divider">/</span></li>
			<li class="active">Order Details</li>
		</ul>-->
		
			<h3>Your Order Details</h3>
			<div class="control-group">
				<label class="control-label" for="inputFname">Order Number : {{$details->order_number}}</label>
			</div>
			<div class="control-group">
				<label class="control-label" for="inputFname">Order Date : {{ date('d F Y', strtotime($details->order_date)) }}</label>
			</div>
			<div class="control-group">
				<label class="control-label" for="inputFname">Swadesh Hut : {{$details->hut_name}}</label>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="inputFname">Order Sataus : {{$details->order_status}}</label>
			</div>
			<div class="control-group">
				<label class="control-label" for="inputFname">Payment Mode : {{$details->payment_mode}}</label>
			</div>
			<div class="control-group">
				<label class="control-label" for="inputFname">Payment Status : {{$details->payment_status}}</label>
			</div>
			<h3>Shipping Details</h3>
			<div class="control-group">
				<label class="control-label" for="inputFname">Customer Name : {{$details->customer_name}}</label>
			</div>
			<div class="control-group">
				<label class="control-label" for="inputFname">Phone No : {{$details->shipping_phone}}</label>
				<label class="control-label" for="inputFname">Address : {{$details->shipping_address}}{{$details->shipping_address1}} , {{$details->city}}-{{$details->pin_code}} {{$details->state}}</label>
				<label class="control-label" for="inputFname">Nearway Landmark : {{$details->location}}</label>
			</div>
			
			<h3>Billing Details</h3>
			<div class="control-group">
				<label class="control-label" for="inputFname">Name : {{$details->billing_name}}</label>
				<label class="control-label" for="inputFname">Email : {{$details->billing_email}}</label>
				<label class="control-label" for="inputFname">Phone : {{$details->billing_phone}}</label>
			</div>
			<div class="control-group">
				<label class="control-label" for="inputFname">Address : {{$details->billing_street}}, {{$details->billing_city}}-{{$details->billing_pincode}} {{$details->billing_state}}</label>
			</div>
			
			<table class="table table-bordered table-condensed">
				<thead>
					<tr>
						<th>Product</th>
						<th>Unit price</th>
						<th>Qty </th>
						<th>Total</th>
					</tr>
				</thead>
				<tbody>
					@if( count($orderDetails) )
						@foreach( $orderDetails As $data )
					<tr>
						<td>{{ $data->prod_name }}</td>
						<td>र {{ $data->item_price }}</td>
						<td>{{ $data->quantity }}</td>
						<td>र {{ number_format(($data->item_price*$data->quantity), 2) }}</td>
					</tr>
						@endforeach
					@endif
					<tr>
					  <td colspan="3" class="alignR">Total products:	{{ count($orderDetails) }}</td>
					  <td><?php if(isset($data->prod_name) && $data->prod_name!='') { ?> र {{ number_format($details->total_amount, 2) }} <?php } ?></td>
					</tr>
				</tbody>
            </table>		
			
			
		</div>
		
			
			
	</div>
</div>
</div>
@endsection
@section('css')
@endsection
@section('js')
<!--script>
  $(function() {
    $('.toggle-class').change(function() {
        var status = $(this).prop('checked') == true ? 'Active' : 'Inactive';
        var id = $(this).data('id');

        $.ajax({
            type: "GET",
            dataType: "json",
			url: "{{ url('cpanel/package_location/changestatus') }}",
            data: {'status': status, 'id': id},
            success: function(data){
              console.log(data.success)
            }
        });
    })
  })
</script-->
@endsection
