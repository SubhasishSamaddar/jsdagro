@extends('layouts.admin')
@section('content')
<div class="card uper">
  <div class="card-header">
    Change Password with Current Password Validation
  </div>
  <div class="card-body">  
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
        </ul>
      </div><br />
    @endif
    @if(session()->get('success'))
        <div class="alert alert-success">
        {{ session()->get('success') }}  
        </div><br />
    @endif

    <form method="POST" action="{{ route('change.password') }}">
        @csrf  
        <div class="form-group row">
            <label for="password" class="col-md-4 col-form-label text-md-right">Current Password</label>

            <div class="col-md-6">
                <input id="password" type="password" class="form-control" name="current_password" required autocomplete="current-password">
            </div>
        </div>

        <div class="form-group row">
            <label for="password" class="col-md-4 col-form-label text-md-right">New Password</label>

            <div class="col-md-6">
                <input id="new_password" type="password" class="form-control" name="new_password" required autocomplete="current-password">
            </div>
        </div>

        <div class="form-group row">
            <label for="password" class="col-md-4 col-form-label text-md-right">New Confirm Password</label>

            <div class="col-md-6">
                <input id="new_confirm_password" type="password" class="form-control" name="new_confirm_password" required autocomplete="current-password">
                <p id="passwordHelpBlock" class="form-text text-muted">
                  Your password must be more than 8 characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.
                </p>
            </div>
        </div> 
        <div class="form-group row mb-0">
            <div class="col-md-8 offset-md-4">
                <button type="submit" class="btn btn-primary">
                    Update Password
                </button>
            </div>
        </div>
    </form>

  </div>
</div>   
@endsection