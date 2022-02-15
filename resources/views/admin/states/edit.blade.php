@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>States Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('states.index') }}">States</a></li>
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
                <h5>Edit State</h5>
	        </div> 
        </div>
		
		<div class="card-body">
        <form method="post" action="{{ route('states.update', $state->id) }}" enctype="multipart/form-data">
        @csrf
		@method('PATCH')
        <div class="row"> 
			<div class="col-md-6"> 
                <div class="form-group">
					<label for="state_name">State Name</label>
					<input type="text" class="form-control" name="state_name" id="state_name" required value="{{ $state->state_name }}"/>
				</div>
				<div class="form-group">
					<label for="state_code">ISO 3 Code</label>
					<input type="text" class="form-control" name="state_code" id="state_code" required value="{{ $state->state_code }}"/>
				</div>  
            </div>    
            <div class="col-md-6"> 
                <div class="form-group"> 
					<label for="country_id">Country</label> 
					<select name="country_id" id="country_id" class="form-control" required >
                        <option value="0">Select Country</option>
                        @foreach($countries as $value)
						<option value="{{ $value->id}}" {{ ($value->id==$state->country_id)?'selected':''}}>{{ $value->country_name}}</option>
                        @endforeach
                    </select> 
				</div>
            </div>
			 
            <div class="col-xs-12 col-sm-12 col-md-12">
                <button type="submit" class="btn btn-primary">Update</button>
                <a class="btn btn-warning" href="{{ route('states.index') }}"> Back</a>
            </div>
		</div>
        </form>
	</div> 
@endsection 