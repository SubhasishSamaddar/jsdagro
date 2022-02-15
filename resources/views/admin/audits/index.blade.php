@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Audit Log Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
		<li class="breadcrumb-item active">Audit Log</li>
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
		 
		
		<div class="card-body">
			<table class="table table-bordered table-sm" id="datatable">
				<thead>
					<tr class="text-center">						
                        <th scope="col">Model</th>
                        <th scope="col">Action</th>
                        <th scope="col">User</th>
                        <th scope="col">Time</th>
                        <th scope="col">Old Values</th>
                        <th scope="col">New Values</th>
                        <th scope="col">Ip Address</th>
					</tr>
				</thead>	
				<tbody>
				@foreach($audits as $audit)
				<tr>					
                    <td>{{ $audit->auditable_type }} (id: {{ $audit->auditable_id }})</td>
                    <td>{{ $audit->event }}</td>
                    <td><?php if(isset($audit->user->username)) echo $audit->user->username;?></td>
                    <td>{{ $audit->created_at }}</td>
                    <td>
                        <table style="table-layout: fixed; width: 100%">
                            @foreach($audit->old_values as $attribute => $value)
                            <tr>
                                <td style="width: 114px;word-wrap:break-word;overflow: hidden;"><b>{{ $attribute }}</b></td>
                                <td style="width: 114px;word-wrap:break-word;overflow: hidden;">{{ $value }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </td>
                    <td>
                        <table style="table-layout: fixed; width: 100%">
                            @foreach($audit->new_values as $attribute => $value)
                            <tr>
                                <td style="width: 114px;word-wrap:break-word;overflow: hidden;"><b>{{ $attribute }}</b></td>
                                <td style="width: 114px;word-wrap:break-word;overflow: hidden;">{{ $value }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </td>
                    <td>{{ $audit->ip_address }}</td>
				</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div> 
    @endsection