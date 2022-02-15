<div id="head">
<div class="headertop">
    <div class="container">
        <div class="topbar-notice">
            <p>100% Secure delivery without contacting the courier &nbsp; &nbsp; | &nbsp; &nbsp;Need help? Call Us: <strong>8617771271</strong></p>
            <p style="float:right;"><a href="{{ url('/') }}/poslogin">Point Of Sale</a></p>
        </div>
        
    </div>
</div>    
    
    
<header class="siteHeader">
<div class="container">
	<div class="header_wrapper">
	<!-- Logo -->
	<a href="{{ route('sitelink') }}" class="siteLogo">
		<img src="{{ asset('front-assets/images/swadesh-haat-logo.png') }}" alt="JSD Agro">
	</a>

	<!--<a href="javascript:void(0);" class="menu_toggle"><b></b><b></b><b></b></a>-->

	<!-- Logo -->
	
	
	<div class="storeLocation">
		<span class="globe" id="storeid">
					<?php /*
					@if(Session::has('swadesh_hut_name'))
						Store Name : {{ Session::get('swadesh_hut_name') }}
					@else
						{{ "Select Store" }}
					@endif
					*/
					?>
          Deliver To
          <strong>
		  			<?php echo Session::get('swadesh_hut_name');  ?>
					
          </strong>
		</span>
		<i class="icon-angle-down"></i>
		
		
	</div>
	
	<div class="storezipchanger">
	    
	    @php
			if(Cookie::has('swadesh_hut_name')):
				$swadesh_pin_code=Cookie::get('swadesh_pin_code');
			else:
				$swadesh_pin_code='';
			endif;
			@endphp
            <div class="modal-body">
            	<div class="modal-title">Enter Your Pincode Here</div>
				<div class="form-row">
					<input type="text" id="swadesh_hut_id" class="form-control" value="{{ $swadesh_pin_code}}" placeholder="700009">
					<input class="site-btn" type="submit" onclick="getSwadeshHut()" name="" value="Check Availability">
				</div>				
				<span id="msgstorename" style="display:none;"></span>
            </div>  
	</div>
	
	
	<!--<div class="callusat">
	    <p><span>Call Us At</span><br/>8617771271</p>
	    
	</div>-->
	
	<!-- Main Search -->
	<div class="search-box">
		<form action="{{ route('search-result') }}" method="get">
			<input type="text" name="q" id="searchtext" autocomplete="off" class="searchtext" value="{{ Request::get('q') }}" placeholder="Search for products" required>
			<button class="search__btn"></button>
		</form>  
	</div>
	<!-- Main Search -->


	
	<!-- Cart -->
	<a href="{{ route('cart') }}" class="hdrCart">
		<span id="shprocnt">
         
          <strong>
				<?php
					$productCount=0;
					foreach( Cart::getContent() as $pdKey=>$pdValue ):
						$productCount=$productCount+$pdValue->quantity;
					endforeach;
					echo $productCount;
				?> 
          </strong><!--span class="badge badge-warning"> $448.42</span-->
		</span>
	</a>
	<!-- Cart -->


	<!-- Account -->
		<div class="accountNvlnk">
	    @if( isset(Auth::user()->id) )
	    <a class="myaccn logged">My Account<br/><span>Welcome User</span> </a>
	    <ul class="submyaccn">
				<li><a href="{{ route('my-profile') }}" >My Profile</a></li>
				<li><a href="{{ route('my-order') }}" >My Orders</a></li>
				<li><a href="{{ route('logout') }}" >Logout</a></li>
			@else
		<a class="myaccn">My Account<br/><span>Login/Sign Up</span> </a>
	    <ul class="submyaccn">
				<li><a href="{{ route('login') }}" class="signin"> SignIn </a></li>
				<li><a href="{{ route('register') }}" class="signup"> SignUp </a></li>
			@endif
	        
	    </ul>
	</div>
	<!-- Account -->
	
	


		</div><!-- .header_wrapper -->
  </div><!-- .container -->
</header>
<nav class="topnav">
    <div class="storezipoverlay">	</div>
  <div class="container">
      
      <!-- Store-->
	<div class="shopbycategories"><p><span>Shop By</span><br/>Categories</p></div> 
	

	
	<!-- Store -->
	
	<ul class="menus">
	    @isset($parentCategory)
	    	@php
			if(!$parentCategory->isEmpty()):
				foreach( $parentCategory as $newData ):
				@endphp
					<li>
						<a href="{{ route('category', $newData->name_url) }}">{{ $newData->name }}</a>
						<!--<ul class="submenu">
						    <li>
						        <a href="#">Dals & Pulses</a>
						        <ul class="submenu lvl2">
        						    <li><a href="#">Dals & Pulses</a></li>
        						    <li><a href="#">Rice & Rice Products</a></li>
        						    <li><a href="#">Wheat & Flours</a></li>
        						    <li><a href="#">Salt, Sugar & Jaggery</a></li>
        						</ul>
						    </li>
						    <li><a href="#">Rice & Rice Products</a></li>
						    <li><a href="#">Wheat & Flours</a></li>
						    <li><a href="#">Salt, Sugar & Jaggery</a></li>
						</ul>-->
						@php Helper::printMenu($newData->id); @endphp
					</li>
				@php
				endforeach;
			endif;
			@endphp
	    @endisset
	</ul>
	
    
      
     <div class="catlinks">
         <ul>
       <li><a href="<?php echo url('/'); ?>/category/rice-and-rice-products" class="rice">Rice & Rice Products</a></li>
       <li><a href="<?php echo url('/'); ?>/category/mustard-oil" class="mustard">Mustard Oil</a></li>
       <li><a href="<?php echo url('/'); ?>/category/blended-spices" class="spices">Blended Spices</a></li>
       <li><a href="<?php echo url('/'); ?>/category/millets" class="millets">Millets</a></li>
	   <li><a href="<?php echo url('/'); ?>/category/ketchup-and-sauces" class="ketchups">Ketchups & Sauces</a></li>
    </ul>
     </div>
    
  </div>
</nav>
</div> 
<div class="wrapper">
<?php //echo Helper::printMenu('0');?>
