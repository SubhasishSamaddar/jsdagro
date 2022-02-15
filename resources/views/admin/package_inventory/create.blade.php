@extends('layouts.admin')

@section('content_header')

<div class="row mb-2">

	<div class="col-sm-6">

	<h1>Inventory Management</h1>

	</div>

	<div class="col-sm-6">

	<ol class="breadcrumb float-sm-right">

		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>

        <li class="breadcrumb-item"><a href="{{ route('package_inventory.index') }}">Inventory</a></li>

		<li class="breadcrumb-item active">Create</li>

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

	            <h4>Create New Inventory</h4>

	        </div>

        </div>



		<div class="card-body">

        <form method="post" action="{{ route('package_inventory.store') }}" enctype="multipart/form-data">

        @csrf

        <div class="row">

            <div class="col-md-6">

                <div class="form-group">

                    <label for="prod_name">Name</label>

                    <input type="text" class="form-control" name="prod_name" id="prod_name"  value="{{ old('prod_name') }}" required/>

                </div>

                <div class="form-group">

                    <label for="category_id">Category</label>

                    <select name="category_id" id="category_id" class="form-control" required>

                        <option value="0">Select Category</option>

                        @foreach($categories as $value)

						<option value="{{ $value->id}}" {{ ($value->id==old('category_id'))?'selected':''}}>{{ $value->name}}</option>

                        @endforeach

                    </select>

                </div>



                <div class="form-group">

					<div class="btn btn-default btn-file">

						<i class="fas fa-paperclip"></i> Product Image

						<input type="file" id="product_image" name="product_image">

                  	</div>

                </div>

              



                <div class="form-group">

					<div class="btn btn-default btn-file">

						<i class="fas fa-paperclip"></i> Product Other Image

						<input type="file" id="other_image" name="other_image[]" multiple>

                  	</div>

                </div>

            </div>



            <div class="col-md-6">

                <div class="form-group">

                    <label for="price">Purchase Price</label>

                    <input type="text" class="form-control" name="purchased_price" id="purchased_price"  value="{{ old('purchased_price') }}" required autocomplete="off"/>

                </div>


                <div class="form-group">

                    <label for="price">Retail Price</label>

                    <input type="text" class="form-control" name="mrp" id="mrp"  value="{{ old('mrp') }}" required autocomplete="off"/>

                </div>



                <div class="form-group">

                    <label for="price">Selling Price</label>

                    <input type="text" class="form-control" name="selling_price" id="selling_price"  value="{{ old('selling_price') }}" required autocomplete="off"/>

                </div>

            </div>



            <div class="col-md-6" style="display: none;">

                <div class="form-group">

					<label for="status">Inventory In Out</label><br/>

					<input name="inventory_in_out" id="inventory_in_out" data-id="0" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="In" data-off="Out" checked>

				</div>

            </div>



            <div class="col-md-6" style="display: none;">

                <label for="category_id" style="display: none;">Package Location</label>

                <select name="package_location_id" id="package_location_id" class="form-control" required>

                    <option value="0">Select Package Location</option>

                    @foreach($package_location as $value)

                    <option value="{{ $value->id}}" {{ ($value->id==old('package_location_id'))?'selected':''}}>{{ $value->location_name}}</option>

                    @endforeach

                </select>

            </div>



            <div class="col-md-4">

                <label for="description">Weight per packet</label><br/>

				<input type="text" id="weight_per_packet" name="weight_per_packet" class="form-control" value="{{ old('weight_per_packet') }}" required onkeyup="calculate_total_weight()"><br/>

            </div>

            <div class="col-md-2">

                <label for="description">Unit</label><br/>

                <select name="weight_unit" id="weight_unit" class="form-control" required>

                    <option value="Kg">Kg</option>

                    <option value="gm">gm</option>

                    <option value="Ltr">Ltr</option>

                    <option value="ml">ml</option>
                    
                    <option value="pc">pc</option>

                </select><br/>

            </div>



            <div class="col-md-6">

                <label for="description">No of packet</label><br/>

				<input type="text" id="no_of_packet" name="no_of_packet" class="form-control" value="{{ old('no_of_packet') }}" required onkeyup="calculate_total_weight()"><br/>

            </div>



            <div class="col-md-6">

                <label for="description">Total Weight</label><br/>

				<input type="text" id="total_weight" name="total_weight" class="form-control" value="{{ old('total_weight') }}"><br/>

            </div>



            <div class="col-md-6">

                <label for="description">CGST</label><br/>

				<input type="text" id="cgst" name="cgst" class="form-control" value="{{ old('cgst') }}"><br/>

            </div>





            <div class="col-md-6">

                <label for="description">SGST</label><br/>

				<input type="text" id="sgst" name="sgst" class="form-control" value="{{ old('sgst') }}"><br/>

            </div>

            <div class="col-md-6">

                <label for="description">IGST</label><br/>

				<input type="text" id="igst" name="igst" class="form-control" value="{{ old('igst') }}"><br/>

            </div>



            <div class="col-md-6">

                <label for="description">Description</label><br/>

				<textarea name="description" id="description" rows="10" cols="75" style="">{{ old('description') }}</textarea><br/>

            </div>

            <div class="col-md-6">

                <label for="specification">Specification</label><br/>

				<textarea name="specification" id="specification" rows="10" cols="75" style="">{{ old('specification') }}</textarea><br/>

			</div>

			 <div class="col-md-6">

                <label for="specification">Bar Code</label><br/>

				<input type="text" id="barcode" name="barcode" class="form-control" value="{{ old('barcode') }}"><br/>

			</div>





            <div class="col-md-6">

                <label for="specification">HSN</label><br/>

				<input type="text" id="hsn" name="hsn" class="form-control" value="{{ old('hsn') }}"><br/>

			</div>

            <div class="col-md-6">

                <label for="specification">Discount</label><br/>

				<input type="text" id="discount" name="discount" class="form-control" value="{{ old('discount') }}"><br/>

			</div>

            <div class="col-md-6">

                <label for="specification">Manufacturer Ddetails</label><br/>

				<input type="text" id="manufacturer_details" name="manufacturer_details" class="form-control" value="{{ old('manufacturer_details') }}"><br/>

			</div>

            <div class="col-md-6">

                <label for="specification">Marketed By</label><br/>

				<input type="text" id="marketed_by" name="marketed_by" class="form-control" value="{{ old('marketed_by') }}"><br/>

			</div> 

            <div class="col-md-6">

                <label for="specification">Country Of Origin</label><br/>

				<input type="text" id="country_of_origin" name="country_of_origin" class="form-control" value="{{ old('country_of_origin') }}"><br/>

			</div>

            <div class="col-md-6">

                <label for="specification">Customer Care Details</label><br/>

				<input type="text" id="customer_care_details" name="customer_care_details" class="form-control" value="{{ old('customer_care_details') }}"><br/>

			</div>

            <div class="col-md-6">

                <label for="specification">Seller</label><br/>

				<input type="text" id="seller" name="seller" class="form-control" value="{{ old('seller') }}"><br/>

			</div>



            <div class="col-md-6" >

                <label for="category_id" >Similar Product</label>

                <select name="similar_product" id="similar_product" class="form-control" required>

                    <option value="0">Select Similar Product</option>

                    @foreach($all_inventory_products as $value)

                    <option value="{{ $value->prod_name}}" {{ ($value->prod_name ==old('prod_name'))?'selected':''}}>{{ $value->prod_name}}</option>

                    @endforeach

                </select>

            </div>







            <div class="col-xs-12 col-sm-12 col-md-12">

                <button type="submit" class="btn btn-primary">Save</button>

                <a class="btn btn-warning" href="{{ route('products.index') }}"> Back</a>

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

<script>

    function calculate_total_weight()

    {

        var weight_per_packet = $("#weight_per_packet").val();

        var no_of_packet = $("#no_of_packet").val();

        $("#total_weight").val(Number(weight_per_packet)*Number(no_of_packet));

    }

</script>

@endsection

