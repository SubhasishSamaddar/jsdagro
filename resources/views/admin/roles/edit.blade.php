@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Roles Management </h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
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
                <h5>Edit Role</h5>
	        </div> 
        </div>
		
		<div class="card-body">
        <form method="post" action="{{ route('roles.update', $role->id) }}" enctype="multipart/form-data">
        @csrf
		@method('PATCH')
        <div class="row"> 
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name" required value="{{ $role->name }}" placeholder="Name" required/>
                </div>
            </div>    
            <div class="col-md-12">
                <label for="permission">Permission</label>
                <div class="form-group row">
                    
                    @php  $i=0; @endphp
                    @foreach($permission as $value)
                    @php echo ($i%4==0)?'<br/>':''; $i++; @endphp
                    <div class="col-3">
                    <label><input type="checkbox" name="permission[]" value="{{ $value->id}}" class="name" <?php if(in_array($value->id, $rolePermissions)){ echo 'checked="checked"';}?>/>&nbsp;&nbsp;{{ strtoupper($value->name) }}</label>
                    </div>
                    @endforeach 
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <button type="submit" class="btn btn-primary">Update</button>
                <a class="btn btn-warning" href="{{ URL::previous() }}"> Back</a>
            </div>
		</div>
        </form>
	</div> 
@endsection