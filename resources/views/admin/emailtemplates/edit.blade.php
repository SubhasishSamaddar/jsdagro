@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Email Templates Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item"><a href="/email_templates">Email Templates</a></li>
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
                <h5>Edit Email Template</h5>
	        </div> 
        </div>
		
		<div class="card-body">
        <form method="post" action="{{ route('email_templates.update', $emailtemplate->id) }}" enctype="multipart/form-data">
        @csrf
		@method('PATCH')
        <div class="row"> 
			<div class="col-md-6"> 
				<div class="form-group">
					<label for="title">Title</label>
					<input type="text" class="form-control" name="title" id="title" required value="{{$emailtemplate->title}}"/>
				</div>
				<div class="form-group">
					<label for="aliases">Aliase</label>
					<input type="text" class="form-control" name="aliases" id="aliases" required value="{{$emailtemplate->aliases}}"/>
				</div>
				
				<div class="form-group">
					<label for="sender_name">Sender Name</label>
					<input type="text" class="form-control" name="sender_name" id="sender_name" required value="{{$emailtemplate->sender_name}}"/>
				</div>
				<div class="form-group">
					<label for="sender_email">Sender Email</label>
					<input type="text" class="form-control" name="sender_email" id="sender_email" required value="{{$emailtemplate->sender_email}}"/>
				</div> 
            </div>    
            <div class="col-md-6">
				<div class="form-group">
					<label for="status">Status</label>
					<input name="status" id="status" data-id="0" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="Inactive" {{ ($emailtemplate->status=='Active')? 'checked' : '' }}> 
				</div>
				<div class="form-group">
					<label for="cc_email">CC Email</label>
					<input type="text" class="form-control" name="cc_email" id="cc_email"  value="{{$emailtemplate->cc_email}}"/>
				</div>
				<div class="form-group">
					<label for="bcc_email">BCC Email</label>
					<input type="text" class="form-control" name="bcc_email" id="bcc_email"  value="{{$emailtemplate->bcc_email}}"/>
				</div>
            </div>
			<div class="col-md-12">
				<textarea name="content" id="content" rows="10" cols="80" style="visibility: hidden; display: none;">{{$emailtemplate->content}}</textarea><br/>
			</div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <button type="submit" class="btn btn-primary">Update</button>
                <a class="btn btn-warning" href="{{ route('email_templates.index') }}"> Back</a>
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