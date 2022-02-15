@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>My Profile</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
		<li class="breadcrumb-item active">Profile</li>
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
                <h5>Update Profile</h5>
	        </div>
        </div>
        <div class="card-body">
        <form method="POST" action="{{ route('change.profile') }}" enctype="multipart/form-data">
        @csrf
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                    <label for="name">Name</label>
                    <input id="name" type="text" class="form-control" name="name" value="{{ auth()->user()->name}}" required>
                  </div>
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="text" class="form-control" name="email" value="{{ auth()->user()->email}}" required>
                  </div>
                  <div class="form-group">
                    <label for="email">Company</label>
                    <input id="company_name" type="text" class="form-control" name="company_name" value="{{ auth()->user()->company_name}}" required>
                  </div>
                  <div class="form-group">
                    <label for="title">title</label>
                    <input id="title" type="text" class="form-control" name="title" value="{{ auth()->user()->title}}" >
                  </div>
                  <div class="form-group">
                    <label for="phone">Phone</label>
                    <input id="phone" type="text" class="form-control" name="phone" value="{{ auth()->user()->phone}}" >
                  </div>
                  <div class="form-group">
                    <label for="country">Country</label>
                    <select name="country" id="country" class="form-control" required >
                        <option value="0">Select Country</option>
                        @foreach($countries as $value)
						            <option value="{{ $value->id}}" {{ ($value->id==auth()->user()->country)?'selected':''}}>{{ $value->country_name}}</option>
                        @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <div class="btn btn-default btn-file">
                      <i class="fas fa-paperclip"></i> Profile Image
                      <input type="file" id="profile_image" name="profile_image">
                    </div>
                  </div>
                  <div class="form-group">
                      <img src="{{ asset('/storage/'.auth()->user()->profile_image)}}" style="height:60px;" />
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                    <label for="address_1">Address Line 1</label>
                    <input id="address_1" type="text" class="form-control" name="address_1" value="{{ auth()->user()->address_1}}" >
                  </div>
                  <div class="form-group">
                    <label for="address_2">Address Line 2</label>
                    <input id="address_2" type="text" class="form-control" name="address_2" value="{{ auth()->user()->address_2}}" >
                  </div>
                  <div class="form-group">
                    <label for="city">City</label>
                    <input id="city" type="text" class="form-control" name="city" value="{{ auth()->user()->city}}" >
                  </div>
                  <div class="form-group">
                    <label for="state">State</label>
                    <input id="state_old" type="hidden" class="form-control" name="state_old" value="{{ auth()->user()->state}}" >
                    <select id='state' name='state' class="form-control">
                        <option value='0'>Select State</option>
                        @foreach($states as $value)
                        <option value="{{ $value->id}}" {{ ($value->id==auth()->user()->state)?'selected':''}}>{{ $value->state_name}}</option>
                        @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="pincode">Pin Code</label>
                    <input id="pincode" type="text" class="form-control" name="pincode" value="{{ auth()->user()->pincode}}" >
                  </div>
              </div>

              <div class="col-xs-12 col-sm-12 col-md-12">
                <button type="submit" class="btn btn-primary">Update</button>
              </div>
            </div>
        </form>
        </div>
    </div>

@endsection
@section('js')
<script type='text/javascript'>
    $(document).ready(function(){
      // Country Change
      $('#country').change(function(){
         // Country id
         var id = $(this).val();
         var selected_state = $('#state_old').val();

         // Empty the dropdown
         $('#state').find('option').not(':first').remove();

         // AJAX request
         $.ajax({
           url: '{{ env("APP_URL")}}profile_update/getStates/'+id,
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
      });

    });
</script>
@endsection
