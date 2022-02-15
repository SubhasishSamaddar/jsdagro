@extends('layouts.front')
<?php //echo Cart::getContent()->count();
//echo '<pre>'; print_r($billingDetails); echo '</pre>';?>
@section('seo-header')
		<title>Shipping</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
@endsection	

@section('content')
<div class="container">
	<div class="row">
		
		<div class="cartbox">
			<h3>Checkout</h3>	
			@if ($errors->any())
				<div class="alert alert-danger">
					<strong>Whoops!</strong> There were some problems with your input.<br>
					<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
					</ul>
				</div>
			@endif
			
			<div class="tablecartdata">
				<form method="post" action="{{ route('place-order') }}" id="checkoutform" class="form-horizontal" >
				@csrf 
                    <div class="oneside">
					<h4>Billing Details</h4>
					<div class="control-group">
						<label class="control-label" for="inputFname">Contact Name<sup>*</sup></label>
						<div class="controls">
							<input type="text" name="billing_name" id="billing_name" placeholder="Contact Name" value="{{ $billingDetails->name }}">
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="inputFname">Contact Email<sup>*</sup></label>
						<div class="controls">
							<input type="email" name="billing_email" id="billing_email" placeholder="Contact Email" value="{{ $billingDetails->email }}" >
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="inputFname">Contact Phone<sup>*</sup></label>
						<div class="controls">
							<input type="text" name="billing_phone" id="billing_phone" placeholder="Contact Phone No" value="{{ $billingDetails->phone }}">
						</div>
					</div>
					
					
					<div class="control-group">
						<label class="control-label" for="inputFname">Address Line 1<sup>*</sup></label>
						<div class="controls">
							<input type="text" name="billing_street" id="billing_street" placeholder="Address Line 1" value="{{ $billingDetails->address_1 }}">
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="inputFname">Address Line 2</label>
						<div class="controls">
							<input type="text" name="billing_street_1" id="billing_street_1" placeholder="Address Line 2" value="{{ $billingDetails->address_2 }}">
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="inputFname">State <sup>*</sup></label>
						<div class="controls">
							<select name="billing_state" >
							<option value="">-- Select State --</option>
							@foreach( $stateDetails as $stateDetail )
								@if( $billingDetails->pincode && $stateDetail->state_name==$billingDetails->pincode )
									@php $selected='selected="selected"'; @endphp
								@elseif( $stateDetail->state_name=='West Bengal' )
									@php $selected='selected="selected"'; @endphp
								@else
									@php $selected=''; @endphp
								@endif
								<option value="{{ $stateDetail->state_name }}" {{ $selected }}>{{ $stateDetail->state_name }}</option>
							@endforeach
							</select>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="inputFname">City/Town <sup>*</sup></label>
						<div class="controls">
							<input type="text" name="billing_city" id="billing_city" placeholder="City/Town" value="{{ $billingDetails->city }}">
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="inputFname">Pincode <sup>*</sup></label>
						<div class="controls">
							<input type="text" name="billing_pincode" id="billing_pincode" value="{{ $billingDetails->pincode }}">
						</div>
					</div>
                      <div class="control-group">
						<div class="controls">
							<input type="checkbox" name="billing_shipping" onclick="asdf()" id="billing_shipping" value="1">&nbsp;Shipping Details Are Same As Billing Address
						
						</div>
						
					</div>
                  </div>
                  <div class="oneside">
					
					
					
					<h4>Shipping Details</h4>
					<input type="hidden" name="total_amount" value="{{ Session::get('total_price') }}">		
					<input type="hidden" name="payment_mode" value="COD">
					<div class="control-group">
						<label class="control-label" for="inputFname">Contact Name<sup>*</sup></label>
						<div class="controls">
							<input type="text" name="contact_name" id="contact_name" placeholder="Contact Name" value="{{ old('contact_no') }}" >
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="inputFname">Phone No<sup>*</sup></label>
						<div class="controls">
							<input type="text" name="contact_no" id="contact_no" placeholder="Contact No" value="{{ old('contact_no') }}" >
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="inputFname">Address Line 1<sup>*</sup></label>
						<div class="controls">
							<input type="text" name="shiping_street" id="shiping_street" placeholder="Shiping Street" value="{{ old('shiping_street') }}" >
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="inputFname">Address Line 2</label>
						<div class="controls">
							<input type="text" name="shiping_street_1" id="shiping_street_1" placeholder="Shiping Street 1" value="{{ old('shiping_street_1') }}" >
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="inputFname">State <sup>*</sup></label>
						<div class="controls">
							<select name="state" >
							<option value="">-- Select State --</option>
							@foreach( $stateDetails as $stateDetail )
								@if( $stateDetail->state_name=='West Bengal' )
									@php $selected='selected="selected"'; @endphp
								@else
									@php $selected=''; @endphp
								@endif
								<option value="{{ $stateDetail->state_name }}" {{ $selected }}>{{ $stateDetail->state_name }}</option>
							@endforeach
							</select>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="inputFname">City/Town <sup>*</sup></label>
						<div class="controls">
							<input type="text" name="city" id="city" placeholder="City/Town" value="{{ old('city') }}" >
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="inputFname">Pincode <sup>*</sup></label>
						<div class="controls">
							<input type="text" name="shiping_pincode" id="shiping_pincode" value="">
						</div>
					</div>  
					
					<div class="control-group">
						<label class="control-label" for="inputFname">Nearway Landmark/Location</label>
						<div class="controls">
							<input type="text" name="location" id="location" placeholder="Nearway Landmark/Location" value="{{ old('location') }}" required >
						</div>
					</div>
					
					</div>
					
					<div class="oneside">

				
					<h4>Payment Method</h4>
					<div class="control-group">
						<input type="radio" name="shipping_method" id="shipping_method" value="cod">&nbsp;&nbsp;<strong>CASH ON DELIVERY</strong><br clear="all"><br clear="all">
						<input type="radio" name="shipping_method" id="shipping_method" value="payumoney">&nbsp;&nbsp;<strong><img src="<?php echo url('/') ?>/public/img/payumoney.jpg" style="width:123px; height:63px;"></strong>
					</div>

					
					
					<input type="hidden" name="new_token" value="{{ time() }}">
					<div class="control-group">
						<div class="controls">
							<button type="button" name="submitAccount" value="Complete Order" class="exclusive shopBtn" onclick="check_pin()">Confirm Order</button>
						</div>
					</div>	
                  </div>
				  <input type="hidden" id="selected_swadesh_hut" value="<?php echo $selected_swadesh_hut; ?>">
                  
				</form>
			</div>
		</div>
	</div>

</div>
@endsection

@section('css')
@endsection
@section('js')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script> 
function asdf(){
	if($("#billing_shipping").prop('checked') == true){
		//alert($('#billing_street').val());
		$('#contact_name').val($('#billing_name').val());
		$('#contact_no').val($('#billing_phone').val());
		$('#shiping_street').val($('#billing_street').val());
		$('#shiping_street_1').val($('#billing_street_1').val());
		$('#city').val($('#billing_city').val());
		$("#shiping_pincode").val($('#billing_pincode').val());
	}else{
		$('#contact_name').val('');
		$('#contact_no').val('');
		$('#shiping_street').val('');
		$('#shiping_street_1').val('');
		$('#city').val('');
	}
}


function check_pin()
{
	if (!$("input[name='shipping_method']:checked").val()) {
       alert('Please Choose Shipment Method');
       return false;
    }
	var billing_pincode = $("#shiping_pincode").val();
	/*$.ajax({      
		type: "POST",
		url: "{{ url('check_pin') }}",   
		data: {'billing_pincode':billing_pincode,'_token':'<?php echo csrf_token(); ?>'},       
		success:     
		function(data){
			if(data.error==1)
			{
				swal("Oops!", data.msg , "error");
				return false;
			}
			else 
			{
				$("#checkoutform").submit();
			}
		}
	});*/
	var selected_swadesh_hut = $("#selected_swadesh_hut").val();
	var cover_area_pincode_array = @json($cover_area_pincode_array);
	console.log(cover_area_pincode_array);
	var obj = jQuery.parseJSON(cover_area_pincode_array);
	var match = 0;
	$.each(obj, function(key,value) {
		if(billing_pincode == value){
			match++;
		}
	});
	console.log(match);
	if(match==0)
	{
		swal("Oops!", "We dont serve this area" , "error");
		return false;
	}
	else 
	{
		$("#checkoutform").submit();
	}
}


</script>	
@endsection



