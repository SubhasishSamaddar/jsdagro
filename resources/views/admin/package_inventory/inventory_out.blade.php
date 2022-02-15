@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Stock Out Details</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
		<li class="breadcrumb-item active">Stock Out Details</li>
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
						<th>Stock Out Quantity</th>
                        <th>Stock Out User</th>
                        <th>Store</th>
						<th>Stock Out Date</th>
					</tr>
				</thead>
				<tbody>
				@foreach ($result as $key => $ins)
				<tr>
					<td>{{ $ins->prod_name }}</td>
					<td>{{ $ins->weight_per_packet.$ins->weight_unit }}</td>
                    <td>{{ 'Available :'.(int)$ins->available_qty }}</td>
                    <td>{{ $ins->inv_out_qty }}</td>
                    <td>{{ $ins->name }}</td>
                    <td>{{ $ins->swadesh_hut }}</td>
					<td class="text-center">{{ $ins->stock_out_date }}</td>
				</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection


@section('js')
<script>
$('#datatable2').DataTable({
	"order": [[ 6, "desc" ]],
	"destroy" : true,
});
</script>
@endsection