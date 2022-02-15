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
	            <h4>Create New Product</h4>
	        </div>
        </div>

		<div class="card-body">
        <form method="post" action="{{ route('products.store') }}" enctype="multipart/form-data">
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
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="price">MRP</label>
                    <input type="text" class="form-control" name="max_price" id="max_price"  value="{{ old('max_price') }}" required autocomplete="off"/>
                </div>
                <div class="form-group">
                    <label for="price">Selling Price</label>
                    <input type="text" class="form-control" name="price" id="price"  value="{{ old('price') }}" required autocomplete="off"/>
                </div>
                <div class="form-group">
					<label for="status">Status</label><br/>
					<input name="status" id="status" data-id="0" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="Inactive" {{ (old('status')=='on')? 'checked' : '' }}>
				</div>
            </div>
            <div class="col-md-6">
                <label for="description">Sku</label><br/>
				<input type="text" id="sku" name="sku" class="form-control" value="{{ old('sku') }}"><br/>
            </div>
            <div class="col-md-6">
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


            <div class="col-md-4">
                <label for="description">Weight Per Pkt</label><br/>
				<input type="text" id="weight_per_pkt" name="weight_per_pkt" class="form-control" value="{{ old('weight_per_pkt') }}"><br/>
            </div>
            <div class="col-md-2">
                <label for="description">Unit</label><br/>
                <select name="weight_unit" id="weight_unit" class="form-control">
                    <option value="">Select</option>
                    <option value="kg">kg</option>
                    <option value="gm">gm</option>
                    <option value="ltr">ltr</option>
                    <option value="ml">ml</option>
                    <option value="pc">pc</option>
                </select><br/>
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
                <label for="description">Available Quantity</label><br/>
				<input type="text" id="available_qty" name="available_qty" class="form-control" value="{{ old('available_qty') }}"><br/>
            </div>
            <div class="col-md-6">
                <label for="description">Ordered Quantity</label><br/>
				<input type="text" id="ordered_qty" name="ordered_qty" class="form-control" value="{{ old('ordered_qty') }}"><br/>
            </div>

            <div class="col-md-4">
                <label for="description">Meta Keyword</label><br/>
                <textarea name="meta_keyword" id="meta_keyword" class="form-control"></textarea>
            </div>
            <div class="col-md-4">
                <label for="description">Meta Description</label><br/>
                <textarea name="meta_description" id="meta_description" class="form-control"></textarea>
            </div>
            <div class="col-md-4">
                <label for="description">Tag</label><br/>
                <input type="rext" name="tags" id="tags" class="form-control">
            </div>

            <div class="col-md-12">
                <label for="description">Description</label><br/>
				<textarea name="description" id="description" rows="10" cols="75" style="">{{ old('description') }}</textarea><br/>
            </div>
            <div class="col-md-12">
                <label for="specification">Specification</label><br/>
				<textarea name="specification" id="specification" rows="10" cols="75" style="">{{ old('specification') }}</textarea><br/><br/>
			</div>
            <div class="col-md-6">
                <label for="description">Is Featured?</label><br/>
				<input name="is_featured" id="is_featured" type="checkbox"><br/><br/>
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
@endsection
