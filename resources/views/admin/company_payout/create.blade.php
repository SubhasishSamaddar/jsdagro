@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Payout Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('company-payouts.index') }}">Payouts</a></li>
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
	            <h4>Create New Payout</h4>
	        </div>
        </div>

		<div class="card-body">
        <form method="post" action="{{ route('company-payouts.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
			<div class="col-md-6">
                <div class="form-group">
                    <label for="prod_name">Company Name</label>
                    <input type="text" class="form-control" name="company_name" id="company_name" required/>
                </div>
                
				<div class="form-group">
                    <label for="prod_name">Amount</label>
                    <input type="text" class="form-control" name="payout_amount" id="payout_amount" required/>
                </div>
            </div>
            <div class="col-md-6">
                <label for="description">Status?</label><br/>
				<input type="radio" name="status" id="status" value="credit">Pay In&nbsp;&nbsp;
				<input type="radio" name="status" id="status" value="debit">Pay Out
            </div>
			
          
            <div class="col-xs-12 col-sm-12 col-md-12">
                <button type="submit" class="btn btn-primary">Save</button>
                <a class="btn btn-warning" href="{{ route('company-payouts.index') }}"> Back</a>
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
