@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Category Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
		<li class="breadcrumb-item active">Categories</li>
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
				<a class="btn btn-info" href="<?php echo url('/') ?>/cpanel/categories/changenameurl">Change Name Url</a>
	        </div>
	        <div class="pull-right">
			@can('category-create')
	            <a class="btn btn-success" href="{{ route('categories.create') }}"> Create New Category</a>
			@endcan
			</div>
        </div>

		<div class="card-body">
			<table class="table table-bordered table-sm" id="datatable">
				<thead>
					<tr class="text-center">
						<th>Name</th>
						<th>Parent Category</th>
						<th>Image</th>
						<th>Position</th>
                        <th>Status</th>
                        <th>Show In Homepage</th>
						<th>Created At</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
                @foreach ($categories as $key => $category)
				<tr>
					<td>{{ $category->name }}</td>
					<td>{{ $category->parent_name }}</td>
					<td><img src="{{ asset('/storage/'.$category->category_image)}}" style="height:60px;" /></td>
					<td>{{ $category->position_order }}</td>
                    <td class="text-center"><input data-id="{{$category->id}}" class="toggle-class show-status  btn-sm" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="Inactive" {{ ($category->status=="Active")? "checked" : "" }} data-size="xs"></td>


                    <td class="text-center"><input data-id="{{$category->id}}" class="toggle-class shown-homepage btn-sm" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Show" data-off="Hide" {{ ($category->show_home_page=="Show")? "checked" : "" }} data-size="xs"></td>


					<td class="text-center" data-sort="{{ date('d-m-Y',strtotime($category->created_at)) }}">{{ date('d-m-Y',strtotime($category->created_at)) }}</td>
					<td class="text-center">
						@can('category-edit')
						<a class="btn btn-primary" href="{{ route('categories.edit',$category->id) }}"  title="Edit"><i class="fas fa-edit"></i></a>
						@endcan
						@can('category-delete')
						<form method="post" action="{{ route('categories.destroy',$category->id) }}" style='display:inline' >
        				@csrf
                  		@method('DELETE')
						<button type="submit"  onclick="return confirm('Are you sure to Delete the Category ?');" class="btn btn-danger"  title="Delete" ><i class="fas fa-trash"></i></button>
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
    $('.show-status').change(function() {
        var status = $(this).prop('checked') == true ? 'Active' : 'Inactive';
        var id = $(this).data('id');

        $.ajax({
            type: "GET",
            dataType: "json",
			url: "{{ url('cpanel/categories/changestatus') }}",
            data: {'status': status, 'id': id},
            success: function(data){
              console.log(data.success)
            }
        });
    })
  })





  $(function() {
    $('.shown-homepage').change(function() {
        var shown = $(this).prop('checked') == true ? 'Show' : 'Hide';
        var id = $(this).data('id');
        $.ajax({
            type: "GET",
            dataType: "json",
			url: "{{ url('cpanel/categories/changeshownstatus') }}",
            data: {'shown': shown, 'id': id},
            success: function(data){
              alert(data.success);
            }
        });
    })
  })
</script>
@endsection
