@extends('layouts.admin')

@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Brand Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="/home">Home</a></li>
		<li class="breadcrumb-item active">Brand Management</li>
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
	            <a class="btn btn-success" href="{{ route('brands.create') }}"> Create New Brand</a> 
	        </div>
        </div>

		<div class="card-body">
			<table class="table table-bordered " id="datatable">
				<thead>
					<tr class="text-center">
						<th>Brand Name</th> 
						<th>Created At</th>
						<th>Updated At</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				@foreach ($data as $key => $brand)
				<tr>
					<td>{{ $brand->brand_name }}</td>
					<td>{{ $brand->created_at }}</td>
					<td>{{ $brand->modified_at }}</td>  
					<td class="text-center"> 
						<a class="btn btn-primary btn-sm" href="{{ route('brands.edit',$brand->id) }}"  title="Edit"><i class="fas fa-edit"></i></a>
					 	<form method="post" action="{{ route('brands.destroy',$brand->id) }}" style='display:inline' >
        				@csrf
                  		@method('DELETE')
						<button type="submit"  onclick="return confirm('Are you sure to Delete the Brand?');" class="btn btn-danger btn-sm"  title="Delete" ><i class="fas fa-trash"></i></button>
						</form> 
					</td>
				</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>

@endsection 
