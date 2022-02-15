@extends('layouts.admin')

@section('content_header')

<div class="row mb-2">

	<div class="col-sm-6">

	<h1>Order Management</h1>

	</div>

	<div class="col-sm-6">

	<ol class="breadcrumb float-sm-right">

		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>

		<li class="breadcrumb-item active">Order Management</li>

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

	<div>

	<form method="post" action="{{ route('datewiseOrderreport') }}" enctype="multipart/form-data">

        @csrf

			Order From: <input type="date" name="start_date" id="start_date" value="{{ $start_date ?? '' }}" required> Order To: <input type="date" name="end_date" id="end_date" value="{{ $end_date ?? '' }}" required>  

			<button type="submit" class="btn btn-primary">View</button>  <button type="button" class="btn btn-info" onclick="download_order_report()">Download Order Report</button></form>

			<a href="{{ route('order.index') }}" class="btn btn-warning">Clear</a>

              

            

			</div>



			

		<div class="card-body">

			<table class="table table-bordered table-sm" id="datatable">

				<thead>

					<tr class="text-center">

						<th>Order Number</th>

                        <th>Name</th>

                        <th>Amount</th>

                        <th>Swadesh Hut Name</th>

                        <th>Order Date</th>

                        <th>Order Status</th>

                        <th>Payment Mode & Status</th>

                         <th>Shipping Mode</th>

						<th>Created At</th>

						<th>Action</th>

					</tr>

				</thead>

				<tbody>

				@foreach ($records as $key => $product)



				<tr>

					<td>{{ $product->order_number }}</td>

                    <!--<td>{{ $product->customer_name }}</td>-->

                    <td>{{ $product->billing_name }}</td>

                    <td>{{ $product->total_amount }}</td>

                    <td>{{ $product->hut_name }}</td>

                    <td>{{ $product->order_date }}</td>

                    <td>

					<?php

						if($product->order_status=='Packed'):

							$product->order_status='<span class="badge badge-primary">Packed</span>';

						elseif($product->order_status=='Ordered'):

							$product->order_status='<span class="badge badge-success">Ordered</span>';

						elseif($product->order_status=='Delivered'):

							$product->order_status='<span class="badge badge-warning">Delivered</span>';

						else:

							$product->order_status='<span class="badge badge-secondary">NA</span>';

						endif;

						echo $product->order_status;

					?>

					</td>



					 <td>

					<?php

						if($product->shipping_type=='pick_up_from_store'):

							$product->shipping_type='pick up from store';

						elseif($product->shipping_type=='home_delivery'):

							$product->shipping_type='home delivery';						

						else:

							$product->shipping_type='';

						endif;

						echo $product->shipping_type;

					?>

					</td>





                    <td>{{ $product->payment_mode }} ({{ $product->payment_status }})</td>

                    <td class="text-center" data-sort="{{ date('d-m-Y',strtotime($product->created_at)) }}">{{ date('d-m-Y',strtotime($product->created_at)) }}</td>

					<td class="text-center">

						<a class="btn btn-primary btn-xs" href="{{ route('order.edit',$product->id) }}"  title="Edit"><i class="fas fa-edit"></i></a>

						<a class="btn btn-success btn-xs" href="{{ route('order.show',$product->id) }}"  title="show"><i class="fas fa-eye"></i></a>

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
function download_order_report()
{
	var start_date = $("#start_date").val();
	var end_date = $("#end_date").val();
	window.open('<?php echo url('/'); ?>/cpanel/order/datewiseOrderExcelDownload?start_date='+start_date+'&end_date='+end_date, '_blank');
}
</script>

<!--script>

  $(function() {

    $('.toggle-class').change(function() {

        var status = $(this).prop('checked') == true ? 'Active' : 'Inactive';

        var id = $(this).data('id');



        $.ajax({

            type: "GET",

            dataType: "json",

			url: "{{ url('cpanel/package_location/changestatus') }}",

            data: {'status': status, 'id': id},

            success: function(data){

              console.log(data.success)

            }

        });

    })

  })

</script-->

@endsection




