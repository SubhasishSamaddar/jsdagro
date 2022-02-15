@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
    <h1>Package Inventory Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
		<li class="breadcrumb-item active">Inventory</li>
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
	        <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('piimport') }}">Import Inventory</a>
                <!--<a class="btn btn-warning" href="{{ route('piexport') }}">Export Inventory</a>-->
                <a class="btn btn-success" href="{{ route('piexport') }}" target="_blank">Stock Out Log</a>
                <a class="btn btn-warning" href="{{ route('stockinlogexport') }}" target="_blank">Stock In Log</a>
                <a class="btn btn-info" href="javascript:void(0)" data-toggle="modal" data-target="#bulkOutModal">Bulk Inventory Out</a>
                @can('package_inventory-create')
	            <a class="btn btn-success" href="{{ route('package_inventory.create') }}"> Create New Inventory</a>
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
                        <th>Location</th>
                        <th>MRP(INR)</th>
                        <th>Selling Price</th>
                        <th>Image</th>
                        <th>Weight/Pkt</th>
                        <th style="display:none;">Barcode</th>
                        <th>Quantity</th>
                        <th>Inventory Out</th>
						<th>Created At</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody id="data_container">
				@foreach ($records as $key => $inventory)
				<tr>
                    <td>{{ $inventory->prod_name }}</td>
                    <td>
                        @php 
                            $get_category_details = DB::table('categories')->where('id',$inventory->category_id)->first();
                            if(isset($get_category_details->name) && $get_category_details->name!='')
                            {
                                echo $get_category_details->name;
                            }
                            else 
                            {
                                echo 'N/A';
                            }
                        @endphp
                    </td>
                    <td>
                        @php 
                            $get_location_details = DB::table('package_locations')->where('id',$inventory->package_location_id)->first();
                            if(isset($get_location_details->location_name) && $get_location_details->location_name!='')
                            {
                                echo $get_location_details->location_name;
                            }
                            else 
                            {
                                echo 'N/A';
                            }
                        @endphp
                    </td>
                    <td>{{ $inventory->mrp }}</td>
                    <td>{{ $inventory->selling_price }}</td>
                    <td><img src="{{ asset('/storage/'.$inventory->product_image)}}" style="height:60px;" /></td>
                    <td>{{ $inventory->weight_per_packet }} {{ $inventory->weight_unit }}</td>
                    <td style="display:none;">{{ $inventory->barcode }}</td>
                    <td class="text-center">
                        @php
                        $total_out_qty=0;
                        $get_package_inventory_out_details = Helper::get_package_inventory_out_details($inventory->id);
                        if(count($get_package_inventory_out_details)>0)
                        {
                            foreach($get_package_inventory_out_details as $pidetails)
                            {
                                $total_out_qty+=$pidetails->inv_out_qty;
                            }
                        }
                        $available_qty = $inventory->available_qty;
                        @endphp
                        <table>
                            <tr><td><strong>Out Quantity</strong></td><td><strong>Available Quantity</strong></td></tr>
                            <tr><td id="tbl_out_qty{{ $inventory->id }}">{{$total_out_qty}}</td><td id="tbl_avl_qty{{ $inventory->id }}"><input type="hidden" id="pavlqty{{ $inventory->id }}" value="{{$available_qty}}">{{$available_qty}}</td></tr>
                        </table>
                    </td>
                    <td class="text-center"><!--<input data-id="{{$inventory->id}}" class="toggle-class  btn-sm" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="In" data-off="Out" {{ ($inventory->inventory_in_out=="In")? "checked" : "" }} data-size="xs">-->
                    <a href="javascript:void(0)" id="inv_in_out_button" data-toggle="modal" data-target="#myModal" class="btn btn-xs btn-warning" onclick="set_package_inventory('{{ $inventory->id }}','{{ $inventory->package_location_id }}','{{ $inventory->weight_unit }}','{{ $inventory->weight_per_packet }}')">Set Inventory Out</a>

                    <button class="btn btn-xs btn-info"  data-toggle="modal" data-target="#increaseStockModal" onclick="increase_stock('{{ $inventory->id }}','{{ $inventory->package_location_id }}','{{ $inventory->weight_unit }}','{{ $inventory->weight_per_packet }}')">Increase Stock</button>
                    </td>
					<td class="text-center" data-sort="{{ date('d-m-Y',strtotime($inventory->created_at)) }}">{{ date('d-m-Y',strtotime($inventory->created_at)) }}</td>
					<td class="text-center">
                        @can('package_inventory-edit')
                        <a class="btn btn-primary" href="{{ route('package_inventory.edit',$inventory->id) }}"  title="Edit"><i class="fas fa-edit"></i></a>
                        @endcan
                        @can('package_inventory-delete')
						<form method="post" action="{{ route('package_inventory.destroy',$inventory->id) }}" style='display:inline' >
        				@csrf
                  		@method('DELETE')
						<button type="submit"  onclick="return confirm('Are you sure to Delete the Inventory ?');" class="btn btn-danger"  title="Delete" ><i class="fas fa-trash"></i></button>
                        </form>
                        @endcan
					</td>
				</tr>
				@endforeach
				</tbody>
			</table>
            <div class="pagination" style="float:right;">{{ $records->links() }}</div>
		</div>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title">Inventory Out</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="package_inventory_id" id="package_inventory_id">
                <input type="hidden" name="package_location_id" id="package_location_id">
                <div class="col-md-12">
                    <label for="description">Inventory Out packet no.</label><br/>
                    <input type="text" id="inv_out_qty" name="inv_out_qty" class="form-control" value=""  onkeyup="get_stock_update()">
                    <span id="stock_error" style="color: red;"></span>
                    <input type="hidden" id="stock_msg_error">
                </div>
                <div class="col-md-12">
                    <label for="description">Weight per packet.</label><br/>
                    <input type="text" id="wt_per_pkt" name="inv_out_qty" class="form-control" readonly value=""><br/>
                </div>
                <div class="col-md-12">
                    <label for="description">Weight Unit.</label><br/>
                    <input type="text" id="wt_unit" name="wt_unit" class="form-control" readonly value=""><br/>
                </div>
                <div class="col-md-12">
                    <label for="description">Swadesh Hut</label><br/>
                    <select name="swadesh_hut_id" class="form-control" id="swadesh_hut_id">
                        <option value="">Select</option>
                        @if(count($swadesh_huts)>0)
                        @foreach($swadesh_huts as $huts)
                            <option value="{{ $huts->id }}">{{ $huts->location_name }}</option>
                        @endforeach
                        @endif
                    </select>
                    <br/>
                </div>
                <button type="button" class="btn btn-primary" onclick="inventory_out_process()">Save</button>
                <br clear="all">
                <br clear="all">
                <span id="inv_out_msg"></span>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

        </div>
    </div>


    <!-- Modal -->
    <div id="increaseStockModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title">Inventory Stock In</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="package_increase_stock_inventory_id" id="package_increase_stock_inventory_id">
                <input type="hidden" name="package_increase_stock_location_id" id="package_increase_stock_location_id">
                <div class="col-md-12">
                    <label for="description">Stock In Quantity.</label><br/>
                    <input type="text" id="stock_in_qty" name="stock_in_qty" class="form-control">
                </div>           
                <br clear="all">     
                <button type="button" class="btn btn-primary" onclick="inventory_stock_in_process()">Save</button>
                <br clear="all">
                <br clear="all">
                <span id="stock_in_msg"></span>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

        </div>
    </div>


    <!--Bulk Out Modal-->
    <div id="bulkOutModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title">Bulk Inventory Stock Out</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6" id="bulkpd">
                        <select name="bulk_pi_id[]" id="bulk_pi_id" multiple required onchange="get_products_to_stockout()">
                            <option value="">Select</option>
                            @foreach ($records as $key => $inventory)
                                <option value="{{ $inventory->id }}">{{ $inventory->prod_name.' '.$inventory->weight_per_packet.$inventory->weight_unit }}</option>
                            @endforeach
                        </select>
                    </div>
                
                    <div class="col-md-3">
                        <select name="bulk_swadesh_hut_id" class="form-control" id="bulk_swadesh_hut_id">
                            <option value="">Select Store</option>
                            @if(count($swadesh_huts)>0)
                            @foreach($swadesh_huts as $huts)
                                <option value="{{ $huts->id }}">{{ $huts->location_name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>  

                    <div class="col-md-3">
                        <select name="bulk_location_id" class="form-control" id="bulk_location_id">
                            <option value="">Select Package Location</option>
                            @if(count($package_locations)>0)
                            @foreach($package_locations as $location)
                                <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>  

                    <div class="col-md-12" id="bulk-pi-container" style="margin-top:20px;padding:50px;display:none;"></div>
                </div>
                <button type="button" class="btn btn-primary" onclick="submit_bulk_oyt_qty()">Save</button>
                <br clear="all">
                <br clear="all">
                <span id="bulk_stock_out_msg"></span>
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.bigdrop {
    width: 600px !important;
}
</style>
@endsection
@section('js')
<script src="{{ asset('/js/bootstrap-toggle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#datatable1').DataTable({
        'paging'      : false,
        'lengthChange': true,
        'searching'   : false,
        'ordering'    : true,
        'info'        : false,
        'autoWidth'   : true
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

    $('#bulk_pi_id').select2({
        closeOnSelect : true,
        dropdownCssClass : 'bigdrop',
        dropdownAutoWidth : true,
        placeholder : "Select",
        allowHtml: true,
        allowClear: true,
        tags: true // создает новые опции на лету
    });

    $('#search').keyup(function () {
        var search_value = $("#search").val();
        if(search_value!=''){
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?php echo url('/') ?>/cpanel/package_inventory/inventory_search",
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

  })


  function show_search_data(page_no){
    var search_value = $("#search").val();
    if(search_value!=''){
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo url('/') ?>/cpanel/package_inventory/show_search_data",
            data: {'page_no': page_no,'search_value': search_value, '_token':'<?php echo csrf_token(); ?>'},
            success: function(data){
                $('#data_container').html(data.table_html);
                $(".pagination").html(data.pagination_html);
            }
        });
    }else {
        window.location.reload();
    }
  }


  function set_package_inventory(package_inventory_id,package_location_id,weight_unit,weight_per_pkt)
  {
        $("#package_inventory_id").val(package_inventory_id);
        $("#package_location_id").val(package_location_id);

        $("#wt_per_pkt").val(weight_per_pkt);
        $("#wt_unit").val(weight_unit);
  }


  function increase_stock(package_inventory_id,package_location_id,weight_unit,weight_per_pkt)
  {
        $("#package_increase_stock_inventory_id").val(package_inventory_id);
        $("#package_increase_stock_location_id").val(package_location_id);
  }

  /*function inventory_out_process()
  {
      
        var swadesh_hut_id = $("#swadesh_hut_id").val();
        var inv_out_qty = $("#inv_out_qty").val();
        var package_inventory_id = $("#package_inventory_id").val();
        var package_location_id = $("#package_location_id").val();


        if(swadesh_hut_id=='')
        {
            alert('Please Choose Swadesh Hut');
            $('#swadesh_hut_id').focus().select()
            return false;
        }
        if(inv_out_qty=='')
        {
            alert('Please Enter Inventory Out Quantity');
            $("#inv_out_qty").focus();
            return false;
        }

        var stock_msg_error = $("#stock_msg_error").val();
        if(stock_msg_error==1)
        {
            //alert(stock_msg_error);
            alert("Please input available quantity for inventory out");
            return false;
        }

        $.ajax({
            type: "POST",
            dataType: "json",
			url: "<?php echo url('/') ?>/cpanel/package_inventory/inventory_out_process",
            data: {'swadesh_hut_id': swadesh_hut_id, 'inv_out_qty': inv_out_qty, 'package_inventory_id':package_inventory_id, 'package_location_id':package_location_id, '_token':'<?php echo csrf_token(); ?>'},
            success: function(data){
              $("#inv_out_msg").html(data.msg);
              if(data.error==0)
              {
                  $("#swadesh_hut_id").val('');
                  $("#inv_out_qty").val('');
                  $("#package_inventory_id").val('');
                  $("#package_location_id").val('');
                  
                  $("tbl_out_qty"+package_inventory_id).html(data.total_out_qty);
                  $("tbl_avl_qty"+package_inventory_id).html(data.available_qty);
                  $("pavlqty"+package_inventory_id).val(data.available_qty);
              }
            }
        });
  }*/


  function get_stock_update()
  {
    var inv_out_qty = $("#inv_out_qty").val();
    var package_inventory_id = $("#package_inventory_id").val();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo url('/') ?>/cpanel/package_inventory/inventory_stock_check",
        data: {'inv_out_qty': inv_out_qty, 'package_inventory_id':package_inventory_id, '_token':'<?php echo csrf_token(); ?>'},
        success: function(data){
            $("#stock_error").html(data.msg);
            $("#stock_msg_error").val(data.error);
        }
    });
  }


  function inventory_out_process()
  {
        var swadesh_hut_id = $("#swadesh_hut_id").val();
        var inv_out_qty = $("#inv_out_qty").val();
        var package_inventory_id = $("#package_inventory_id").val();
        
        var package_location_id = $("#package_location_id").val();


        if(swadesh_hut_id=='')
        {
            alert('Please Choose Swadesh Hut');
            $('#swadesh_hut_id').focus().select()
            return false;
        }
        if(inv_out_qty=='')
        {
            alert('Please Enter Inventory Out Quantity');
            $("#inv_out_qty").focus();
            return false;
        }

        var stock_msg_error = $("#stock_msg_error").val();
        if(stock_msg_error==1)
        {
            //alert(stock_msg_error);
            alert("Please input available quantity for inventory out");
            return false;
        }

        $.ajax({
            type: "POST",
            dataType: "json",
			url: "<?php echo url('/') ?>/cpanel/package_inventory/inventory_out_process",
            data: {'swadesh_hut_id': swadesh_hut_id, 'inv_out_qty': inv_out_qty, 'package_inventory_id':package_inventory_id, 'package_location_id':package_location_id, '_token':'<?php echo csrf_token(); ?>'},
            success: function(data){
              $("#inv_out_msg").html(data.msg);
              if(data.error==0)
              {
                  $("#swadesh_hut_id").val('');
                  $("#inv_out_qty").val('');
                  $("#package_inventory_id").val('');
                  $("#package_location_id").val('');
                  $("#tbl_out_qty"+package_inventory_id).html(data.total_out_qty);
                  $("#tbl_avl_qty"+package_inventory_id).html(data.available_qty);
                  $("#pavlqty"+package_inventory_id).val(data.available_qty);
                  $("#myModal").modal('hide');
              }
            }
        });
  }



  function inventory_stock_in_process()
  {
        var stock_in_qty = $("#stock_in_qty").val();
        var package_increase_stock_inventory_id = $("#package_increase_stock_inventory_id").val();
        var package_increase_stock_location_id = $("#package_increase_stock_location_id").val();

        if(stock_in_qty=='')
        {
            alert('Please Enter Stock In Quantity');
            $("#stock_in_qty").focus();
            return false;
        }


        $.ajax({
            type: "POST",
            dataType: "json",
			url: "<?php echo url('/') ?>/cpanel/package_inventory/stock_in_process",
            data: {'stock_in_qty': stock_in_qty,'package_increase_stock_inventory_id':package_increase_stock_inventory_id, 'package_increase_stock_location_id':package_increase_stock_location_id, '_token':'<?php echo csrf_token(); ?>'},
            success: function(data){
              $("#stock_in_msg").html(data.msg);
              if(data.error==0)
              {
                $("#stock_in_qty").val('');
                $("#package_increase_stock_inventory_id").val('');
                $("#package_increase_stock_location_id").val('');                 
                location.reload();                
              }
              location.reload();
            }
        });
  }



  function get_products_to_stockout()
  {
    $("#bulk-pi-container").show();
    var bulk_pi_id = $("#bulk_pi_id").val();
    var tbl_html = '<table class="table table-striped">';
    $('div#bulkpd select> option:selected').each(function() {
        tbl_html+='<tr><td>'+$(this).text()+'</td><td><input type="text" class="form-control" name="bulk_out_qty[]" id="bulk_out_qty'+$(this).val()+'" onkeyup="chk_qty(\''+($(this).val())+'\')"><input type="hidden" class="form-control" name="bulk_out_id[]" id="bulk_out_id'+$(this).val()+'" value="'+$(this).val()+'"></td></tr>';
    });
    tbl_html+='</table>';
    $("#bulk-pi-container").html(tbl_html);

    
  }

  function chk_qty(id)
  {
      var avl_qty = $("#pavlqty"+id).val();
      type_qty = $("#bulk_out_qty"+id).val();
      if(Number(avl_qty)<Number(type_qty))
      {
          alert("Sorry!this much stock not available for this product");
          $("#bulk_out_qty"+id).val('');
          return false;
      }
  }


  function submit_bulk_oyt_qty()
  {
    var bulk_out_qty = $("input[name='bulk_out_qty[]']").map(function(){return $(this).val();}).get();
    var bulk_out_id = $("input[name='bulk_out_id[]']").map(function(){return $(this).val();}).get();
    var bulk_swadesh_hut_id = $("#bulk_swadesh_hut_id").val();
    var bulk_location_id = $("#bulk_location_id").val();

    if(bulk_swadesh_hut_id=='')
    {
        alert('Select Swadesh Hut!');
        return false;
    }
    if(bulk_location_id=='')
    {
        alert('Select Package Location!');
        return false;
    }
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo url('/') ?>/cpanel/package_inventory/bulk_stock_out_process",
        data: {'bulk_out_qty': bulk_out_qty,'bulk_out_id':bulk_out_id,'bulk_swadesh_hut_id':bulk_swadesh_hut_id,'_token':'<?php echo csrf_token(); ?>','bulk_location_id':bulk_location_id},
        success: function(data){
            $("#bulk_stock_out_msg").html(data.msg);
            location.reload();
        }
    });
  }
</script>
@endsection
