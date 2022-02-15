@extends('layouts.admin')

@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Navigations Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
		<li class="breadcrumb-item active">Navigations</li>
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
			@can('navigation-create')
	            <a class="btn btn-success" href="{{ route('navigations.create') }}"> Create New Navigations</a>
			@endcan	
	        </div> 
        </div>
		
		<div class="card-body">
			<table class="table table-bordered table-sm" id="datatable">
				<thead>
					<tr class="text-center">						
						<th>Title</th> 
						<th>URL</th> 
						<th>Position</th>   
						<th>Target Window</th> 
						<th>Updated At</th>
						<th>Action</th>
					</tr>
				</thead>	
				<tbody>
				@foreach ($navigations as $key => $navigation)
				<tr>  
					<td>{{ $navigation->title }}</td> 
					<td>{{ $navigation->link_url }}</td> 
					<td>{{ $navigation->position_block }} [ {{ $navigation->position_order }} ]</td> 
					<td class="text-center"> <input data-id="{{$navigation->id}}" class="toggle-class  btn-sm" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Parent" data-off="New" {{ ($navigation->target=="_self")? "checked" : "" }} data-size="xs"> </td> 
					<td class="text-center" data-sort="{{ date('d-m-Y',strtotime($navigation->updated_at)) }}">{{ date('d-m-Y',strtotime($navigation->updated_at)) }}</td>
					<td class="text-center"> 
						<!-- <a class="btn btn-info btn-sm" href="{{ route('navigations.show',$navigation->id) }}" title="View"><i class="fas fa-play-circle"></i></a> -->
						@can('navigation-edit')
						<a class="btn btn-primary btn-sm" href="{{ route('navigations.edit',$navigation->id) }}"  title="Edit"><i class="fas fa-edit"></i></a>
						@endcan
						@can('navigation-delete')
						<form method="post" action="{{ route('navigations.destroy',$navigation->id) }}" style='display:inline' >
        				@csrf
                  		@method('DELETE')
						<button type="submit"  onclick="return confirm('Are you sure to Delete the Navigation?');" class="btn btn-danger btn-sm"  title="Delete" ><i class="fas fa-trash"></i></button>
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
        var target = $(this).prop('checked') == true ? '_self' : '_blank'; 
        var id = $(this).data('id'); 
         
        $.ajax({
            type: "GET",
			dataType: "json",
			url: "{{ url('navigations/changestatus') }}", 
            data: {'target': target, 'id': id},
            success: function(data){
              console.log(data.success)
            }
        });
    })
  })
</script>	
@endsection