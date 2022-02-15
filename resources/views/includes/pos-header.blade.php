<div id="head">
<header class="siteHeader">
<div class="container">
	<div class="header_wrapper" style="text-align: center;">
	<!-- Logo -->
	<a href="{{ route('sitelink') }}" class="siteLogo" style="float: none; margin: 0px auto; text-align: center; display: inline-block;">
		<img src="{{ asset('front-assets/images/swadesh-haat-logo.png') }}" alt="JSD Agro">
	</a>

	<!--<a href="javascript:void(0);" class="menu_toggle"><b></b><b></b><b></b></a>-->

	<!-- Logo -->	

	<!-- Main Search -->
	<!--<div class="search-box">
		<form action="{{ route('search-result') }}" method="get">
			<input type="text" name="q" id="searchtext" autocomplete="off" class="searchtext" value="{{ Request::get('q') }}" placeholder="Search for products" required>
			<button class="search__btn"></button>
		</form>  
	</div>-->
	<!-- Main Search -->

	<!-- Account -->
	<!---->
	<!-- Account -->
	


	<!-- Cart -->
	<!--<a href="{{ route('cart') }}" class="hdrCart">-->
	<!--	<span id="shprocnt">-->
 <!--         My Cart-->
 <!--         <strong>-->
				<?php
				// 	$productCount=0;
				// 	foreach( Cart::getContent() as $pdKey=>$pdValue ):
				// 		$productCount=$productCount+$pdValue->quantity;
				// 	endforeach;
				// 	echo $productCount;
				?> <!-- items-->
 <!--         </strong>-->
	<!--	</span>-->
	<!--</a>-->
	<!-- Cart -->
		</div><!-- .header_wrapper -->
  </div><!-- .container -->
</header>
<nav class="topnav orangecol">
  <div class="container">
      <div class="welc">
       @if (Session::has('pos_swadesh_hut'))
	    Welcome {{  Session::get('pos_swadesh_hut') }}  <a href="{{ route('poslogout') }}" class="">Logout</a>

			
			@endif
	        
</div>
	
    
  </div>
</nav>
</div>  
 

<div class="wrapper-fluid">
 
	 
<?php //echo Helper::printMenu('0');?>
