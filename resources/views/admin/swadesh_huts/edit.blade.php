@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Swadesh Huts Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('stores.index') }}">Swadesh Huts</a></li>
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
	            <h4>Edit Swadesh Huts</h4>
	        </div>
        </div>

		<div class="card-body">
        <form method="post" action="{{ route('stores.update', $details->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="prod_name">Location Name</label>
                    <input type="text" class="form-control" name="location_name" id="location_name"  value="{{$details->location_name}}" required/>
                </div>
                
				<div class="form-group">
					<label for="description">Close Time</label><br/>
					<?php
					$start = "14:00"; //you can write here 00:00:00 but not need to it
					$end = "23:00";
					$tStart = strtotime($start);
					$tEnd = strtotime($end);
					$tNow = $tStart;
					?>
					<select name="close_time" id="close_time" class="form-control">
						<?php 
						while($tNow <= $tEnd){
							echo '<option value="'.date("H:i:s",$tNow).'"';
							if($details->close_time==date("H:i:s",$tNow))
							{
								echo 'selected="selected"';
							}
							echo '>'.date("H:i:s",$tNow).'</option>';
							$tNow = strtotime('+30 minutes',$tNow);
						}
						?>
					</select>
				</div>

				<div class="form-group">
					<label for="status">Status</label><br/>
					<input name="status" id="status" data-id="0" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="Inactive" {{ ($details->status=='Active')? 'checked' : '' }}>
                </div>

				<div class="col-md-6">
					<label for="address">Invoice Prefix</label><br/>
					<input type="text" name="invoice_prefix" id="invoice_prefix" required value="{{ $details->invoice_prefix }}"><br/>
				</div>

				<div class="col-md-6">
					<label for="address">Address</label><br/>
					<textarea name="address" id="address" rows="5" cols="80" style="" required >{{$details->address}}</textarea><br/>
					<p>*please enter complete address here </p>
				</div>

            </div>
            <div class="col-md-6">
                <label for="description">Cover Area Pincodes</label><br/>
				<textarea name="cover_area_pincodes" id="cover_area_pincodes" rows="5" cols="80" style="">{{$details->cover_area_pincodes}}</textarea><br/>
            </div>

			

			
         
            <div class="col-xs-12 col-sm-12 col-md-12">
                <button type="submit" class="btn btn-primary">Save</button>
                <a class="btn btn-warning" href="{{ route('stores.index') }}"> Back</a>
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
