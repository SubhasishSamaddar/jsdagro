@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Product Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
		<li class="breadcrumb-item active">Products</li>
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
			<li> {{ $message }} </li>
		</ul>
	</div>
	@endif
	<div class="card">
		<div class="card-header">
			<div class="pull-left">
				<a class="btn btn-info" href="<?php echo url('/') ?>/cpanel/products/changenameurl">Change Name Url</a>
	        </div>
	        <div class="pull-right">
			@can('product-create')
	            <!--a class="btn btn-primary" href="{{ route('import') }}"> Import Product</a-->
	            <a class="btn btn-warning" href="{{ route('export') }}"> Export Product</a>
				<a class="btn btn-primary" href="{{ route('expiryproductlog') }}"> Expiry Product Log</a>
	            @if(Auth::user()->user_type=='Swadesh_Hut')<a class="btn btn-info" href="{{ route('generatecriticalproductreport') }}">Critical Qty Product Report</a>@endif
			@endcan
			</div>
        </div>

		<div class="card-body">
		<input type="text" style="width:220px;float:right;" class="form-control form-control-sm" name="seatch" placeholder="Search..." id="search"><br clear="all">
			<table class="table table-bordered table-sm" id="datatable1">
				<thead>
					<tr class="text-center">
						<th>Name</th>
                        <th>Category</th>
                        <th>MRP</th>
                        <th>Selling Price</th>
                        <th>Available Quantity</th>
						<th>Weight</th>
						<th>Image</th>
						<th>Status</th>
						<th>Created At</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody id="data_container">
				@foreach ($products as $key => $product)
				<tr>
					<td>{{ $product->prod_name }}</td>
                    <td>{{ $product->category_name }}</td>
                    <td> र {{ $product->max_price }}</td>
                    <td id="tddiscount{{ $product->id }}"> र {{ $product->price }}</td>
					<!--<td id="tdstockalert{{ $product->id }}" ondblclick="showStockAlert( {{ $product->id }} )" >
						<span id="spanstockalert{{ $product->id }}">{{ $product->stock_alert }}</span>

						<input type="hidden" id="stock_alert{{ $product->id }}" value="{{ $product->stock_alert }}" onchange="updateStockAlert('{{ $product->id }}')" min="0" max="100" style="width: 70px;">

						a href="javascript:void(0)" id="inv_in_out_button1" data-toggle="modal" data-target="#myModal1" class="btn btn-xs btn-danger" onclick="set_product_stock_alert('{{ $product->id }}','{{ $product->stock_alert  }}')">Stock Alert </a>
                    </td>-->
					<td class="text-center">{{ (int)$product->available_qty }}</td>
					<td class="text-center">{{ $product->weight_per_pkt }} {{ $product->weight_unit }}</td>
					<td><img src="{{ asset('/storage/'.$product->product_image)}}" style="height:60px;" /></td>

					<td class="text-center"><input data-id="{{$product->id}}" class="toggle-class  btn-sm" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="Inactive" {{ ($product->status=="Active")? "checked" : "" }} data-size="xs"></td>
					<td class="text-center" data-sort="{{ date('d-m-Y',strtotime($product->created_at)) }}">{{ date('d-m-Y',strtotime($product->created_at)) }}</td>
					<td class="text-center">
					
						@can('product-edit')
						<a class="btn btn-success btn-xs" href="{{ route('products.edit',$product->id) }}"  title="Edit"><i class="fas fa-edit"></i></a>
						@endcan


						@if($user_type=='Swadesh_Hut')						
							<button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#returnModal" onclick="set_return_product_id('{{ $product->id }}')">Return Product</button>
							<input type="hidden" id="pavlbl_qty{{ $product->id }}" value="{{ $product->available_qty}}">
						@endif



					{{--
					@can('product-delete')
						<form method="post" action="{{ route('products.destroy',$product->id) }}" style='display:inline' >
        				@csrf
                  		@method('DELETE')
						<button type="submit"  onclick="return confirm('Are you sure to Delete the Product ?');" class="btn btn-danger"  title="Delete" ><i class="fas fa-trash"></i></button>
						</form>
						@endcan
					--}}
					<!--<a class="btn btn-primary btn-xs" href="{{ route('products.show',$product->id) }}"  title="Edit"><i class="fas fa-eye"></i></a>-->

					</td>  
				</tr>
				@endforeach
				</tbody>
			</table>
			<div class="pagination" style="float:right;">{{ $products->links() }}</div>
		</div>
	</div>

	 <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content xs">
            <div class="modal-header">
            <h4 class="modal-title">Set Discount Percentage</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="product_id" id="product_id">
                <div class="col-md-12">
                    <!--label for="description">Set Discount Percentage</label><br/-->
                    <input type="text" id="discount" name="discount" class="form-control"><br/>
                </div>

                <button type="button" class="btn btn-primary" onclick="product_discount()">Save</button>
                <span id="discount_msg"></span>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

        </div>
    </div>

	 <!-- Modal -->
    <div id="myModal1" class="modal fade" role="dialog">
        <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content xs">
            <div class="modal-header">
            <h4 class="modal-title">Set Stock Alert</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="sa_product_id" id="sa_product_id">
                <div class="col-md-12">
                    <!--label for="description">Set Discount Percentage</label><br/-->
                    <input type="text" id="stock_alert" name="stock_alert" class="form-control"><br/>
                </div>

                <button type="button" class="btn btn-primary" onclick="product_stock_alert()">Save</button>
                <span id="stock_alert_msg"></span>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

        </div>
    </div>



	 <!-- Modal -->
	 <div id="returnModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content xs">
            <div class="modal-header">
            <h4 class="modal-title">Set Return Qty</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="return_product_id" id="return_product_id">
                <div class="col-md-12">
                    <!--label for="description">Set Discount Percentage</label><br/-->
                    <input type="text" id="return_qty" name="return_qty" onkeyup="chk_qty()" class="form-control"><br/>
                </div>

                <button type="button" class="btn btn-primary" onclick="product_return()">Return</button>
                <span id="stock_alert_msg"></span>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

        </div>
    </div>

@endsection
@section('css')
<link href="{{ asset('/css/bootstrap-toggle.min.css') }}" rel="stylesheet">
@endsection
@section('js')
<script src="{{ asset('/js/bootstrap-toggle.min.js') }}"></script>
<script>
  $(document).ready(function() {
  	$('#datatable1').DataTable({
      'paging'      : false,
      'lengthChange': true,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true
    });



	$('#search').keyup(function () {
        var search_value = $("#search").val();
        if(search_value!=''){
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?php echo url('/') ?>/cpanel/product/product_search",
                data: {'search_value': search_value, '_token':'<?php echo csrf_token(); ?>'},
                success: function(data){
                    $('#data_container').html(data.table_html);
                    $(".pagination").html(data.pagination_html);
                }
            });
        }else {
            window.location.reload();
        }
    });





  });


  $(function() {
    $('.toggle-class').change(function() {
        var status = $(this).prop('checked') == true ? 'Active' : 'Inactive';
        var id = $(this).data('id');

        $.ajax({
            type: "GET",
            dataType: "json",
			url: "{{ url('cpanel/products/changestatus') }}",
            data: {'status': status, 'id': id},
            success: function(data){
              console.log(data.success)
            }
        });
    })
  })

  	function changeStatus(id,status)
	{
		$.ajax({
            type: "GET",
            dataType: "json",
			url: "{{ url('cpanel/products/changestatus') }}",
            data: {'status': status, 'id': id},
            success: function(data){
              console.log(data.success)
            }
        });
	}


	function show_search_data(page_no){
    var search_value = $("#search").val();
    if(search_value!=''){
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo url('/') ?>/cpanel/product/show_search_data",
            data: {'page_no': page_no,'search_value': search_value, '_token':'<?php echo csrf_token(); ?>'},
            success: function(data){
                $('#data_container').html(data.table_html);
                $(".pagination").html(data.pagination_html);
				$("[data-toggle='toggle']").bootstrapToggle();
            }
        });
    }else {
        window.location.reload();
    }
  }

	function set_product_discount(PRODUCT_ID,DISCOUNT){
		$("#product_id").val(PRODUCT_ID);
		$("#discount").val(DISCOUNT);
	}

	function product_discount(){
		var product_id=$("#product_id").val();
		var discount=$("#discount").val();
		$.ajax({
            type: "GET",
            dataType: "json",
			url: "{{ url('cpanel/products/changediscount') }}",
            data: {'id': product_id, 'discount': discount},
            success: function(data){
				$("#discount_msg").html(data.success);
				location.reload();
            }
        });
	}

	function set_product_stock_alert(PRODUCT_ID,DISCOUNT){
		$("#sa_product_id").val(PRODUCT_ID);
		$("#stock_alert").val(DISCOUNT);
	}

	function product_stock_alert(){
		var product_id=$("#sa_product_id").val();
		var stock_alert=$("#stock_alert").val();
		$.ajax({
            type: "GET",
            dataType: "json",
			url: "{{ url('cpanel/products/changestockalert') }}",
            data: {'id': product_id, 'stock_alert': stock_alert},
            success: function(data){
				$("#stock_alert_msg").html(data.success);
				location.reload();
            }
        });
	}

	function updateDiscount(PRODUCTID){
		//alert(PRODUCTID);
		var DISCOUNT=$("#discount"+PRODUCTID).val();
		//alert(DISCOUNT);
		$.ajax({
            type: "GET",
            dataType: "json",
			url: "{{ url('cpanel/products/changediscount') }}",
            data: {'id': PRODUCTID, 'discount': DISCOUNT},
            success: function(data){
				alert(data.success);
				location.reload();
            }
        });
	}

	function updateStockAlert(PRODUCTID){
		//alert(PRODUCTID);
		var STOCKALERT=$("#stock_alert"+PRODUCTID).val();
		//alert(STOCKALERT);

			$.ajax({
				type: "GET",
				dataType: "json",
				url: "{{ url('cpanel/products/changestockalert') }}",
				data: {'id': PRODUCTID, 'stock_alert': STOCKALERT},
				success: function(data){
					//$("#stock_alert_msg").html(data.success);
					alert(data.success);
					location.reload();
				}
			});

	}

	function showDiscount(PRODUCTID){
		//alert("PRODUCTID");
		$('#discount'+PRODUCTID).attr('type', 'text');
		$('#spandiscount'+PRODUCTID).hide();
	}
	function showStockAlert(PRODUCTID){
		//alert("PRODUCTID");
		$('#stock_alert'+PRODUCTID).attr('type', 'text');
		$('#spanstockalert'+PRODUCTID).hide();
	}

	function set_return_product_id(pid)
	{
		$('#return_product_id').val(pid);
	}

	function chk_qty()
	{
		var return_product_id = $('#return_product_id').val();
		var avlbl_qty = $("#pavlbl_qty"+return_product_id).val();
		var return_qty = $("#return_qty").val();

		if(Number(return_qty)>Number(avlbl_qty))
		{
			alert("Sorry!this much stock not available for this product");
			$("#return_qty").val('');
			return false;
		}
	}

	function product_return()
	{
		if(confirm("Are You Sure To Return Product?"))
		{
			var return_product_id = $('#return_product_id').val();
			var return_qty = $("#return_qty").val();
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "{{ url('cpanel/products/returnproduct') }}",
				data: {'id': return_product_id,'return_qty':return_qty,'_token':'<?php echo csrf_token(); ?>'},
				success: function(data){
					//$("#stock_alert_msg").html(data.success);
					alert(data.success);
					location.reload();
				}
			});
		}
	}
</script>
@endsection
