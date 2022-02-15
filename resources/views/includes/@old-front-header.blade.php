

<!-- 
	Upper Header Section 
-->
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="topNav">
		<div class="container">
			<div class="alignR">
				<div class="pull-left socialNw">
					<a href="#"><span class="icon-twitter"></span></a>
					<a href="#"><span class="icon-facebook"></span></a>
					<a href="#"><span class="icon-youtube"></span></a>
					<a href="#"><span class="icon-tumblr"></span></a>
				</div>
				<a class="active" href="index.html"> <span class="icon-home"></span> Home</a> 
				<a href="#"><span class="icon-user"></span> My Account</a> 
				<a href="register.html"><span class="icon-edit"></span> Free Register </a> 
				<a href="contact.html"><span class="icon-envelope"></span> Contact us</a>
				<a href="cart.html"><span class="icon-shopping-cart"></span> 2 Item(s) - <span class="badge badge-warning"> $448.42</span></a>
			</div>
		</div>
	</div>
</div>

<!--
Lower Header Section 
-->

<div class="container">
<div id="gototop"> </div>


<!DOCTYPE html>
<html lang="en">
<head>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<!--script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script-->
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<!--script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script-->

</head>
<body>
<nav class="navbar navbar-default">
<div class="container-fluid">
<div class="navbar-header">
<a class="navbar-brand" href="{{ route('product-listing')}}">Swadesh Huts</a>
<a class="navbar-brand" href="{{ route('my-order')}}">My Order</a>
</div>

<ul class="nav navbar-nav">
	<li>
		<a data-toggle="modal" href="#myModal"><span id="storeid">

			@if(Session::has('swadesh_hut_name'))
				Store Name : {{ Session::get('swadesh_hut_name') }}
			@else
				{{ "Select Store" }}
			@endif

		</a> |
		<a href="{{ route('cart')}}"><span id="shprocnt">
			<?php
				$productCount=0;
				foreach( Cart::getContent() as $pdKey=>$pdValue ):
					$productCount=$productCount+$pdValue->quantity;
				endforeach;
				echo $productCount;
			?> item(s)
		</a>
	</li>
</ul>
</div>
</nav>

