@extends('layouts.admin')

@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>User Contacts</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
		<li class="breadcrumb-item active">User Contacts</li>
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
						<th>User Name</th>
						<th>User Email</th>
						<th>User Phone</th>
						<th>Comment</th>
					</tr>
				</thead>
				<tbody>
				@foreach ($user_contacts as $details)
				<tr>
					<td>{{ $details->user_name }}</td>
					<td>{{ $details->user_email }}</td>
					<td>{{ $details->user_phone_no }} </td>
					<td class="text-center">{{ $details->user_comment }}</td>
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
