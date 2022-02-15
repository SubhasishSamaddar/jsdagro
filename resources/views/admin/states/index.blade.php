@extends('layouts.admin')

@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>States Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
		<li class="breadcrumb-item active">States</li>
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
	@if ($message = Session::get('error'))
	<div class="alert alert-danger">
		<ul class="margin-bottom-none padding-left-lg">
			<li>{{ $message }} </li>
		</ul>
	</div>
	@endif
	<div class="card"> 
		<div class="card-header">
			<div class="pull-left">
	             
	        </div> 
	        <div class="pull-right">
			@can('state-create')
	            <a class="btn btn-success" href="{{ route('states.create') }}"> Create New State</a>
			@endcan	
	        </div> 
        </div>
		
		<div class="card-body">
			<table class="table table-bordered table-sm" id="datatable">
				<thead>
					<tr class="text-center">						
						<th>State Name</th>  
						<th>ISO 3 Code</th> 
						<th>Country</th>  
						<th>Updated At</th>
						<th>Action</th>
					</tr>
				</thead>	
				<tbody>
				@foreach ($states as $key => $state)
				<tr>  
					<td>{{ $state->state_name }}</td> 
					<td>{{ $state->state_code }}</td> 
					<td>{{ $state->country_name }} </td>   					
					<td class="text-center" data-sort="{{ date('d-m-Y',strtotime($state->updated_at)) }}">{{ date('d-m-Y',strtotime($state->updated_at)) }}</td>
					<td class="text-center"> 
						<!-- <a class="btn btn-info btn-sm" href="{{ route('states.show',$state->id) }}" title="View"><i class="fas fa-play-circle"></i></a> -->
						@can('state-edit')
						<a class="btn btn-primary btn-sm" href="{{ route('states.edit',$state->id) }}"  title="Edit"><i class="fas fa-edit"></i></a>
						@endcan
						@can('state-delete')
						<form method="post" action="{{ route('states.destroy',$state->id) }}" style='display:inline' >
        				@csrf
                  		@method('DELETE')
						<button type="submit"  onclick="return confirm('Are you sure to Delete the State?');" class="btn btn-danger btn-sm"  title="Delete" ><i class="fas fa-trash"></i></button>
						</form> 
						@endcan
					</td>
				</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div> 
 
@endsection 