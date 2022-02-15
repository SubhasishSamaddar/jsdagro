@extends('layouts.admin')

@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Email Templates Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="/home">Home</a></li>
		<li class="breadcrumb-item active">Email Templates</li>
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
			@can('email_template-create')
	            <a class="btn btn-success" href="{{ route('email_templates.create') }}"> Create New Email Templates</a>
			@endcan
	        </div>
        </div>

		<div class="card-body">
			<table class="table table-bordered " id="datatable">
				<thead>
					<tr class="text-center">
						<th>Title</th>
						<th>URL Aliase</th>
						<th>Sender</th>
						<th>CC / BCC</th>
						<th>Status</th>
						<th>Updated At</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				@foreach ($emailtemplates as $key => $emailtemplate)
				<tr>
					<td>{{ $emailtemplate->title }}</td>
					<td>{{ $emailtemplate->aliases }}</td>
					<td>{{ $emailtemplate->sender_name }}<br/>< {{ $emailtemplate->sender_email }} ></td>
					<td>{{ $emailtemplate->cc_email }}<br/> {{ $emailtemplate->bcc_email }}</td>
					<td> <input data-id="{{$emailtemplate->id}}" class="toggle-class  btn-sm" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="Inactive" {{ ($emailtemplate->status=="Active")? "checked" : "" }} data-size="xs"> </td>
					<td class="text-center" data-sort="{{ date('d-m-Y',strtotime($emailtemplate->updated_at)) }}">{{ date('d-m-Y',strtotime($emailtemplate->updated_at)) }}</td>
					<td class="text-center">
						@can('email_template-edit')
						<a class="btn btn-primary btn-sm" href="{{ route('email_templates.edit',$emailtemplate->id) }}"  title="Edit"><i class="fas fa-edit"></i></a>
						@endcan
						@can('email_template-delete')
						<form method="post" action="{{ route('email_templates.destroy',$emailtemplate->id) }}" style='display:inline' >
        				@csrf
                  		@method('DELETE')
						<button type="submit"  onclick="return confirm('Are you sure to Delete the Email Template?');" class="btn btn-danger btn-sm"  title="Delete" ><i class="fas fa-trash"></i></button>
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
<link href="/css/bootstrap-toggle.min.css" rel="stylesheet">
@endsection
@section('js')
<script src="/js/bootstrap-toggle.min.js"></script>
<script>
  $(function() {
    $('.toggle-class').change(function() {
        var status = $(this).prop('checked') == true ? 'Active' : 'Inactive';
        var id = $(this).data('id');

        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ url('cpanel/email_templates/changestatus') }}",
            data: {'status': status, 'id': id},
            success: function(data){
              console.log(data.success)
            }
        });
    })
  })
</script>
@endsection
