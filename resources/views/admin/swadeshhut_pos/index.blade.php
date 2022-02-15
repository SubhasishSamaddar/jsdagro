@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Store Pos Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
		<li class="breadcrumb-item active">Store Pos</li>
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
			
	            <a class="btn btn-success" href="{{ route('swadesh_hut_pos.create') }}"> Create New Store Pos</a>
			
			</div>
        </div>

		<div class="card-body">
			<table class="table table-bordered table-sm" id="datatable">
				<thead>
					<tr class="text-center">
						<th>Pos Name</th>
                        <th>Pos User Name</th>
						<th>Password</th>
                        <th>Status</th>
						<th>Created At</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				@foreach ($records as $key => $product)
				<tr>
					<td>{{ $product->name }}</td>
                    <td>{{ $product->userName }}</td>
					<td>{{ $product->password }}</td>
                    <td class="text-center"><input data-id="{{$product->id}}" class="toggle-class  btn-sm" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="Inactive" {{ ($product->status=="Active")? "checked" : "" }} data-size="xs"></td>
					<td class="text-center" data-sort="{{ date('d-m-Y',strtotime($product->created_at)) }}">{{ date('d-m-Y',strtotime($product->created_at)) }}</td>
					<td class="text-center">
						
						<a class="btn btn-primary" href="{{ route('swadesh_hut_pos.edit',$product->id) }}"  title="Edit"><i class="fas fa-edit"></i></a>
						
						
						<form method="post" action="{{ route('swadesh_hut_pos.destroy',$product->id) }}" style='display:inline' >
        				@csrf
                  		@method('DELETE')
						<button type="submit"  onclick="return confirm('Are you sure to Delete the Swadesh Hut Pos ?');" class="btn btn-danger"  title="Delete" ><i class="fas fa-trash"></i></button>
						</form>
						
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
			url: "{{ url('cpanel/swadesh_hut_pos/changestatus') }}",
            data: {'status': status, 'id': id},
            success: function(data){
              console.log(data.success)
            }
        });
    })
  })
</script>
@endsection
