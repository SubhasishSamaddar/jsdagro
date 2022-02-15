@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Package Inventory Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('package_inventory.index') }}">Package Inventory</a></li>
		<li class="breadcrumb-item active">Import</li>
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
	            <h4>Package Inventory Import</h4>
	        </div>
        </div>

		<div class="card-body">
        <form method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
			<div class="col-md-6">
                <label for="category_id">Package Location</label>
                <select name="package_location_id" id="package_location_id" class="form-control" required>
                    <option>Select Package Location</option>
                    @foreach($package_location as $value)
						@php
						if( isset(Auth::user()->package_location_id) && Auth::user()->package_location_id==$value->id ):
							$selected='selected="selected"';
						elseif( $value->id==old('package_location_id') ):
							$selected='selected="selected"';
						else:
							$selected=""; 
						endif;
						@endphp;
						<option value="{{ $value->id}}" {{ $selected }}>{{ $value->location_name}}</option>
                    @endforeach
                </select>
            </div>
			<br clear="all">
			<br clear="all">
			<div class="col-md-12">
                <div class="form-group">
					<div class="btn btn-default btn-file">
						<i class="fas fa-paperclip"></i> Upload File
						<input type="file" id="inventory_file" name="inventory_file">
                  	</div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <button type="submit" class="btn btn-primary">Save</button>
                <a class="btn btn-warning" href="{{ route('products.index') }}"> Back</a>
            </div>

            <a href="{{ url('/').'/cpanel/package_inventory/downloadsamplecsv' }}"><span style="color: red;">*</span>Download Sample Csv</a>
		</div>
        </form>
	</div>
@endsection
@section('css')
<link href="{{ asset('/css/bootstrap-toggle.min.css') }}" rel="stylesheet">
@endsection
@section('js')
<script src="{{ asset('/js/bootstrap-toggle.min.js') }}"></script>
<script src="<?php echo url('/') ?>/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
<script src="<?php echo url('/') ?>/vendor/unisharp/laravel-ckeditor/adapters/jquery.js"></script>
<script>
    CKEDITOR.replace( 'description' );
    CKEDITOR.replace( 'specification' );
</script>
@endsection
