@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Roles Management </h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
		<li class="breadcrumb-item active">View</li>
	</ol>
	</div>
</div> 
@endsection  
@section('content')
	 
	<div class="card"> 
		<div class="card-header">
			<div class="pull-left"> 
                <h5>View Role</h5>
	        </div> 
        </div>
		
		<div class="card-body">
         
        <div class="row"> 
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Name:</strong>
                    &nbsp;{{ strtoupper($role->name) }}
                </div>
            </div>    
            <div class="col-md-12">
                <strong>Permissions:</strong>
                <div class="form-group row">
                    @if(!empty($rolePermissions))
                    @php  $i=0; @endphp
                        @foreach($rolePermissions as $v)
                        @php echo ($i%4==0)?'<br/>':''; $i++; @endphp
                        <div class="col-3">
                            <label class="label label-success">{{ strtoupper($v->name) }},</label>
                        </div>
                        @endforeach
                    @endif 
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <a class="btn btn-warning" href="{{ URL::previous() }}"> Back</a>
            </div>
		</div> 
	</div> 
@endsection