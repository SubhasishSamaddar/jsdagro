@extends('layouts.admin')

@section('content_header')

<div class="row mb-2">

	<div class="col-sm-6">

	<h1>Package Inventory Management</h1>

	</div>

	<div class="col-sm-6">

	<ol class="breadcrumb float-sm-right">

		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>

        <li class="breadcrumb-item"><a href="{{ route('package_inventory.index') }}">Inventory</a></li>

		<li class="breadcrumb-item active">Edit</li>

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

	            <h4>Edit Inventory</h4>

	        </div>

        </div>



		<div class="card-body">

        <form method="post" action="{{ route('package_inventory.update', $inventory->id) }}" enctype="multipart/form-data">

        @csrf

        @method('PATCH')

        <div class="row">

            <div class="col-md-6">

                <div class="form-group">

                    <label for="prod_name">Name</label>

                    <input type="text" class="form-control" name="prod_name" id="prod_name"  value="{{$inventory->prod_name}}" required/>

                </div>

                <div class="form-group">

                    <label for="category_id">Category</label>

                    <select name="category_id" id="category_id" class="form-control" required>

                        <option value="0">Select Category</option>

                        @foreach($categories as $value)

						<option value="{{ $value->id}}" {{ ($value->id==$inventory->category_id)?'selected':''}}>{{ $value->name}}</option>

                        @endforeach

                    </select>

                </div>



                <div class="form-group">

					<div class="btn btn-default btn-file">

						<i class="fas fa-paperclip"></i> Product Image

						<input type="file" id="product_image" name="product_image">

                  	</div>

                </div>

            </div>

            <div class="col-md-6">

                <div class="form-group">

                    <label for="price">Purchase Price</label>

                    <input type="text" class="form-control" name="purchased_price" id="purchased_price"  value="{{$inventory->purchased_price}}" required autocomplete="off"/>

                </div>


                <div class="form-group">

                    <label for="price">Retail Price</label>

                    <input type="text" class="form-control" name="mrp" id="mrp"  value="{{$inventory->mrp}}" required autocomplete="off"/>

                </div>



                <div class="form-group">

                    <label for="price">Selling Price</label>

                    <input type="text" class="form-control" name="selling_price" id="selling_price"  value="{{$inventory->selling_price}}" required autocomplete="off"/>

                </div>

                <div class="form-group">

					<img src="{{ asset('/storage/'.$inventory->product_image)}}" style="height:60px;" />

                </div>

            </div>



            <div class="col-md-6">

                <div class="form-group">

					<label for="status">Inventory In Out</label><br/>

					<input name="inventory_in_out" id="inventory_in_out" data-id="0" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="In" data-off="Out" checked>

				</div>

            </div>



            <div class="col-md-6">

                <div class="form-group">

                    <label for="status">SKU</label><br/>

                    <!--{{$inventory->sku}}-->

                    <input type="text" class="form-control" name="sku" id="sku"  value="{{$inventory->sku}}" required autocomplete="off"/>

				</div>

            </div>



            <div class="col-md-6" style="display:none;">

                <label for="category_id">Package Location</label>

                <select name="package_location_id" id="package_location_id" class="form-control" required>

                    <option value="0">Select Package Location</option>

                    @foreach($package_location as $value)

                    <option value="{{ $value->id}}" {{ $value->id==$inventory->package_location_id ? 'selected':''}}>{{ $value->location_name}}</option>

                    @endforeach

                </select>

            </div>







            <div class="col-md-4">

                <label for="description">Weight per packet</label><br/>

				<input type="text" id="weight_per_packet" name="weight_per_packet" class="form-control" value="{{ $inventory->weight_per_packet}}" required><br/>

            </div>

            <div class="col-md-2">

                <label for="description">Unit</label><br/>

                <select name="weight_unit" id="weight_unit" class="form-control" required>

                    <option value="Kg"

                    @if($inventory->weight_unit=="kilogram" || $inventory->weight_unit=="Kg")

                    selected

                    @endif

                    >Kg</option>

                    <option value="gm"

                    @if($inventory->weight_unit=="gram" || $inventory->weight_unit=="gm")

                    selected

                    @endif

                    >gm</option>

                    <option value="Ltr"

                    @if($inventory->weight_unit=="litre" || $inventory->weight_unit=="Ltr")

                    selected

                    @endif

                    >Ltr</option>

                    <option value="ml"

                    @if($inventory->weight_unit=="mililitre" || $inventory->weight_unit=="ml")

                    selected

                    @endif

                    >ml</option>
                    
                    <option value="pc"

                    @if($inventory->weight_unit=="pc" || $inventory->weight_unit=="pc")

                    selected

                    @endif

                    >pc</option>

                </select><br/>

            </div>



            <div class="col-md-3">

                <label for="description">No of Packet</label><br/>

				<input type="text" id="no_of_packet" name="no_of_packet" class="form-control" value="{{ $inventory->no_of_packet}}" required><br/>

            </div>

            

             <div class="col-md-3">

                <label for="description">Avilable Packet</label><br/>

				<input type="text" id="available_qty" name="available_qty" class="form-control" value="{{ $inventory->available_qty}}" required><br/>

            </div>







            <div class="col-md-6">

                <label for="description">Total Weight</label><br/>

				<input type="text" id="total_weight" name="total_weight" class="form-control" value="{{ $inventory->total_weight }}"><br/>

            </div>



            <div class="col-md-6">

                <label for="description">CGST</label><br/>

				<input type="text" id="cgst" name="cgst" class="form-control" value="{{ $inventory->cgst }}"><br/>

            </div>





            <div class="col-md-6">

                <label for="description">SGST</label><br/>

				<input type="text" id="sgst" name="sgst" class="form-control" value="{{ $inventory->sgst }}"><br/>

            </div>

            <div class="col-md-6">

                <label for="description">IGST</label><br/>

				<input type="text" id="igst" name="igst" class="form-control" value="{{ $inventory->igst }}"><br/>

            </div>





            <div class="col-md-6">

                <label for="description">Description</label><br/>

				<textarea name="description" id="description" rows="10" cols="80" style="">{{$inventory->description}}</textarea><br/>

            </div>

            <div class="col-md-6">

                <label for="specification">Specification</label><br/>

				<textarea name="specification" id="specification" rows="10" cols="80" style="">{{$inventory->specification}}</textarea><br/>

			</div>

			<div class="col-md-6">

                <label for="specification">Bar Code</label><br/>

				<input type="text" id="barcode" name="barcode" class="form-control" value="{{ $inventory->barcode }}"><br/>

			</div>

            <div class="col-md-6">

                <label for="description">HSN</label><br/>

                <input type="text" id="hsn" name="hsn" class="form-control" value="{{ $inventory->hsn }}"><br/>

            </div>

            <div class="col-md-6">

                <label for="description">Manufacturer Details</label><br/>

                <input type="text" id="manufacturer_details" name="manufacturer_details" class="form-control" value="{{ $inventory->manufacturer_details }}"><br/>

            </div>

             <div class="col-md-6">

                <label for="description">Marketed By</label><br/>

                <input type="text" id="marketed_by" name="marketed_by" class="form-control" value="{{ $inventory->marketed_by }}"><br/>

            </div>

             <div class="col-md-6">

                <label for="description">Country Of Origin</label><br/>

                <input type="text" id="country_of_origin" name="country_of_origin" class="form-control" value="{{ $inventory->country_of_origin }}"><br/>

            </div>

             <div class="col-md-6">

                <label for="description">Customer Care Details</label><br/>

                <input type="text" id="customer_care_details" name="customer_care_details" class="form-control" value="{{ $inventory->customer_care_details }}"><br/>

            </div>

             <div class="col-md-6">

                <label for="description">Seller</label><br/>

                <input type="text" id="seller" name="seller" class="form-control" value="{{ $inventory->seller }}"><br/>

            </div>



            <div class="col-md-6">
                <label for="description">Wholesale Price</label><br/>
                <input type="text" id="wholesale_price" name="wholesale_price" class="form-control" value="{{ $inventory->wholesale_price }}"><br/>
            </div>

            <div class="col-md-6">
                <label for="description">Wholesale Min Qty</label><br/>
                <input type="text" id="wholesale_min_qty" name="wholesale_min_qty" class="form-control" value="{{ $inventory->wholesale_min_qty }}"><br/>
            </div>

            <div class="col-md-6">
                <label for="description">Expiry Date</label><br/>
                <input type="date" id="expiry_date" name="expiry_date" class="form-control" value="{{ $inventory->expiry_date }}"><br/>
            </div>





            <div class="col-xs-12 col-sm-12 col-md-12">

                <button type="submit" class="btn btn-primary">Save</button>

                <a class="btn btn-warning" href="{{ route('package_inventory.index') }}"> Back</a>

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

@endsection

