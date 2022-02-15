@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Returned Products</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
		<li class="breadcrumb-item active">Returned Products</li>
	</ol>
	</div>
</div>
@endsection
@section('content')
	<div class="card">
		<div class="card-header">
			
        </div>

		<div class="card-body">
			<table class="table table-bordered table-sm" id="datatable">
				<thead>
					<tr class="text-center">
						<th>Product Name</th>
                        <th>Returned Quantity</th>
                        <?php if(Auth::user()->user_type!='Swadesh_Hut') { ?><th>Swadesh Hut</th><?php } ?>
                        <th>Product SKU</th>
						<th>Returned At</th>
					</tr>
				</thead>
				<tbody id="data_container">
				@foreach ($products as $key => $product)
				<tr>
					<td>{{ $product->product_name }}</td>
                    <td>{{ $product->return_quantity }}</td>
                    <?php if(Auth::user()->user_type!='Swadesh_Hut') { ?><td>{{$product->location_name}}</td><?php } ?>
                    <td>{{ $product->product_sku }}</td>
					<td class="text-center" data-sort="{{ date('d-m-Y',strtotime($product->created_at)) }}">{{ date('d-m-Y',strtotime($product->created_at)) }}</td>
				</tr>
				@endforeach
				</tbody>
			</table>
			
		</div>
	</div>
@endsection