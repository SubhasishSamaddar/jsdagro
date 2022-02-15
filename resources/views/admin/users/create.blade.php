@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>User Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
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
	            <h4>Create New User</h4>
	        </div>
        </div>

		<div class="card-body">
        <form method="post" action="{{ route('users.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name"  value="{{ old('name') }}" required/>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" name="email" id="email" value="{{ old('email') }}" required autocomplete="off"/>
                </div>
                <div class="form-group">
                    <label for="company_name">Company</label>
                    <input type="text" class="form-control" name="company_name" id="company_name" value="{{ old('company_name') }}"  autocomplete="off"/>
                </div>
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}"  autocomplete="off"/>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" class="form-control" name="phone" id="phone" required  value="{{ old('phone') }}" autocomplete="off"/>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" id="password"  value="" required autocomplete="off"/>
                    <p id="passwordHelpBlock" class="form-text text-muted">
                    Your password must be more than 8 characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.
                    </p>
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" class="form-control" name="password_confirmation" id="confirm-password"  value="" required autocomplete="off"/>
                </div>

                <div class="form-group">
                    <label for="role">Role(s)</label>
                    <select name="roles[]" id="role" class="form-control" multiple>
                        <option value="">Select Role(s)</option>
                        @foreach($roles as $key=>$value)
						<option value="{{ $key}}">{{ strtoupper($value)}}</option>
                        @endforeach
                    </select>

                </div>
            </div>
            <div class="col-md-6">

                <div class="form-group">
                    <label for="address_1">Address Line 1</label>
                    <input type="text" class="form-control" name="address_1" id="address_1" required  value="{{ old('address_1') }}" autocomplete="off"/>
                </div>
                <div class="form-group">
                    <label for="address_2">Address Line 2</label>
                    <input type="text" class="form-control" name="address_2" id="address_2" required  value="{{ old('address_2') }}" autocomplete="off"/>
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" class="form-control" name="city" id="city" required  value="{{ old('city') }}" autocomplete="off"/>
                </div>
                <div class="form-group">
                    <label for="country">Country</label>
                    <select name="country" id="country" class="form-control" required>
                        <option value="0">Select Country</option>
                        @foreach($countries as $value)
						<option value="{{ $value->id}}" {{ ($value->id==old('country'))?'selected':''}}>{{ $value->country_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="state">State</label>
                    <input type="hidden" class="form-control" name="state_old" id="state_old" value="{{ old('state') }}" />
                    <select id='state' name='state' class="form-control" required>
                        <option value='0'>Select State</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="pincode">Pin Code</label>
                    <input type="text" class="form-control" name="pincode" id="pincode" required  value="{{ old('pincode') }}" autocomplete="off"/>
                </div>
                <div class="form-group">
					<div class="btn btn-default btn-file">
						<i class="fas fa-paperclip"></i> Profile Image
						<input type="file" id="profile_image" name="profile_image">
                  	</div>
				</div>
                <div class="form-group">
                    <label for="user_type">User Type</label>
                    <div class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" id="user_type1" name="user_type" onclick="show_swadesh_huts('hide'),show_package_location('hide')" value="Admin" {{ (old('user_type')=='Admin')?'checked':'null' }} >
                        <label for="user_type1" class="custom-control-label">Admin</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" id="user_type2" name="user_type" onclick="show_swadesh_huts('hide'),show_package_location('hide')" value="Vendor" {{ (old('user_type')=='Vendor')?'checked':'null' }}>
                        <label for="user_type2" class="custom-control-label">Vendor</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" id="user_type3" name="user_type" onclick="show_swadesh_huts('hide'),show_package_location('hide')" value="Customer" {{ (old('user_type')=='Customer')?'checked':'null' }}>
                        <label for="user_type3" class="custom-control-label">Customer</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" id="user_type4" name="user_type" onclick="show_swadesh_huts('show'),show_package_location('hide')" value="Swadesh_Hut" {{ (old('user_type')=='Swadesh_Hut')?'checked':'null' }}>
                        <label for="user_type4" class="custom-control-label">Swadesh Hut</label>
                        <select class="form-control" name="user_swadesh_hut_id" id="user_swadesh_hut_id" style="display:none;">
                            <option value="">Select Swadesh-Hut</option>
                            @if($swadesh_huts)
                            @foreach($swadesh_huts as $huts)
                            <option value="{{ $huts->id }}">{{ $huts->location_name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" id="user_type5" name="user_type" onclick="show_swadesh_huts('hide'),show_package_location('show')" value="Package" {{ (old('user_type')=='Package')?'checked':'null' }}>
                        <label for="user_type5" class="custom-control-label">Package</label>
                        <select class="form-control" name="package_location_id" id="package_location_id" style="display:none;">
                            <option value="">Select Package Location</option>
                            @if($package_locations)
                            @foreach($package_locations as $locations)
                            <option value="{{ $locations->id }}">{{ $locations->location_name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <button type="submit" class="btn btn-primary">Save</button>
                <a class="btn btn-warning" href="{{ route('users.index') }}"> Back</a>
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
<script type='text/javascript'>
$(document).ready(function(){
    var id = $('#country').val();
    countrystate(id);
    // Country Change
    $('#country').change(function(){
        // Country id
        var id = $(this).val();
        countrystate(id);
    });

});


function show_swadesh_huts(action)
{
    if(action=='hide'){
        $("#user_swadesh_hut_id").hide();
    }
    else{
        $("#user_swadesh_hut_id").show();
    }

}

function show_package_location(action)
{
    if(action=='hide'){
        $("#package_location_id").hide();
    }
    else{
        $("#package_location_id").show();
    }
}


function countrystate(cid)
{
    var id = cid;
    var selected_state = $('#state_old').val();

         // Empty the dropdown
         $('#state').find('option').not(':first').remove();

         // AJAX request
         $.ajax({
           url: '{{ env("APP_URL")}}cpanel/profile_update/getStates/'+id,
           type: 'get',
           dataType: 'json',
           success: function(response){

             var len = 0;
             if(response['data'] != null){
               len = response['data'].length;
             }

             if(len > 0){
               // Read data and create <option >
               for(var i=0; i<len; i++){

                 var id = response['data'][i].id;
                 var name = response['data'][i].state_name;
                 if(id==selected_state)
                     var option = "<option value='"+id+"' selected>"+name+"</option>";
                 else
                     var option = "<option value='"+id+"'>"+name+"</option>";

                 $("#state").append(option);
               }
             }

           }
        });
}
</script>
@endsection
