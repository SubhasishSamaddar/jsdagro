@extends('layouts.front')

@section('seo-header')
		<title>Product Page</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content=""-->
@endsection		
@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 my-3">
            <div class="pull-right">
                <div class="btn-group">
                   Product Listing
                </div>
            </div>
        </div>
    </div> 
    <div id="products" class="row view-group">
        @foreach($products as $data)
			<div class="item col-xs-4 col-lg-4">
				<div class="thumbnail card">
					<div class="img-event">
						<img class="group list-group-image img-fluid" src="{{ asset('/storage/'.$data->product_image)}}" alt="" />
					</div>
					<div class="caption card-body">
					<h4 class="group card-title inner list-group-item-heading">{{ $data->prod_name}}</h4>
					<p class="group inner list-group-item-text">
						{{ $data->description }}
					</p>
						<div class="row">
							<div class="col-xs-12 col-md-6">
								<p class="lead">${{ $data->price }}</p>
							</div>
							<div class="col-xs-12 col-md-6">
								<a class="btn btn-success" href="{{ route('products',$data->id) }}">Product Details</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endforeach
	
	</div>
</div>
@endsection
@section('css')
<link href="{{ asset('/css/bootstrap-toggle.min.css') }}" rel="stylesheet">
@endsection
@section('js')
<script src="{{ asset('/js/bootstrap-toggle.min.js') }}"></script>
<script>
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
</script>
@endsection



