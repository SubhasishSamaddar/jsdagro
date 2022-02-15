@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Payout Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
		<li class="breadcrumb-item active">Payouts</li>
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
			<a class="btn btn-info" href="javascript:void(0)" data-toggle="modal" data-target="#payoutLogModal">Payout Log</a>
	            <a class="btn btn-success" href="{{ route('company-payouts.create') }}"> Create Payout</a>
			</div>
        </div>

		<div class="card-body">
			<table class="table table-bordered table-sm" id="datatable">
				<thead>
					<tr class="text-center">
						<th>Company Name</th>
                        <th>Payout Amount</th>
                        <th>Status</th>
						<th>Created At</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				@foreach ($records as $key => $product)
				<tr>
					<td>{{ $product->company_name }}</td>
                    <td>र{{ $product->payout_amount }}</td>
					<td>{{ $product->status }}</td>
					<td class="text-center" data-sort="{{ date('d-m-Y',strtotime($product->created_at)) }}">{{ date('d-m-Y',strtotime($product->created_at)) }}</td>
					<td class="text-center">
						<a class="btn btn-primary" href="{{ route('company-payouts.edit',$product->id) }}"  title="Edit"><i class="fas fa-edit"></i></a>
						<form method="post" action="{{ route('company-payouts.destroy',$product->id) }}" style='display:inline' >
        				@csrf
                  		@method('DELETE')
						<button type="submit"  onclick="return confirm('Are you sure to Delete?');" class="btn btn-danger"  title="Delete" ><i class="fas fa-trash"></i></button>
						</form>
					</td>
				</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>




	<div id="payoutLogModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title">Payout Log</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4" id="bulkpd">
						Start Date
                        <input type="date" name="start_date" id="start_date" class="form-control" placeholder="Start Date">
                    </div>
                
                    <div class="col-md-4">
						End Date
						<input type="date" name="end_date" id="end_date" class="form-control" placeholder="End Date">
                    </div>  

                    <div class="col-md-4">
						Payment Status
                        <select name="type" class="form-control" id="type">
                            <option value="credit">Pay In</option>
                            <option value="debit">Pay Out</option>
                        </select>
                    </div>  
                </div>
				<br clear="all">
                <button type="button" class="btn btn-primary" onclick="get_log()">Get Log</button>
                <br clear="all">
                <br clear="all">
                <span id="log_msg"></span>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

        </div>
    </div>
@endsection

@section('js')
<script>
function get_log()
  {
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
	var type = $("#type").val();
    if(start_date=='')
    {
        alert('Select start date!');
        return false;
    }
    if(end_date=='')
    {
        alert('Select end date!');
        return false;
    }
    location.href="<?php echo url('/') ?>/cpanel/company-payout/get-log?start_date="+start_date+"&end_date="+end_date+"&type="+type;
  }
</script>