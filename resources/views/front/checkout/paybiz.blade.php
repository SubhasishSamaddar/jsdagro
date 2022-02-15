@extends('layouts.front')

<?php //echo Cart::getContent()->count();

//echo '<pre>'; print_r($billingDetails); echo '</pre>';?>

@section('seo-header')

		<title>Shipping</title>

		<meta name="viewport" Content-Type="application/x-www-form-urlencoded">

		<meta type="accept" content="application/json">

		

@endsection	



@section('content')

	<div class="row">

		

		<div class="cartbox">

			<h3>Confirm Payment</h3>	

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



			<?php

			$MERCHANT_KEY = "PyEC8E";

			$SALT = "xPnmiTZ9";

			//$MERCHANT_KEY = "gtKFFx";

			//$SALT = "eCwWELxi";

	

		$txnid=substr(hash('sha256', mt_rand() . microtime()), 0, 20);

		//	$txnid= 12345;

			$name=$attributes['firstname'];

		//	$name= "Debayan";

			$email=$attributes['email'];

			$amount=$attributes['amount'];

			$phone=$attributes['phone'];

			//$surl=url('/').'/payment/success';

			//$furl=url('/').'/payment/failure';

			$surl='https://jsdagro.com/payment-success';

			$furl='https://jsdagro.com/payment-failure';

		//	$curl=url('/').'/payment/cancel';

			$productinfo=$attributes['productinfo'];

			

		

		$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";

	    //$hashString = $MERCHANT_KEY."|".$txnid."|".$amount."|".$productinfo."|".$name."|".$email."|1|2|3|4|5||||||".$SALT;

	    	$hashString = $MERCHANT_KEY."|".$txnid."|".$amount."|".$productinfo."|".$name."|".$email."|||||||||||xPnmiTZ9";

			//$hash = strtolower(hash('sha512', $hashString));

			$hash = hash('sha512', $hashString);

			 //echo $hashString;

			 //echo "<br>";

			 //echo $hash;

			 //die();

			?>

			

			<div class="tablecartdata paymentdetails">

			    <h4>Customer Details</h4>

			    <div class="labelcontrol"><strong>Name:</strong> {{ $name }}

				</div>

				<div class="labelcontrol"><strong>Email:</strong> {{ $email }}

				</div>

                <div class="labelcontrol"><strong>Phone:</strong> {{ $phone }}

				</div>

				

				<h4>Payable Amount</h4>

			    <div class="labelcontrol">

			        	<strong>Amount:</strong> ₹{{ number_format($amount,2) }}

			    </div>

			    </div>

			    

			    <form method="post" action="https://secure.payu.in/_payment" id="checkoutform" class="form-horizontal" >

				@csrf 

                <!-- <form action='https://test.payu.in/_payment' method='post'> -->

                    <input type="hidden" name="firstname" value="<?php echo $name ; ?>" />

                    <input type="hidden" name="lastname" value="<?php echo $name ; ?>" />

                    <input type="hidden" name="surl" value="<?php echo $surl ; ?>" />

                    <input type="hidden" name="_token" value="<?php echo csrf_token() ; ?>" />

                    <input type="hidden" name="phone" value="<?php echo $phone ; ?>" />

                    <input type="hidden" name="key" value="<?php echo $MERCHANT_KEY ; ?>" />

                    <input type="hidden" name="hash" value ="<?php echo $hash ; ?>" />

                    <!--<input type="hidden" name="curl" value="<?php //echo $curl ; ?>" />-->

                    <input type="hidden" name="furl" value="<?php echo $furl ; ?>" />

                    <!--<input type="hidden" name="txnid" value="<?php echo $attributes['txnid'] ; ?>" />-->

                    <input type="hidden" name="txnid" value="<?php echo $txnid ; ?>" />

                    <input type="hidden" name="productinfo" value="<?php echo $productinfo ; ?>" />

                    <input type="hidden" name="amount" value="<?php echo $amount ; ?>" readonly/>

                    <input type="hidden" name="email" value="<?php echo $email ; ?>" /><br>

					<input type="hidden" name="address1" value="<?php echo $attributes['address1']; ?>" />

					<input type="hidden" name="address2" value="<?php echo $attributes['address2']; ?>" />

					<input type="hidden" name="city" value="<?php echo $attributes['city']; ?>" />

					<input type="hidden" name="state" value="West Bengal" />

					<input type="hidden" name="country" value="India" />

					<input type="hidden" name="zipcode" value="<?php echo $attributes['zipcode']; ?>" />

                    <input type= "submit" value="Pay Now">

                    </form>

                  

				</form>

			</div>

		</div>

	</div>





@endsection



@section('css')

@endsection

@section('js')

	

@endsection







