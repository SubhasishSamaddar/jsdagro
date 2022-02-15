@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Stock In Details</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
		<li class="breadcrumb-item active">Stock In Details</li>
	</ol>
	</div>
</div>
@endsection
@section('content')
	
	<div class="card">
		<div class="card-header">
			<div class="pull-left">

	        </div>
	        <div class="pull-right">
			
			</div>
        </div>

		<div class="card-body">
			<table class="table table-bordered table-sm" id="datatable2">
				<thead>
					<tr class="text-center">
						<th>Product</th>
						<th>Weight/Unit</th>
                        <th>Available Stock</th>
						<th>Stock In Qty</th>
                        <th>Stock In By</th>
						<th>Stock In Date</th>
					</tr>
				</thead>
				<tbody>
				@foreach ($result as $key => $ins)
				<tr>
					<td>{{ $ins->prod_name }}</td>
					<td>{{ $ins->weight_per_packet.$ins->weight_unit }}</td>
                    <td>{{ 'Available :'.(int)$ins->available_qty }}</td>
					<td>{{ $ins->in_quantity }}</td>
                    <td>{{ $ins->name }}</td>
					<td class="text-center">{{ date('Y-m-d',strtotime($ins->stock_in_date)) }}</td>
				</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection


@section('js')
<script>
$(document).ready(function() {
  $('#datatable2').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true,
      'order'       : [[ 5, "desc" ]]
    });
});
</script>
@endsection