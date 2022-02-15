@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
    <h1>Voucher Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
		<li class="breadcrumb-item active">Voucher</li>
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
        </div>

		<div class="card-body">
			<table class="table table-bordered table-sm" id="datatable1">
				<thead>
					<tr class="text-center">
                        <th>Voucher No</th>
						<th>Total Product No</th>
                        <th>Total Price</th>
                        <th>Store</th>
                        <th>Out Quantitiy</th>
                        <th>Voucher date</th>
                        <th>Status</th>
                        
					</tr>
				</thead>
				<tbody>
                @php 
				@foreach ($records as $key => $inventory)
				<tr>
                    <td><a href="javascript:void(0)" data-toggle="modal" data-target="#detailsModal" onclick="show_details('{{ $inventory->voucher_no }}')"><strong style="color:red;">{{ $inventory->voucher_no }}</strong></a></td>
                    <td class="text-center">{{ $inventory->totalproduct }}</td>
                    <td class="text-center">र{{ $inventory->totalprice }}</td>
                    <td class="text-center">{{ $inventory->location_name }}</td>
                    <td class="text-center">{{ $inventory->totalquantity }}</td>
                    <td class="text-center" style="color:blue;"><strong>{{ date('Y-m-d',strtotime($inventory->voucher_date)) }}</strong></td>
                    @if($inventory->voucher_status=='inprocess')
					<td class="text-center"><a class="btn btn-sm btn-warning" href="javascript:void(0)"  <?php if($user_type=='swadeshhutuser') { ?> onclick="change_voucher_status('{{ $inventory->voucher_no }}')" <?php } ?>>{{ $inventory->voucher_status }}</a>|<a class="btn btn-sm btn-info" href="{{url('/')}}/cpanel/package_inventory/voucher-details/{{ $inventory->voucher_no }}" target="_blank"><strong>Print Voucher</strong></a></td>
                    @elseif($inventory->voucher_status=='complete')
                    <td class="text-center"><a class="btn btn-sm btn-success" href="javascript:void(0)">{{ $inventory->voucher_status }}</a>|<a class="btn btn-sm btn-info" href="{{url('/')}}/cpanel/package_inventory/voucher-details/{{ $inventory->voucher_no }}" target="_blank"><strong>Print Voucher</strong></a></td>
                    @endif
				</tr>
				@endforeach
				</tbody>
			</table>
		</div>
    </div>

    <!-- Modal -->
    <div id="detailsModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title">Voucher Details</h4>
            </div>
            <div class="modal-body details-container">
                
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

<script>

$(document).ready(function() {
  $('#datatable1').DataTable({
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
<script src="{{ asset('/js/bootstrap-toggle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    function change_voucher_status(voucher)
    {
        if(confirm("Are You Sure To Change The Status To Complete?"))
        {
        $.ajax({
            type: "POST",
            dataType: "json",
			url: "{{ url('cpanel/voucher/changestatus') }}",
            data: {'voucher': voucher, '_token':'<?php echo csrf_token() ?>'},
            success: function(data){
              alert(data.msg);
              location.reload();
            }
        });
        }
    }

    function show_details(voucher)
    {
        $.ajax({
            type: "POST",
            dataType: "json",
			url: "{{ url('cpanel/voucher/showdetails') }}",
            data: {'voucher': voucher, '_token':'<?php echo csrf_token() ?>'},
            success: function(data){
              $(".details-container").html(data.html);
            }
        });
    }

</script>
@endsection
