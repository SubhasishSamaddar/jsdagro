@extends('layouts.front')
<?php //echo Cart::getContent()->count();
//echo '<pre>'; print_r($data); echo '</pre>';?>
@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 my-3">
            <div class="pull-right">
                <div class="btn-group">
                   Payment
                </div>
            </div>
        </div>
    </div> 
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

	
			<?php /* if( !$productDetails->isEmpty() && $totalPrice>0 && isset(Auth::user()->id) )*/?>
				<form method="post" action="{{ route('place-order') }}" id="checkoutform">
				 @csrf  
					<input type="hidden" name="total_amount" value="{{ $data['total_amount'] }}">
					<!--input type="hidden" name="order_date" value="{{ $data['total_amount'] }}"-->
					<input type="hidden" name="shiping_street" value="{{ $data['shiping_street'] }}">
					<div class="row">
						<div class="col-md-12">
							<label for="description">Payment Mode</label>
							<select name="payment_mode" id="payment_mode" class="form-control">
							<option value="COD">Cash On Delivery</option>
							<option value="Online" disabled>Online Payment</option>
							</select>
						</div>
						
						
					</div>
					 <input type="hidden" name="new_token" value="{{ time() }}">
					<div class="row">
					
						<div class="col-md-6"><br>
							<button id="processtocheckout" class="btn btn-success">Place Order</a>
						</div>
					</div>
				</form>
			
							
</div>
@endsection

@section('css')
@endsection
@section('js')

@endsection



