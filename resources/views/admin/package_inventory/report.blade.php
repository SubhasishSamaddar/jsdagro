@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Package Inventory Report</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('package_inventory.index') }}">Package Inventory</a></li>
		<li class="breadcrumb-item active">Report</li>
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
        </div>

		<div class="card-body">
        <form method="post" action="{{ route('pidatewiseexport') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-3">
                <label for="description">Start Date</label><br/>
                <input type="date" class="form-control" name="start_date">
            </div>
            <div class="col-md-3">
                <label for="description">End Date</label><br/>
                <input type="date" class="form-control" name="end_date">
            </div>
            <div class="col-md-3">
                <label for="description">In/Out Type</label><br/>
                <select class="form-control" name="export_type" id="export_type">
                    <option value="">Select Export Type</option>
                    <option value="in">Inventory In</option>
                    <option value="out">Inventory Out</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="description">&nbsp;&nbsp;</label><br/>
                <button type="submit" class="btn btn-primary">Export Data</button>
            </div>

		</div>
        </form>
	</div>
@endsection

