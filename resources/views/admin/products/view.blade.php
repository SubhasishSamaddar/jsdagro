@extends('layouts.admin')
@section('content_header')
<?php //echo '<pre>';
//print_r($product_inventory);?>
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Product Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
		<li class="breadcrumb-item active">Details</li>
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
    @if ($errors->any())
	<div class="alert alert-danger">
		<strong>Whoops!</strong> There were some problems with your input.<br><br>
		<ul>
		@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
		</ul>
	</div>
	@endif
	<div class="card">
		<div class="card-header">
			<div class="pull-left">
	            <h4>Edit Product</h4>
	        </div>
        </div>

		<div class="card-body">
        <form method="post" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
         <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <img src="{{ asset('/storage/'.$product->product_image)}}" style="height:60px;" />
              
                </div>
            </div>
			<div class="col-md-6">
                <div class="form-group">
					<label for="prod_name">Name : {{$product->prod_name}}</label></br>
                    <label for="prod_name">Swadesh Hut : {{$product->swadesh_hut}}</label></br>
					<label for="prod_name">Category : {{$product->category_name}}</label>
                </div>
           
            </div>
          
			
			<div class="col-md-12">
                <div class="form-group">
                    <label for="prod_name">Description : </label>
					<p>{{$product->description}}</p>
                </div>
            </div>
			
			<div class="col-md-12">
                <div class="form-group">
                    <label for="prod_name">Specification  : </label>
					<p>{{$product->specification }}</p>
                </div>
            </div>
			
			<div class="col-md-6">
                <div class="form-group">
                    <label for="prod_name">SKU : {{$product->sku}}</label>
                </div>
            </div>
			
			
			<div class="col-md-6">
                <div class="form-group">
                    <label for="prod_name">Packet Weight : {{$product->weight_per_pkt}}{{$product->weight_unit}}</label>
                </div>
            </div>
			
			<div class="col-md-6">
                <div class="form-group">
                    <label for="prod_name">Price :  र {{$product->price}}</label></br>
					<label for="prod_name">CGST : {{$product->cgst}}</label></br>
                    <label for="prod_name">SGST : {{$product->sgst}}</label></br>
                    <label for="prod_name">IGST : {{$product->igst}}</label>
                </div>
            </div>
			
			
			
			
			<div class="col-md-6">
                <div class="form-group">
                    <label for="prod_name">Available : {{$product->available_qty}}</label></br>
					<label for="prod_name">Ordered : {{$product->ordered_qty}}</label></br>
					<label for="prod_name">Stock Alert : {{$product->stock_alert}}</label>
                </div>
            </div>
           
			<div class="col-md-6">
                <div class="form-group">
                    <label for="prod_name">HSN : {{$product->hsn}}</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="prod_name">Manufacturer Details : {{$product->manufacturer_details}}</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="prod_name">Marketed By : {{$product->marketed_by}}</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="prod_name">Country Of Origin : {{$product->country_of_origin}}</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="prod_name">Customer Care Details : {{$product->customer_care_details}}</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="prod_name">Seller : {{$product->seller}}</label>
                </div>
            </div>
			<div class="col-md-6">
                <div class="form-group">
                    <label for="prod_name">Bar Code : {{$product->barcode}}</label>
                </div>
            </div>
			
            
		</div>
        </form>
	</div>
@endsection
@section('css')
<link href="{{ asset('/css/bootstrap-toggle.min.css') }}" rel="stylesheet">
@endsection
@section('js')
<script src="{{ asset('/js/bootstrap-toggle.min.js') }}"></script>
<script src="<?php echo url('/') ?>/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
<script src="<?php echo url('/') ?>/vendor/unisharp/laravel-ckeditor/adapters/jquery.js"></script>
<script>
    CKEDITOR.replace( 'description' );
    CKEDITOR.replace( 'specification' );
</script>
@endsection
