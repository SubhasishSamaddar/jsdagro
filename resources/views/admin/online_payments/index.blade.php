@extends('layouts.admin')

@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Online Payments</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
		<li class="breadcrumb-item active">Online Payments</li>
	</ol>
	</div>
</div>
@endsection
@section('content')
	
	<div class="card">
		<div class="card-header">
			<div class="pull-left">

	        </div>
	        <div class="pull-right">
			
	        </div>
        </div>

		<div class="card-body">
			<table class="table table-bordered table-sm" id="datatable">
				<thead>
					<tr class="text-center">
						<th>Transaction Id</th>
						<th>User Email</th>
						<th>User Phone</th>
						<th>Product Info</th>
						<th>Amount</th>
					</tr>
				</thead>
				<tbody>
				@foreach ($online_payments as $details)
				<tr>
					<td>{{ $details->txn_id }}</td>
					<td>{{ $details->user_email }}</td>
					<td>{{ $details->user_phone }} </td>
					<td>{!! $details->product_info !!}</td>
					<td class="text-center">Rs. {{ $details->amount }}</td>
				</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>

@endsection
@section('css')
<link href="{{ asset('/css/bootstrap-toggle.min.css') }}" rel="stylesheet">
@endsection
@section('js')

@endsection
