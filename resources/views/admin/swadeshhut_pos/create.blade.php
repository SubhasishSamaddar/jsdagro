@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Store Pos Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="">Store Pos</a></li>
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
	            <h4>Create New POS</h4>
	        </div>
        </div>

		<div class="card-body">
        <form method="post" action="{{ route('swadesh_hut_pos.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="prod_name">Name</label>
                    <input type="text" class="form-control" name="name" id="name"  value="{{ old('name') }}" required/>
                </div>
				<div class="form-group">
                    <label for="prod_name">User Name</label>
                    <input type="text" class="form-control" name="userName" id="userName"  value="{{ old('userName') }}" required/>
                </div>
				<div class="form-group">
                    <label for="prod_name">Password</label>
                    <input type="text" class="form-control" name="password" id="password"  value="{{ old('password') }}" required/>
                </div>
                
               
              <?php if(Auth::user()->user_type !='Swadesh_Hut') { ?>
                <div class="form-group">
					<label for="country_id">Store</label>
					<select name="swadeshhut_id" id="swadeshhut_id" class="form-control" required >
                        <option value="0">-- Select Store --</option>
                        @foreach($records as $value)
						<option value="{{ $value->id}}" {{ ($value->id==old('swadeshhut_id'))?'selected':''}}>{{ $value->location_name}}</option>
                        @endforeach
                    </select> 
				</div>
               <?php } ?>
				


				<div class="form-group">
					<label for="status">Status</label><br/>
					<input name="status" id="status" data-id="0" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="Inactive" {{ (old('status')=='on')? 'checked' : '' }}>
				</div>

            </div>
           			
          
            <div class="col-xs-12 col-sm-12 col-md-12">
                <button type="submit" class="btn btn-primary">Save</button>
                <a class="btn btn-warning" href="{{ route('swadesh_hut_pos.index') }}"> Back</a>
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
