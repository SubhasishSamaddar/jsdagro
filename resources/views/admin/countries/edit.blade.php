@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Countries Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('countries.index') }}">Countries</a></li>
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
                <h5>Edit Country</h5>
	        </div> 
        </div>
		
		<div class="card-body">
        <form method="post" action="{{ route('countries.update', $country->id) }}" enctype="multipart/form-data">
        @csrf
		@method('PATCH')
        <div class="row"> 
			<div class="col-md-6"> 
				<div class="form-group">
					<label for="country_name">Country Name</label>
					<input type="text" class="form-control" name="country_name" id="country_name" required value="{{ $country->country_name }}"/>
				</div>
				<div class="form-group">
					<label for="country_code2">ISO 2 Code</label>
					<input type="text" class="form-control" name="country_code2" id="country_code2" required value="{{ $country->country_code2 }}"/>
				</div>
				<div class="form-group">
					<label for="country_code3">ISO 3 Code</label>
					<input type="text" class="form-control" name="country_code3" id="country_code3" required value="{{ $country->country_code3 }}"/>
				</div> 
            </div>    
            <div class="col-md-6">
				<div class="form-group">
					<label for="position_order">Position Order</label>
					<input type="text" class="form-control" name="position_order" id="position_order" required value="{{ $country->position_order }}"/>
				</div>
				<div class="form-group">
					<label for="status">Status</label><br/>
					<input name="status" id="status" data-id="0" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="Inactive" {{ ($country->status=='Active')? 'checked' : '' }}>
				</div>
            </div>
			 
            <div class="col-xs-12 col-sm-12 col-md-12">
                <button type="submit" class="btn btn-primary">Update</button>
                <a class="btn btn-warning" href="{{ route('countries.index') }}"> Back</a>
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