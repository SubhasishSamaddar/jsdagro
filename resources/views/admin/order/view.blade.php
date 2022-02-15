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

	

	<div class="card">

		<div class="card-header">

			<div class="pull-left">

	            <h4>View Order</h4>

	        </div>

        </div>

<?php //print_r($details);die;?>

		<div class="card-body">

        <form method="post" action="" enctype="multipart/form-data">

        @csrf

        @method('PATCH')

        <div class="row">

            <div class="col-md-6">

                <div class="form-group">

                    <h5>Order Number : {{$details->order_number}}</h5>

                    <p><strong>Order Date:</strong> {{ date('d F Y- H:i A', strtotime($details->shipping_date_time)) }}<br/><strong>Store Name:</strong> {{$details->hut_name}}<br/>

                    <strong>Order Status:</strong> <?php

							if($details->order_status=='Packed'):

								$details->order_status='<span class="badge badge-primary">Packed</span>';

							elseif($details->order_status=='Ordered'): 	

								$details->order_status='<span class="badge badge-success">Ordered</span>';

							elseif($details->order_status=='Delivered'): 	

								$details->order_status='<span class="badge badge-warning">Delivered</span>';

							else:

								$details->order_status='<span class="badge badge-secondary">NA</span>';

							endif;

							echo $details->order_status;

						?>

						<br/><strong>Payment Method:</strong> {{ $details->payment_mode}}<br/><strong>Payment Status:</strong> {{ $details->payment_status}}

					</p>

                    <h5>Customer Name : {{$details->billing_name }}</h5>

                    

                </div>

            </div>

         </div>   

			

			<!--<div class="col-md-6">-->

   <!--             <div class="form-group">-->

   <!--                 <label for="category_id">Customer Name : {{$details->customer_name}}</label>-->

   <!--             </div>-->

   <!--         </div>-->

            

			

		<div class="row">	

			<div class="col-md-6">

                <div class="form-group">

                    <h5>Billing Details</h5>

                    <p>Customer Name : {{$details->billing_name }}<br/>Address : {{$details->billing_street }} {{$details->billing_city }}-{{$details->billing_pincode }}<br/>Email : {{$details->billing_email }}<br>Phone : {{$details->billing_phone}}<br>

                    

                    <!--<label for="category_id">{{ $details->billing_state }}</label>-->

                </div>

            </div>

            <div class="col-md-6">

				<div class="form-group">

                    <h5>Shipping Details</h5>

                    <p>Customer Name : {{$details->billing_name }}<br/>Address : {{ $details->shipping_address }} {{ $details->shipping_address1 }} {{ $details->city }} {{ $details->pin_code }} {{ $details->state }}<br/>Phone : {{ $details->shipping_phone }}</p>

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

						<th>Item Price</th>

						<th>CGST</th>

						<th>SGST</th>

						<!--<th>IGST</th> -->

						<th>Total Price</th>

					</tr>

				</thead>

				<tbody>

					@foreach( $orderDetails as $data )
							@php 
							$get_product_sdetails = Helper::get_products_by_id($data->pid);  
							$unitp = (100*$data->item_total)/(100+$get_product_sdetails->cgst+$get_product_sdetails->sgst);
							@endphp
						<tr>

							<td>{{ $data->product_namee }}</td>

							<td>{{ $data->quantity }}</td>

							<td>र {{ number_format($unitp,2) }}</td>

							<td>र {{ number_format($get_product_sdetails->cgst*number_format($unitp,2)/100,2) }}</td>

							<td>र {{ number_format($get_product_sdetails->sgst*number_format($unitp,2)/100,2) }}</td>

							<!-- <td>{{ $data->igst }}</td> -->

							<td>र {{ $data->item_total }}</td>

						</tr>

					@endforeach

						<tr>

							<td colspan="5">Total</td>

							<td>र {{ $details->total_amount  }}</td>

						</tr>

				</tbody>

			</table>

					</div>

				</div>

			</div>

			@endif

			

		<div class="row">

			

			 

            <div class="col-xs-12 col-sm-12 col-md-12">

                

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

