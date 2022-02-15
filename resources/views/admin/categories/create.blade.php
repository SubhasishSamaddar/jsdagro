@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Category Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
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
	            <h4>Create New Category</h4>
	        </div>
        </div>

		<div class="card-body">
        <form method="post" action="{{ route('categories.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name"  value="{{ old('name') }}" required/>
                </div>
                <div class="form-group">
                    <label for="parent_id">Parent Category</label>
                    <select name="parent_id" id="parent_id" class="form-control" required>
                        <option value="0">Select Parent Category</option>
                        @foreach($categories as $value)
						<option value="{{ $value->id}}" {{ ($value->id==old('parent_id'))?'selected':''}}>{{ $value->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
					<div class="btn btn-default btn-file">
						<i class="fas fa-paperclip"></i> Category Image
						<input type="file" id="category_image" name="category_image">
                  	</div>
                </div>
				<div class="form-group">
					<div class="btn btn-default btn-file">
						<i class="fas fa-paperclip"></i> Category Banner Images
						<input type="file" id="category_banner_images" name="category_banner_images[]" multiple>
                  	</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="position_order">Position</label>
                    <input type="text" class="form-control" name="position_order" id="position_order"  value="{{ old('position_order') }}" required autocomplete="off"/>
                </div>
                <div class="form-group">
					<label for="status">Status</label><br/>
					<input name="status" id="status" data-id="0" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="Inactive" {{ (old('status')=='on')? 'checked' : '' }}>
				</div>
            </div>
            <div class="col-md-12">
                <label for="description">Description</label><br/>
				<textarea name="description" id="description" rows="10" cols="80" style="">{{ old('description') }}</textarea><br/>
			</div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <button type="submit" class="btn btn-primary">Save</button>
                <a class="btn btn-warning" href="{{ route('categories.index') }}"> Back</a>
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
