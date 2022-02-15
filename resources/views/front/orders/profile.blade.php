@extends('layouts.front')
@section('seo-header')
		<title>My Profile</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
@endsection
<?php //echo '<pre>'; print_r($details); echo '</pre>';?>
@section('content')
<div class="container">
<div class="accountarea">
<div class="breadcrumb"><a href="{{ url('/') }}/home">Home</a>  >  <a href="{{ url('/') }}/my-profile">My Profile</a></div>
	<div id="sidebar">
		<ul class="sidenav nav-list">
				<li>
					<a href="{{ route('my-profile') }}"> My Profile</a>
				</li>
				<li>
					<a href="{{ route('my-order') }}"> My Order</a>
				</li>
				<li>
					<a href="{{ route('logout') }}"> Logout</a>
				</li>
			</ul>
		
	</div>
	<div class="profilearea">
		<!--<ul class="breadcrumb">
			<li><a href="{{ route('sitelink') }}">Home</a> <span class="divider">/</span></li>
			<li class="active">Profile</li>
		</ul>-->
		@if ($errors->any())
			<div class="alert alert-danger">
				<strong>Whoops!</strong> There were some problems with your input.<br>
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
				</ul>
			</div>
		@endif
		<div class="profileform">
			<form method="post" id="updateprofileform" class="form-horizontal" >
				@csrf 
				<h3>Your Details</h3>
				
				<div class="control-group">
					<label class="control-label" for="inputFname">Full Name<sup>*</sup></label>
					<div class="controls">
						<input type="text" name="name" id="name" placeholder="Full Name" value="{{ $details->name }}" required >
					</div>
				</div>
				
				
				<div class="control-group">
					<label class="control-label" for="inputFname">Email</label>
					<div class="controls">
						<input type="text" name="email" id="email" placeholder="Full Name" value="{{ $details->email }}" disabled >
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="inputFname">Phone<sup>*</sup></label>
					<div class="controls">
						<input type="text" name="phone" id="phone" placeholder="Phone" value="{{ $details->phone }}" required >
					</div>
				</div>
				
				<!--<div class="control-group">
					<label class="control-label" for="inputFname">Company Name</label>
					<div class="controls">
						<input type="text" name="company_name" id="company_name" placeholder="Company Name" value="{{ $details->company_name }}" required>
					</div>
				</div>-->
				
				<div class="control-group">
					<label class="control-label" for="inputFname">Address Line 1<sup>*</sup></label>
					<div class="controls">
						<input type="text" name="address_1" id="address_1" placeholder="Address Line 1" value="{{ $details->address_1 }}" required>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="inputFname">Address Line 2</label>
					<div class="controls">
						<input type="text" name="address_2" id="address_2" placeholder="Address Line 2" value="{{ $details->address_2 }}" >
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="inputFname">City<sup>*</sup></label>
					<div class="controls">
						<input type="text" name="city" id="city" placeholder="city" value="{{ $details->city }}" required>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="inputFname">Pincode<sup>*</sup></label>
					<div class="controls">
						<input type="text" name="pincode" id="pincode" placeholder="Pincode" value="{{ $details->pincode }}" required>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="inputFname">State<sup>*</sup></label>
					<div class="controls">
						<select name="state" >
						<option value="">-- Select State --</option>
						@foreach( $stateDetails as $stateDetail )
							@if( $stateDetail->state_name=='West Bengal' )
								@php $selected='selected="selected"'; @endphp
							@else
								@php $selected=''; @endphp
							@endif
							<option value="{{ $stateDetail->state_name }}" {{ $selected }}>{{ $stateDetail->state_name }}</option>
						@endforeach
						</select>
					</div>
				</div>
				
				<div class="control-group">
					<div class="controls">
						<input type="button" name="submitAccount" value="Update" class="exclusive shopBtn" onclick="update_profile()">
						<br clear="all">
						<br clear="all">
						<div id="profile_msg">
							<img id="load_img" src="<?php echo url('/'); ?>/public/img/loading.gif" height="35px" width="35px;" style="display:none;">
						</div>
					</div>
				</div>		

				
			</form>
			
			
			
			
						
			
			
			
			
			
		</div>
	</div>
		
			
			
	</div></div>
@endsection
@section('css')
@endsection
@section('js')
<script>
function update_profile()
{
	$("#load_img").show();
	$.ajax({
		type: "POST",
		dataType: "json",
		url: "{{ url('update_profile') }}",
		data: $("#updateprofileform").serialize(),
		success: function(data){
			$("#load_img").hide();
			$("#profile_msg").html(data.msg);
			//window.location.reload();
		}
	});
}
</script>
@endsection
