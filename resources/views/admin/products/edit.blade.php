@extends('layouts.admin')

@section('content_header')

<div class="row mb-2">

	<div class="col-sm-6">

	<h1>Product Management</h1>

	</div>

	<div class="col-sm-6">

	<ol class="breadcrumb float-sm-right">

		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>

        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>

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

                    <label for="prod_name">Name</label>

                    <input type="text" class="form-control" name="prod_name" id="prod_name"  value="{{$product->prod_name}}" required/>

                </div>

                <div class="form-group">

                    <label for="category_id">Category</label>

                    <select name="category_id" id="category_id" class="form-control" required>

                        <option value="0">Select Category</option>

                        @foreach($categories as $value)

						<option value="{{ $value->id}}" {{ ($value->id==$product->category_id)?'selected':''}}>{{ $value->name}}</option>

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
                    <label for="price">MRP</label>
                    <input type="text" class="form-control" name="max_price" id="max_price"  value="{{$product->max_price}}" <?php if(count($get_weight_price_by_product_id)>0) { echo 'readonly'; } ?> required autocomplete="off"/>
                </div>

                

                <div class="form-group">

                    

                    <label for="price">Selling Price</label>

                    <input type="text" class="form-control" name="price" id="price"  value="{{$product->price}}" onkeyup="check_price()" required autocomplete="off"/>

                    

                </div>

                

                <div class="form-group">

					<label for="status">Status</label><br/>

					<input name="status" id="status" data-id="0" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="Inactive" {{ ($product->status=='Active')? 'checked' : '' }}>

                </div>

                <div class="form-group">

					<img src="{{ asset('/storage/'.$product->product_image)}}" style="height:60px;" />

                </div>

            </div>



            <div class="col-md-6">

                <label for="description">Sku</label><br/>

				<input type="text" id="sku" name="sku" class="form-control" value="{{ $product->sku }}"><br/>

            </div>

            <div class="col-md-6">

                <label for="description">Swadesh Hut</label><br/>

				<select name="swadesh_hut_id" class="form-control" id="swadesh_hut_id">

                    <option value="">Select</option>

                    @if(count($swadesh_huts)>0)

                    @foreach($swadesh_huts as $huts)

                    <option value="{{ $huts->id }}"

                    @if($huts->id==$product->swadesh_hut_id)

                    selected

                    @endif

                    >{{ $huts->location_name }}</option>

                    @endforeach

                    @endif

                </select>

                <br/>

            </div>



            





            <div class="col-md-4">

                <label for="description">Weight Per Pkt</label><br/>

                <input type="text" id="weight_per_pkt" name="weight_per_pkt" class="form-control" value="{{ $product->weight_per_pkt }}"><br/>

            </div>

            <div class="col-md-2">

                <label for="description">Unit</label><br/>

                <select name="weight_unit" id="weight_unit" class="form-control">

                    <option value="">Select</option>

                    <option value="kg"

                    @if($product->weight_unit=="kg")

                    selected

                    @endif

                    >kg</option>

                    <option value="gm"

                    @if($product->weight_unit=="gm")

                    selected

                    @endif

                    >gm</option>

                    <option value="ltr"

                    @if($product->weight_unit=="ltr")

                    selected

                    @endif

                    >ltr</option>

                    <option value="ml"

                    @if($product->weight_unit=="ml")

                    selected

                    @endif

                    >ml</option>
                    
                    <option value="pc"

                    @if($product->weight_unit=="pc")

                    selected

                    @endif

                    >pc</option>

                </select><br/>

            </div>



            <div class="col-md-6">

                <label for="description">CGST</label><br/>

				<input type="text" id="cgst" name="cgst" class="form-control" value="{{ $product->cgst }}"><br/>

            </div>





            <div class="col-md-6">

                <label for="description">SGST</label><br/>

				<input type="text" id="sgst" name="sgst" class="form-control" value="{{ $product->sgst }}"><br/>

            </div>

            <div class="col-md-6">

                <label for="description">IGST</label><br/>

				<input type="text" id="igst" name="igst" class="form-control" value="{{ $product->igst }}"><br/>

            </div>



            <div class="col-md-6">

                <label for="description">Available Quantity</label><br/>

				<input type="text" id="available_qty" name="available_qty" class="form-control" value="{{ $product->available_qty }}"><br/>

            </div>

            <div class="col-md-6">

                <label for="description">Ordered Quantity</label><br/>

				<input type="text" id="ordered_qty" name="ordered_qty" class="form-control" value="{{ $product->ordered_qty }}"><br/>

            </div>











            <?php 

            $count=1;

            

            if(count($get_weight_price_by_product_id)>0)

            {

                foreach($get_weight_price_by_product_id as $weight_price)

                {

                    echo '<div class="row" id="price_container'.$count.'">';

                ?>

                    <div class="col-md-3">

                        <label for="description">Weight Per Pkt</label><br/>

                        <input type="text" id="product_weight_per_pkt" name="product_weight_per_pkt[]" class="form-control" value="{{ $weight_price->weight_per_pkt }}"><br/>

                    </div>

                    <div class="col-md-3">

                        <label for="description">Unit</label><br/>

                        <select name="product_weight_unit[]" id="product_weight_unit" class="form-control">

                            <option value="">Select</option>

                            <option value="kilogram" <?php if($weight_price->weight_unit=='kilogram') { echo 'selected=="selected"'; } ?>>Kilogram</option>

                            <option value="gram" <?php if($weight_price->weight_unit=='gram') { echo 'selected=="selected"'; } ?>>gram</option>

                            <option value="litre" <?php if($weight_price->weight_unit=='litre') { echo 'selected=="selected"'; } ?>>Litre</option>

                            <option value="mililitre" <?php if($weight_price->weight_unit=='mililitre') { echo 'selected=="selected"'; } ?>>mililitre</option>

                        </select><br/>

                    </div>

                    <div class="col-md-3">

                        <label for="description">Price</label><br/>

                        <input type="text" id="product_price" name="product_price[]" class="form-control" value="{{ $weight_price->price }}"><br/>

                    </div>

                    <?php 

                    if($count==1)

                    {

                    ?>

                        <div class="col-md-3">

                            <label for="description">&nbsp;</label><br/>

                            <button type="button" id="add_more_weight_price_for_edit" class="btn btn-sm btn-primary">Add Weight/Price</button><br/>

                        </div>

                    <?php 

                    }

                    else 

                    {

                    ?>

                        <div class="col-lg-3">

                            <label for="description">&nbsp;</label><br/><button type="button" id="removeButton" class="btn btn-sm btn-danger" onclick="remove_edit_row('<?php echo $count; ?>')">Remove</button><br/>

                        </div>

                    <?php

                    }

                    echo '</div>';

                    ?>

                <?php

                $count++;

                }

                echo '<input type="hidden" name="aecounter" id="aecounter" value="'.$count.'">';

            }

            else 

            {

            ?>

            <div class="row">

                <div class="col-md-3">

                    <label for="description">Weight Per Pkt</label><br/>

                    <input type="text" id="product_weight_per_pkt" name="product_weight_per_pkt[]" class="form-control" value=""><br/>

                </div>

                <div class="col-md-3">

                    <label for="description">Unit</label><br/>

                    <select name="product_weight_unit[]" id="product_weight_unit" class="form-control">

                        <option value="">Select</option>

                        <option value="kilogram">Kilogram</option>

                        <option value="gram">gram</option>

                        <option value="litre">Litre</option>

                        <option value="mililitre">mililitre</option>

                    </select><br/>

                </div>

                <div class="col-md-3">

                    <label for="description">Price</label><br/>

                    <input type="text" id="product_price" name="product_price[]" class="form-control" value=""><br/>

                </div>

                <div class="col-md-3">

                    <label for="description">&nbsp;</label><br/>

                    <button type="button" id="add_more_weight_price" class="btn btn-sm btn-primary">Add Weight/Price</button><br/>

                </div>

            </div>

            <?php 

            }

            ?>

            <div id="TextBoxesGroup"></div>



            <div class="col-md-4">
                <label for="description">Meta Keyword</label><br/>
                <textarea name="meta_keyword" id="meta_keyword" class="form-control">{{ $product->meta_keyword }}</textarea>
            </div>
            <div class="col-md-4">
                <label for="description">Meta Description</label><br/>
                <textarea name="meta_description" id="meta_description" class="form-control">{{ $product->meta_description }}</textarea>
            </div>
            <div class="col-md-4">
                <label for="description">Tag</label><br/>
                <input type="rext" name="tags" id="tags" class="form-control" value="{{ $product->tags }}">
            </div>





            <div class="col-md-12">

                <label for="description">Description</label><br/>

				<textarea name="description" id="description" rows="10" cols="80" style="">{{$product->description}}</textarea><br/>

            </div>

            <div class="col-md-12">

                <label for="specification">Specification</label><br/>

				<textarea name="specification" id="specification" rows="10" cols="80" style="">{{$product->specification}}</textarea><br/><br/>

            </div>

            

             <div class="col-md-6">

                <label for="description">Bar Code</label><br/>

                <input type="text" id="barcode" name="barcode" class="form-control" value="{{ $product->barcode }}"><br/>

            </div>



            <div class="col-md-6">

                <label for="description">HSN</label><br/>

                <input type="text" id="hsn" name="hsn" class="form-control" value="{{ $product->hsn }}"><br/>

            </div>

            <div class="col-md-6">

                <label for="description">Manufacturer Details</label><br/>

                <input type="text" id="manufacturer_details" name="manufacturer_details" class="form-control" value="{{ $product->manufacturer_details }}"><br/>

            </div>

             <div class="col-md-6">

                <label for="description">Marketed By</label><br/>

                <input type="text" id="marketed_by" name="marketed_by" class="form-control" value="{{ $product->marketed_by }}"><br/>

            </div>

             <div class="col-md-6">

                <label for="description">Country Of Origin</label><br/>

                <input type="text" id="country_of_origin" name="country_of_origin" class="form-control" value="{{ $product->country_of_origin }}"><br/>

            </div>

             <div class="col-md-6">

                <label for="description">Customer Care Details</label><br/>

                <input type="text" id="customer_care_details" name="customer_care_details" class="form-control" value="{{ $product->customer_care_details }}"><br/>

            </div>

             <div class="col-md-6">

                <label for="description">Seller</label><br/>

                <input type="text" id="seller" name="seller" class="form-control" value="{{ $product->seller }}"><br/>

            </div>


            <div class="col-md-6">
                <label for="description">Wholesale Price</label><br/>
                <input type="text" id="wholesale_price" name="wholesale_price" class="form-control" value="{{ $product->wholesale_price }}"><br/>
            </div>

            <div class="col-md-6">
                <label for="description">Wholesale Min Qty</label><br/>
                <input type="text" id="wholesale_min_qty" name="wholesale_min_qty" class="form-control" value="{{ $product->wholesale_min_qty }}"><br/>
            </div>

            <div class="col-md-6">
                <label for="description">Expiry Date</label><br/>
                <input type="date" id="expiry_date" name="expiry_date" class="form-control" value="{{ $product->expiry_date }}"><br/>
            </div>

           

            <div class="col-md-6">

                <label for="description">Is Featured?</label><br/>

                <input name="is_featured" id="is_featured" type="checkbox"

                @if($product->is_featured=="yes")

                checked

                @endif

                ><br/><br/>

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

<script src="<?php echo url('/') ?>/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>

<script src="<?php echo url('/') ?>/vendor/unisharp/laravel-ckeditor/adapters/jquery.js"></script>

<script>

    CKEDITOR.replace( 'description' );

    CKEDITOR.replace( 'specification' );

</script>





<script>

var counter = 2;



$("#add_more_weight_price").click(function () {

var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);

newTextBoxDiv.after().html('<div class="row"><div class="col-lg-3"><label for="description">Weight Per Pkt</label><br/><input type="text" id="product_weight_per_pkt" name="product_weight_per_pkt[]" class="form-control" value=""><br/></div><div class="col-lg-3"><label for="description">Unit</label><br/><select name="product_weight_unit[]" id="product_weight_unit" class="form-control"><option value="">Select</option><option value="kilogram">Kilogram</option><option value="gram">gram</option><option value="litre">Litre</option><option value="mililitre">mililitre</option></select><br/></div><div class="col-lg-3"><label for="description">Price</label><br/><input type="text" id="product_price" name="product_price[]" class="form-control" value=""><br/></div><div class="col-lg-3"><label for="description">&nbsp;</label><br/><button type="button" id="removeButton" class="btn btn-sm btn-danger" onclick="remove_row(\''+counter+'\')">Remove</button><br/></div></div>');

newTextBoxDiv.appendTo("#TextBoxesGroup");

counter++;

});



function remove_row(rcounter)

{

    $("#TextBoxDiv" + rcounter).remove();

}

















var aecounter = $("#aecounter").val();



$("#add_more_weight_price_for_edit").click(function () {

var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + aecounter);

newTextBoxDiv.after().html('<div class="row"><div class="col-lg-3"><label for="description">Weight Per Pkt</label><br/><input type="text" id="product_weight_per_pkt" name="product_weight_per_pkt[]" class="form-control" value=""><br/></div><div class="col-lg-3"><label for="description">Unit</label><br/><select name="product_weight_unit[]" id="product_weight_unit" class="form-control"><option value="">Select</option><option value="kilogram">Kilogram</option><option value="gram">gram</option><option value="litre">Litre</option><option value="mililitre">mililitre</option></select><br/></div><div class="col-lg-3"><label for="description">Price</label><br/><input type="text" id="product_price" name="product_price[]" class="form-control" value=""><br/></div><div class="col-lg-3"><label for="description">&nbsp;</label><br/><button type="button" id="removeButton" class="btn btn-sm btn-danger" onclick="remove_row(\''+aecounter+'\')">Remove</button><br/></div></div>');

newTextBoxDiv.appendTo("#TextBoxesGroup");

aecounter++;

});



function remove_edit_row(ecounter)

{

    $("#price_container" + ecounter).remove();

}


function check_price()
{
    var price = $("#price").val();
    var main = price.split(".");
    var max_price = $("#max_price").val();
    var main2 = max_price.split(".");

    

    if(Number(main[0])>Number(main2[0]))
    {
        alert('Selling Price Can not be greater than MRP');
        $("#price").val('');
        return false;
        
    }
}

</script>

@endsection

