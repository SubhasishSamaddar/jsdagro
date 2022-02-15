@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Order Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('order.index') }}">Order Management</a></li>
		<li class="breadcrumb-item active">Edit</li>
	</ol>
	</div>
</div>
@endsection
@section('content')
	@if ($message = Session::get('success'))
	<div class="alert alert-success">
		<ul class="margin-bottom-none padding-left-lg">
			<li>{{ $message }}</li>
		</ul>
	</div>
	@endif
    @if ($errors->any())
	<div class="alert alert-danger">
		<strong>Whoops!</strong> There were some problems with your input.<br><br>
		<ul>
		@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
		</ul>
	</div>
	@endif
	<div class="card">
		<div class="card-header">
			<div class="pull-left">
	            <h4>Edit Order</h4>
	        </div>
        </div>

		<div class="card-body">
        <form method="post" action="{{ route('order.update', $details->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="prod_name">Order Number</label>
                    <input type="text" class="form-control" name="order_number" id="order_number"  value="{{$details->order_number}}" disabled/>
                </div>
            </div>
            <div class="col-md-6">
				<div class="form-group">
					<label for="prod_name">Order Date</label>
					<input type="text" class="form-control" name="order_date" id="order_date"  value="{{ date('d F Y-H:i A', strtotime($details->shipping_date_time)) }}" disabled/>
				</div>
			</div>
			
			<!-- <div class="col-md-6">
                <div class="form-group">
                    <label for="category_id">Customer Details</label>
                    <select name="user_id" id="user_id" class="form-control" disabled>
                        @foreach($userDetails as $value)
						<option value="{{ $value->id}}" {{ ($value->id==$details->user_id)?'selected':''}}>{{ $value->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div> -->

            <div class="col-md-6">
                <div class="form-group">
                    <label for="category_id">Customer Name</label>
                    <input type="text" class="form-control" name="order_date" id="order_date"  value="{{$details->billing_name}}" disabled/>
                </div>
            </div>

            <div class="col-md-6">
				<div class="form-group">
                    <label for="category_id">Store Name</label>
                    <select name="swadesh_hut_id" id="swadesh_hut_id" class="form-control" disabled>
                        @foreach($hutDetails as $value)
						<option value="{{ $value->id}}" {{ ($value->id==$details->swadesh_hut_id)?'selected':''}}>{{ $value->location_name}}</option>
                        @endforeach
                    </select>
                </div>
			</div>
		</div>	
		
			
			@if( !$orderDetails->isEmpty() )
			<div class="row">	
				<div class="col-md-12">
					<div class="form-group">
						<label for="category_id">Order Details</label>				
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Product Name</th>
						<th>Quantity</th>
						<!-- <th>Item Price</th>
						<th>CGST</th>
						<th>SGST</th>
						<th>IGST</th> -->
						<th>Total Price</th>
					</tr>
				</thead>
				<tbody>
					@foreach( $orderDetails as $data )
						<tr>
							<td>{{ $data->product_namee }}</td>
							<td>{{ $data->quantity }}</td>
							<!-- <td>{{ $data->item_price }}</td>
							<td>{{ $data->cgst }}</td>
							<td>{{ $data->sgst }}</td>
							<td>{{ $data->igst }}</td> -->
							<td>{{ $data->item_total }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
					</div>
				</div>
			</div>
			@endif
			
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="prod_name">Order Status</label>
					<select name="order_status" id="order_status" class="form-control">
                        <option value="Ordered" {{ ($details->order_status=='Ordered')?'selected':''}}>Ordered</option>
                        <option value="Packed" {{ ($details->order_status=='Packed')?'selected':''}}>Packed</option>
                        <option value="Delivered" {{ ($details->order_status=='Delivered')?'selected':''}}>Delivered</option>
                    </select>
				</div>
			</div>
			
			<div class="col-md-3">
				<div class="form-group">
					<label for="prod_name">Payment Method</label>
					<select name="payment_mode" id="payment_mode" class="form-control">
                        <option value="Online" {{ ($details->payment_mode=='Online')?'selected':''}}>Online</option>
                        <option value="COD" {{ ($details->payment_mode=='COD')?'selected':''}}>COD</option>
                    </select>
				</div>
			</div>
			
			<div class="col-md-3">
				<div class="form-group">
					<label for="prod_name">Payment Status</label>
					<select name="payment_status" id="payment_status" class="form-control">
                        <option value="Paid" {{ ($details->payment_status=='Paid')?'selected':''}}>Paid</option>
                        <option value="Unpaid" {{ ($details->payment_status=='Unpaid')?'selected':''}}>Unpaid</option>
                    </select>
				</div>
			</div>
			 
            <div class="col-xs-12 col-sm-12 col-md-12">
				@php 
					if( $details->order_status=='Delivered' && $details->payment_status=='Paid' ):
						$disabled='disabled';
					else:
						$disabled='';
					endif;
				@endphp
                <button type="submit" class="btn btn-primary" {{ $disabled }}>Save</button>
                <a class="btn btn-warning" href="{{ route('order.index') }}"> Back</a>
            </div>
		</div>
        </form>
	</div>
@endsection
@section('css')
<link href="{{ asset('/css/bootstrap-toggle.min.css') }}" rel="stylesheet">
@endsection
@section('js')
<script src="{{ asset('/js/bootstrap-toggle.min.js') }}"></script>
@endsection
