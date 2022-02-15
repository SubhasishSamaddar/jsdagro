@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Brand Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
	<li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item"><a href="/brands">Brands</a></li>
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
                <h5>Edit Brand</h5>
	        </div> 
        </div>
		
		<div class="card-body">
        <form method="post" action="{{ route('brands.update', $brand->id) }}" enctype="multipart/form-data">
        @csrf
		@method('PATCH')
        <div class="row"> 
			<div class="col-md-6"> 
				<div class="form-group">
					<label for="title">Title</label>
					<input type="text" class="form-control" name="brand_name" id="brand_name" required value="{{$brand->brand_name}}"/>
				</div>
				 
            </div>  
			<div class="col-md-12">
				<textarea name="brand_content" id="brand_content" rows="10" cols="80" style="visibility: hidden; display: none;">{{$brand->brand_content}}</textarea><br/>
			</div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <button type="submit" class="btn btn-primary">Update</button>
                <a class="btn btn-warning" href="{{ route('brands.index') }}"> Back</a>
            </div>
		</div>
        </form>
	</div> 
@endsection
@section('css')
<link href="/css/bootstrap-toggle.min.css" rel="stylesheet">
@endsection
@section('js')
<script src="/js/bootstrap-toggle.min.js"></script>
<script>
    CKEDITOR.replace( 'content', {
        filebrowserUploadUrl: "{{route('upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
</script>
@endsection