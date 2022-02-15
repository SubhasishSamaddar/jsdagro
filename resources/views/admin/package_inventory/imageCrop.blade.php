@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Image Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('package_inventory.index') }}">Package Inventory</a></li>
		<li class="breadcrumb-item active">Image Management</li>
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
	            <h4>Image Management</h4>
	        </div>
        </div>

		<div class="card-body">
        <form method="post" action="{{ url('cpanel/image-crop') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
			<div class="col-md-12">
                <div class="form-group">
					<div class="btn btn-default btn-file">
						<i class="fas fa-paperclip"></i> Upload Multiple Images 
						<input type="file" id="images" name="images[]" multiple>
                  	</div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <button type="submit" class="btn btn-primary">Save</button>
                <a class="btn btn-warning" href="{{ route('package_inventory.index') }}"> Back</a>
            </div>

            <span style="color: red;">*select multiple images pressing ctrl key</span>
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
