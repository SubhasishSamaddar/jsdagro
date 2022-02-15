

<!-- 
	Upper Header Section 
-->
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="topNav">
		<div class="container">
			<div class="alignR">
				<div class="pull-left socialNw">
					<a href="{{ route('sitelink') }}">
					<img src="{{ asset('front-assets/images/logo.png') }}" alt="Swadesh Hut" height="30" width="224">	
					</a>
					<!--a href="#"><span class="icon-twitter"></span></a>
					<a href="#"><span class="icon-facebook"></span></a>
					<a href="#"><span class="icon-youtube"></span></a>
					<a href="#"><span class="icon-tumblr"></span></a-->
				</div>
				
				<a class="active" href="{{ route('sitelink') }}"> <span class="icon-home"></span> Home</a> 
				@if( isset(Auth::user()->id) )
					<a href="{{ route('my-order') }}"><span class="icon-user"></span> My Account</a>
				
				@else
					<a href="{{ route('login') }}"><span class="icon-user"></span> Login </a>
					<a href="{{ route('register') }}"><span class="icon-user"></span> Registration </a>
				@endif

				<!--a href="register.html"><span class="icon-edit"></span> Free Register </a--> 
				<!--a href="contact.html"><span class="icon-envelope"></span> Contact us</a-->
				<a data-toggle="modal" href="#myModal">
					<span class="icon-globe" id="storeid">
						<?php /*
						@if(Session::has('swadesh_hut_name'))
							Store Name : {{ Session::get('swadesh_hut_name') }}
						@else
							{{ "Select Store" }}
						@endif
						*/
						?>
						@if(Cookie::has('swadesh_hut_name'))
							Store Name : {{ Cookie::get('swadesh_hut_name') }}
						@else
							{{ "Select Store" }}
						@endif
					</span>
				</a>
				<a href="{{ route('cart') }}"><span class="icon-shopping-cart"></span>
					<span id="shprocnt">
					<?php
						$productCount=0;
						foreach( Cart::getContent() as $pdKey=>$pdValue ):
							$productCount=$productCount+$pdValue->quantity;
						endforeach;
						echo $productCount;
					?> item(s)<!--span class="badge badge-warning"> $448.42</span-->
					</span>
				</a>
			</div>
		</div>
	</div>
</div>


<div class="container">
	<div id="gototop"> </div>
		<!-- Lower Header -->
		<!--header id="header">
			<div class="row">
				<div class="span4">
					<h1>
						<a class="logo" href="index.html"><span>Twitter Bootstrap ecommerce template</span> 
							<img src="{{ asset('front-assets/images/logo.png') }}" alt="bootstrap sexy shop" height="50" width="224">
						</a>
					</h1>
				</div>
				<div class="span4">
					<div class="offerNoteWrapper">
						<h1 class="dotmark">
							<i class="icon-cut"></i>
							Twitter Bootstrap shopping cart HTML template is available @ $14
						</h1>
					</div>
				</div>
				<div class="span4 alignR">
					<p><br> <strong> Support (24/7) :  0800 1234 678 </strong><br><br></p>
					<span class="btn btn-mini">[ 2 ] <span class="icon-shopping-cart"></span></span>
					<span class="btn btn-warning btn-mini">$</span>
					<span class="btn btn-mini">&pound;</span>
					<span class="btn btn-mini">&euro;</span>
				</div>
			</div>
		</header-->
		
<br>		
<br>		
<br>		
<br>		
<!-- Navigation	-->
<!--div class="navbar">
	<div class="navbar-inner">
		<div class="container">
			<a data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<div class="nav-collapse">
				<ul class="nav">
					<li class="active"><a href="{{ route('sitelink') }}">Home	</a></li>
					<li class=""><a href="list-view.html">List View</a></li>
					<li class=""><a href="grid-view.html">Grid View</a></li>
					<li class=""><a href="three-col.html">Three Column</a></li>
					<li class=""><a href="four-col.html">Four Column</a></li>
					<li class=""><a href="general.html">General Content</a></li>
				</ul>
				<form action="#" class="navbar-search pull-left">
					<input type="text" placeholder="Search" class="search-query span2">
				</form>
				<ul class="nav pull-right">
					<li class="dropdown">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#"><span class="icon-lock"></span> Login <b class="caret"></b></a>
						<div class="dropdown-menu">
							<form class="form-horizontal loginFrm">
							<div class="control-group">
							<input type="text" class="span2" id="inputEmail" placeholder="Email">
							</div>
							<div class="control-group">
							<input type="password" class="span2" id="inputPassword" placeholder="Password">
							</div>
							<div class="control-group">
							<label class="checkbox">
							<input type="checkbox"> Remember me
							</label>
							<button type="submit" class="shopBtn btn-block">Sign in</button>
							</div>
							</form>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div-->
<div class="row">

<?php //echo Helper::printMenu('0');?>
