@extends('layouts.admin')

@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Countries Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
		<li class="breadcrumb-item active">Countries</li>
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
			@can('country-create')
	            <a class="btn btn-success" href="{{ route('countries.create') }}"> Create New Country</a>
			@endcan
	        </div>
        </div>

		<div class="card-body">
			<table class="table table-bordered table-sm" id="datatable">
				<thead>
					<tr class="text-center">
						<th>Country Name</th>
						<th>ISO 2 Code</th>
						<th>ISO 3 Code</th>
						<th>Position</th>
						<th>Status</th>
						<th>Updated At</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				@foreach ($countries as $key => $country)
				<tr>
					<td>{{ $country->country_name }}</td>
					<td>{{ $country->country_code2 }}</td>
					<td>{{ $country->country_code3 }} </td>
					<td>{{ $country->position_order }}</td>
					<td class="text-center"> <input data-id="{{$country->id}}" class="toggle-class  btn-sm" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="Inactive" {{ ($country->status=="Active")? "checked" : "" }} data-size="xs"> </td>
					<td class="text-center" data-sort="{{ date('d-m-Y',strtotime($country->updated_at)) }}">{{ date('d-m-Y',strtotime($country->updated_at)) }}</td>
					<td class="text-center">
						<!-- <a class="btn btn-info btn-sm" href="{{ route('countries.show',$country->id) }}" title="View"><i class="fas fa-play-circle"></i></a> -->
						@can('country-edit')
						<a class="btn btn-primary btn-sm" href="{{ route('countries.edit',$country->id) }}"  title="Edit"><i class="fas fa-edit"></i></a>
						@endcan
						@can('country-delete')
						<form method="post" action="{{ route('countries.destroy',$country->id) }}" style='display:inline' >
        				@csrf
                  		@method('DELETE')
						<button type="submit"  onclick="return confirm('Are you sure to Delete the Country?');" class="btn btn-danger btn-sm"  title="Delete" ><i class="fas fa-trash"></i></button>
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
			url: "{{ url('cpanel/countries/changestatus') }}",
            data: {'status': status, 'id': id},
            success: function(data){
              console.log(data.success)
            }
        });
    })
  })
</script>
@endsection
