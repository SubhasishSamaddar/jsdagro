@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Navigations Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('navigations.index') }}">Navigations</a></li>
		<li class="breadcrumb-item active">Create</li>
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
	            <h4>Create New Navigation</h4> 
	        </div> 
        </div>
		
		<div class="card-body">
        <form method="post" action="{{ route('navigations.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row"> 
            <div class="col-md-6">
				<div class="form-group">
					<label for="title">Title</label>
					<input type="text" class="form-control" name="title" id="title" required value="{{ old('title') }}"/>
				</div>
				<div class="form-group">
					<label for="link_url">URL</label>
					<input type="text" class="form-control" name="link_url" id="link_url" required value="{{ old('link_url') }}"/>
				</div>
				
				<div class="form-group">
					<label for="position_block">Nav Position</label>
					<input type="text" class="form-control" name="position_block" id="position_block" required value="{{ old('position_block') }}"/>
				</div>
				<div class="form-group">
					<label for="position_order">Position Order</label>
					<input type="text" class="form-control" name="position_order" id="position_order" required value="{{ old('position_order') }}"/>
				</div>
            </div>    
            <div class="col-md-6">
				
				<div class="form-group">
					<label for="target">Target Window</label><br/>
					<input name="target" id="target" data-id="0" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Parent" data-off="New" {{ (old('target')=='on')? 'checked' : '' }}>
				</div>
            </div> 
            <div class="col-xs-12 col-sm-12 col-md-12">
                <button type="submit" class="btn btn-primary">Save</button>
                <a class="btn btn-warning" href="{{ route('navigations.index') }}"> Back</a>
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