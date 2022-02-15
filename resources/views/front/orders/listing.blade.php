@extends('layouts.front')
@section('seo-header')
		<title>My Order Page</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
@endsection
@section('content')
<div class="container">
<div class="accountarea">
<div class="breadcrumb"><a href="{{ url('/') }}/home">Home</a>  >  <a href="{{ url('/') }}/my-order">Orders</a></div>
	<div id="sidebar">
			<ul class="sidenav nav-list">
				<li>
					<a href="{{ route('my-profile') }}">My Profile</a>
				</li>
				<li>
					<a href="{{ route('my-order') }}">My Order</a>
				</li>
				<li>
					<a href="{{ route('logout') }}">Logout</a>
				</li>
			</ul>
	</div>
	<div class="profilearea">
		<!--<ul class="breadcrumb">
			<li><a href="{{ route('sitelink') }}">Home</a> <span class="divider">/</span></li>
			<li class="active">My Order</li>
		</ul>-->
		<div class="profileform">
			<h3>Order Listing <!--small class="pull-right"> 2 Items are in the cart </small--></h3>
			
			<table class="table table-bordered table-condensed">
				<thead>
					<tr>
						<th>Order Number</th>
                        <!--th>User Name</th
						<th>Swadesh Hut Name</th>-->
                        <th>Amount</th>
                        <th>Order Date</th>
                        <th>Order Status</th>
                        <th>Payment Mode & Status</th>
						<th>Created At</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				@if(count($records)>0)
					@foreach ($records as $key => $product)
						<tr>
							<td>{{ $product->order_number }}</td>
							<!--<td>{{ $product->hut_name }}</td>-->
							<td>र {{ $product->total_amount }}</td>
							<!--td>{{ $product->customer_name }}</td-->
							<td>{{ $product->order_date }}</td>
							<td>
							<?php
								if($product->order_status=='Packed'):
									$product_order_status='<span class="badge badge-primary">Packed</span>';
								elseif($product->order_status=='Ordered'):
									$product_order_status='<span class="badge badge-success">Ordered</span>';
								elseif($product->order_status=='Delivered'):
									$product_order_status='<span class="badge badge-warning">Delivered</span>';
								elseif($product->order_status=='Cancelled'):
									$product_order_status='<span class="label label-danger">Cancelled</span>';
								elseif($product->order_status=='Returned'):
									$product_order_status='<span class="badge badge-info">Returned</span>';
								else:
									$product_order_status='<span class="badge badge-secondary">NA</span>';
								endif;
								echo $product_order_status;
							?>
							</td>
							<td>{{ $product->payment_mode }} ({{ $product->payment_status }})</td>
							<td class="text-center" data-sort="{{ date('d-m-Y',strtotime($product->created_at)) }}">{{ date('d-m-Y',strtotime($product->created_at)) }}</td>
							<td>
								<a class="btn btn-success btn-xs" href="{{ route('order-details',$product->id) }}"  title="show">View</a>

								@if($product->order_status=='Ordered' || $product->order_status=='Packed')
									<a class="btn btn-danger btn-xs" title="show" onclick="cancel_order({{ $product->id }})">Cancel</i></a>
								@endif

								@if($product->order_status=='Delivered')
								<a class="btn btn-info btn-xs" title="show" onclick="return_order({{ $product->id }})">Return</a>
								@endif
							</td>
						</tr>
					@endforeach
				@else
					
				@endif
			

				</tbody>
			</table>

		</div>
		<div class="pagination">
		{{ $records->links() }}
		</div>
	</div>
	</div></div>
@endsection
@section('css')
<link href="{{ asset('/css/bootstrap-toggle.min.css') }}" rel="stylesheet">
@endsection
@section('js')


<script src="{{ asset('/js/bootstrap-toggle.min.js') }}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
function cancel_order(order_id)
{
	if(confirm("Are You Sure To Cancel Order?"))
	{
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "{{ url('cancel_order') }}",
			data: {'order_id': order_id,'_token':'<?php echo csrf_token(); ?>'},
			success: function(data){
				swal("Success!", data.msg , "success");
				window.location.reload();
			}
		});
	}	
}


function return_order(order_id)
{
	if(confirm("Are You Sure To Return Order?"))
	{
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "{{ url('return_order') }}",
			data: {'order_id': order_id,'_token':'<?php echo csrf_token(); ?>'},
			success: function(data){
				swal("Success!", data.msg , "success");
				window.location.reload();
			}
		});
	}	
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
