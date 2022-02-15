@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Swadesh Huts Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
		<li class="breadcrumb-item active">Swadesh Huts</li>
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
			@can('swadesh_hut-create')
	            <a class="btn btn-success" href="{{ route('stores.create') }}"> Create New Swadesh Hut</a>
			@endcan
			</div>
        </div>

		<div class="card-body">
			<table class="table table-bordered table-sm" id="datatable">
				<thead>
					<tr class="text-center">
						<th>Location Name</th>
                        <th>Cover Area Pincodes</th>
						<th>Address</th>
                        <th>Status</th>
						<th>Created At</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				@foreach ($records as $key => $product)
				<tr>
					<td>{{ $product->location_name }}</td>
                    <td>{{ $product->cover_area_pincodes }}</td>
					<td>{{ $product->address }}</td>
                    <td class="text-center"><input data-id="{{$product->id}}" class="toggle-class  btn-sm" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="Inactive" {{ ($product->status=="Active")? "checked" : "" }} data-size="xs"></td>
					<td class="text-center" data-sort="{{ date('d-m-Y',strtotime($product->created_at)) }}">{{ date('d-m-Y',strtotime($product->created_at)) }}</td>
					<td class="text-center">
						@can('swadesh_hut-edit')
						<a class="btn btn-primary" href="{{ route('stores.edit',$product->id) }}"  title="Edit"><i class="fas fa-edit"></i></a>
						@endcan
						@can('swadesh_hut-delete')
						<form method="post" action="{{ route('stores.destroy',$product->id) }}" style='display:inline' >
        				@csrf
                  		@method('DELETE')
						<button type="submit"  onclick="return confirm('Are you sure to Delete the Swadesh Hut ?');" class="btn btn-danger"  title="Delete" ><i class="fas fa-trash"></i></button>
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
@section('css')
<link href="{{ asset('/css/bootstrap-toggle.min.css') }}" rel="stylesheet">
@endsection
@section('js')
<script src="{{ asset('/js/bootstrap-toggle.min.js') }}"></script>
<script>
  $(function() {
    $('.toggle-class').change(function() {
        var status = $(this).prop('checked') == true ? 'Active' : 'Inactive';
        var id = $(this).data('id');

        $.ajax({
            type: "GET",
            dataType: "json",
			url: "{{ url('cpanel/swadesh_hut/changestatus') }}",
            data: {'status': status, 'id': id},
            success: function(data){
              console.log(data.success)
            }
        });
    })
  })
</script>
@endsection
